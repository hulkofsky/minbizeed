var q        = require("q"),
    db       = require("../../core/database.js"),
    events   = require("../../core/events.js"),
    request  = require("request"),
    redisCl  = require("../../core/redis.js");

/**
 * Automatically gives bids reward if the auction
 * gives bids as reward
 *
 * @param $id
 * @param winner
 */
function handleRewardType($id, winner) {
    var self = this;

    db.isBidRewardAuction($id).then(function(isBidReward) {
        if ( !isBidReward )
            return;

        /**
         * Only handle the bid reward if the bid_auction field is 1
         * inside the database
         */

        db.getAuctionBidReward($id).then(function($reward) {
            if ( !$reward )
                return;

            var amount = $reward[0].meta_value;

            logger.log('verbose',
                '[%s]: The ended auction was a bids reward auction with a value of [%s bids]. Giving the user the won bids.',
                INSTANCE,
                amount );

            db.incrementUserCredits(winner, amount).then(function() {
                logger.log('info',
                    '[%s]: Successfully given user with id %s [%s bids].',
                    INSTANCE,
                    winner,
                    amount );

                /**
                 * Tell the user bids were given
                 */

                self.sockets.emitUserGlobal(winner, 'AUCTION_BIDS_GIVEN', {
                    id: $id,
                    amount: amount
                }, self.isMaster);
            }, function(err) {
                if ( err )
                {
                    logger.log('error',
                        '[%s]: Error occurred while giving user with id %s [%s bids].',
                        INSTANCE,
                        winner,
                        err );
                }
            });
        });
    });
}

module.exports = function($id) {
    var self = this;

    logger.log('info',
        '[%s]: Timer on auction with id %s has finished, closing auction and setting the winner.',
        INSTANCE,
        $id );

    logger.log('info',
        '[%s]: Closing auction with id %s.',
        INSTANCE,
        $id );

    q.allSettled([
        db.getPriceData($id),
        db.setAuctionClosed($id),
        db.getPostData($id)
    ]).spread(function (priceData, auctionWinner, auctionData) {
        if (auctionWinner.state === "fulfilled")
        {
            var minimumPrice = Number(priceData.value[2].meta_value) || 0,
                currentBids  = Number(priceData.value[1].meta_value) || 0,
                startPrice   = Number(priceData.value[0].meta_value) || 0,
                auctionName  = auctionData.value[0].post_title;

            var isSatisfied = minimumPrice <= (currentBids + startPrice).toPrecision(2);

            if ( auctionWinner.value.winner !== 0 && isSatisfied )
            {
                db.getUser(auctionWinner.value.winner).then(function ($user) {
                    self.sockets.emitUserGlobal(auctionWinner.value.winner, 'AUCTION_WON', {
                        id: $id,
                        name: auctionName
                    }, self.isMaster);

                    self.sockets.emitGlobal('AUCTION_END', {
                        id: $id,
                        user: $user[0].meta_value,
                        name: auctionName
                    }, ['GLOBAL', 'AUCTION_' + $id], self.isMaster);

                    // Email the winner
                    request.get({
                        rejectUnauthorized: false,
                        url: [
                            config.APP.BASE_URL,
                            'mail-functions-api',
                            '?token=', config.APP.REQUEST_TOKEN,
                            '&action=user_won',
                            '&user_id=', auctionWinner.value.winner,
                            '&auction_id=', $id
                        ].join("")}, function(err) {
                        err && logger.log('error', '[%s]: An error occurred while sending emails: %s', INSTANCE, err);
                    });

                    logger.log('debug',
                        '[%s]: Emitted the auction end events and emailed the winner on auction with id %s.',
                        INSTANCE,
                        $id );

                    // Email the losers
                    db.getAuctionParticipants($id).then(function($users) {
                        var sentTo = [];
                        var count  = 0;

                        for(var l = 0, len1 = $users.length; l < len1; l++)
                        {
                            if ( $users[l].uid != auctionWinner.value.winner
                                && sentTo.indexOf($users[l].uid) === -1 )
                            {
                                count++;
                                (function(auctionWinner, $id, l, count) {
                                    setTimeout(function() {
                                        request.get({
                                            rejectUnauthorized: false,
                                            url: [
                                                config.APP.BASE_URL,
                                                'mail-functions-api',
                                                '?token=', config.APP.REQUEST_TOKEN,
                                                '&action=user_lost',
                                                '&user_id=', $users[l].uid,
                                                '&auction_id=', $id,
                                                '&winner_id=', auctionWinner.value.winner
                                            ].join("")}, function(err) {
                                            err && logger.log('error', '[%s]: An error occurred while sending emails: %s', INSTANCE, err);
                                        });
                                    }, 120 * count);
                                })(auctionWinner, $id, l, count);

                                sentTo.push($users[l].uid);
                            }
                        }

                        logger.log('info', '[%s]: Finished emailing the users on auction with id %s.', INSTANCE, $id);
                    });

                    /**
                     * If the winner still has the auto bid on for the won auction,
                     * set it as paused
                     */

                    db.getAutobid($id, auctionWinner.value.winner).then(function(data) {
                        if ( !data[0] )
                            return;

                        db.pauseAutobid($id, auctionWinner.value.winner).then(function () {
                            logger.log('info', '[%s]: Sent the winner auto bid pause as the auction has ended and he still had bids for this auction.', INSTANCE);

                            self.sockets.emitUserGlobal(auctionWinner.value.winner, 'AUTO_BID_PAUSED', {
                                id: $id
                            }, ['AUCTION_' + $id], self.isMaster);
                        });
                    });

                    /**
                     * If the auction is should give bids as reward,
                     * handle the reward automatically
                     */

                    handleRewardType.bind(self)($id, auctionWinner.value.winner);
                });
            }
            else if ( auctionWinner.value.winner !== 0 && !isSatisfied )
            {   // There's a winner but the auction didn't reach the
                // minimum amount.
                logger.log('info',
                    '[%s]: Auction with id %s has current price (%s), lower than minimum price(%s), setting winner as 0.',
                    INSTANCE,
                    $id,
                    (currentBids + startPrice).toFixed(2), minimumPrice);

                db.setNoWinnerOnAuction($id).then(function() {
                    self.sockets.emitGlobal('AUCTION_NOT_FULFILLED', {
                        id: $id,
                        name: auctionName
                    }, ['GLOBAL', 'AUCTION_' + $id], self.isMaster);

                    self.refundBids($id);
                });
            }
            else
            {   // No user has bid on this auction
                self.sockets.emitGlobal('AUCTION_END', {
                    id: $id,
                    user: 0,
                    name: auctionName
                }, ['GLOBAL', 'AUCTION_' + $id], self.isMaster);
            }

            setTimeout(function() {
                redisCl.del('AUCTION_CLOSED_' + $id, function(err) {
                    if ( err )
                    {
                        logger.log('error',
                            '[%s]: Error encountered while removing AUCTION_CLOSED flag from redis on auction with id %s.',
                            INSTANCE,
                            err,
                            $id );

                        return;
                    }

                    logger.log('debug',
                        '[%s]: Removed AUCTION_CLOSED on auction with id %s, auction should be closed by now.',
                        INSTANCE,
                        $id );
                });

                redisCl.del('AUCTION_BIDS_IN_PROGRESS_' + $id, function(err) {
                    if ( err )
                    {
                        logger.log('error',
                            '[%s]: Error encountered while removing AUCTION_BIDS_IN_PROGRESS flag from redis on auction with id %s.',
                            INSTANCE,
                            err,
                            $id );

                        return;
                    }

                    logger.log('debug',
                        '[%s]: Removed AUCTION_BIDS_IN_PROGRESS on auction with id %s, auction should be closed by now.',
                        INSTANCE,
                        $id );
                });

            }, 2500);
        }
        else
        {
            logger.log('error',
                '[%s]: Something went wrong while closing the auction with id %s, error %s.',
                INSTANCE,
                $id,
                auctionWinner.r );
        }
    });
};