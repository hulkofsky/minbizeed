var q        = require("q"),
    db       = require("../../core/database.js"),
    events   = require("../../core/events.js"),
    noop     = require("../../helpers/noop.js"),
    request  = require("request"),
    redisCl  = require("../../core/redis.js");

module.exports = function ($id, $userid, $autobid, $autobid_data) { // Bids on an auction
    var self = this;

    /**
     * Only take the credits if it
     * is an user requested bid
     *
     * @param $amount
     * @returns {*}
     */
    function takeCredits($amount)
    {
        if ( $autobid && !$autobid_data.take_credits )
        {
            return Promise.resolve();
        }

        if ( $autobid && $autobid_data.take_credits )
        {
            return db.decrementUserCredits($userid, $amount);
        }

        return db.decrementUserCredits($userid, $amount);
    }

    /**
     * Function to handle throttled
     * auction bids
     *
     * @param $throttle
     * @param $cost
     */
    function handleBid($throttle, $cost)
    {
        $throttle = ( $throttle === 0 ? 1 : $throttle ) * 30;

        logger.log('info',
            '[%s]: Bid for auction with id %s, for user %s scheduled with throttle %sms.',
            INSTANCE,
            $id,
            $userid,
            $throttle );

        setTimeout(function() {
            if ( isNaN($id) )
            {
                logger.info('info',
                    '[%s]: User with id %s tried to bid with %s as auction id.',
                    INSTANCE,
                    $userid,
                    $id );

                return;
            }

            redisCl.get('AUCTION_CLOSED_' + $id, function(err, isClosed) {
                if ( isClosed )
                {
                    logger.log('info',
                        '[%s]: User with id %s tried to bid on auction with id %s but redis says the auction is closed, ignoring the bid request.',
                        INSTANCE,
                        $userid,
                        $id );

                    redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                    return;
                }

                db.isAlreadyEnded($id).then(function ($ended) { // Check if the bid hasn't already ended
                    db.isTimeEnded($id).then(function($isTimeEnded) {
                        var bidTime = ( +($isTimeEnded[0].meta_value) - Math.floor(Date.now() / 1000) );

                        if ( bidTime < 0 && !$autobid )
                        {
                            logger.log('info',
                                '[%s]: Time left in database for auction with id %s is lower than 0, which means the auction is pending close, ignoring the bid request from user with id %s.',
                                INSTANCE,
                                $id,
                                $userid );

                            redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                            return;
                        }

                        if ( $ended[0].meta_value == 0 )
                        {
                            q.allSettled([db.getUser($userid), db.getAuctionData($id)]).spread(function ($user, $auction) {
                                if ( $auction.value.length > 0 && $user.value.length > 0 )
                                {
                                    
                                    var userName             = $user.value[0].meta_value,
                                        userAvatarId         = $user.value[3] ? $user.value[3].meta_value : false,
                                        userCountry          = $user.value[2] ? $user.value[2].meta_value : false,
                                        userCredits          = $user.value[1] ? Number($user.value[1].meta_value) : 0,
                                        auctionPriceIncrease = Number($auction.value[0].meta_value) || 1,
                                        auctionTimeLeft      = $auction.value[1].meta_value - Math.floor(Date.now() / 1000),
                                        auctionMultiplier    = Number($auction.value[4].meta_value) || 1,
                                        auctionCurrentBids   = Number($auction.value[3].meta_value) || 0;

                                    var newTotal     = ( auctionCurrentBids + auctionPriceIncrease ).toFixed(2);
                                    var autoBidsLeft = ( $autobid && $autobid_data.take_credits ) ? $autobid_data.credits_start - $autobid_data.credits_current : 0;

                                    if ( ( !$autobid ? ( userCredits >= auctionMultiplier ) : true )
                                         && ( $autobid ? ( $autobid_data.take_credits ? ( autoBidsLeft >= auctionMultiplier ) : true ) : true ) )
                                    {
                                        redisCl.get('AUCTION_CLOSED_' + $id, function(err, isClosed) {

                                            if ( isClosed )
                                            {
                                                logger.log('info',
                                                    '[%s]: User with id %s tried to bid on auction with id %s but redis says the auction is closed, ignoring the bid request.',
                                                    INSTANCE,
                                                    $userid,
                                                    $id );

                                                redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                                                return;
                                            }

                                            q.allSettled([userAvatarId ? db.getAvatar(userAvatarId) : null,
                                                takeCredits(auctionMultiplier),
                                                db.updateAuctionData($id, auctionPriceIncrease, (auctionTimeLeft < 15)), // Only change the time if less than 15 seconds
                                                db.insertBid($id, $userid, newTotal)]).spread(function ($avatar) {
                                                var userAvatar = $avatar.value != null ? $avatar.value[0].meta_value : "";
                                                if ( $autobid && $autobid_data && $autobid_data.take_credits )
                                                {
                                                   
                                                }
                                                else if ( $autobid && $autobid_data && !$autobid_data.take_credits )
                                                {
                                                    logger.log('info',
                                                        '[%s]: User with id %s has admin auto bid on auction with id %s on %ss time left.',
                                                        INSTANCE,
                                                        $userid,
                                                        $id,
                                                        bidTime);
                                                }
                                                else
                                                {
                                                    logger.log('info',
                                                        '[%s]: User with id %s has bid on auction with id %s on %ss time left.',
                                                        INSTANCE,
                                                        $userid,
                                                        $id,
                                                        bidTime);
                                                }

                                                redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, Number(auctionMultiplier), function(err, totalCreditsToBeUsed) {
                                                    if ( err )
                                                    {
                                                        logger.log('error',
                                                            '[%s]: Error occurred while incrementing user total bids credit count in redis: %s.',
                                                            INSTANCE,
                                                            err );

                                                        return;
                                                    }

                                                    logger.log('info',
                                                        '[%s]: Total credits that are still to be used for user with id %s: %s',
                                                        INSTANCE,
                                                        $userid,
                                                        totalCreditsToBeUsed );
                                                });

                                                redisCl.decr('AUCTION_BIDS_IN_PROGRESS_' + $id, function (err, bidsInProgress) {
                                                    logger.log('info', '[%s]: Bids left in current auction queue: %s', INSTANCE, bidsInProgress);

                                                    self.sockets.emitGlobal('NEW_BID', { // Tell the users there's a new bid and update the clients
                                                        id: $id,
                                                        time_left: (auctionTimeLeft < 15 ? 15 : auctionTimeLeft),
                                                        bid_amount: newTotal,
                                                        total: newTotal,
                                                        name: userName,
                                                        country: userCountry,
                                                        avatar: userAvatar
                                                    }, ['GLOBAL', 'AUCTION_' + $id], self.isMaster);

                                                    self.sockets.emitUserGlobal($userid, 'BID_OK', {
                                                        credits: userCredits - auctionMultiplier
                                                    }, self.isMaster);
                                                });
                                            });
                                        });
                                    }
                                    else
                                    {
                                        if ( $autobid && $autobid_data.take_credits )
                                        {
                                            /**
                                             * This is coming from an auto bid, and the user does
                                             * not have the bids anymore, pause the auto bid.
                                             */
                                             
                                        }
                                        else if ( !$autobid )
                                        {
                                            logger.log('info',
                                                '[%s]: User %s tried to bid on auction with id %s without having enough credits.',
                                                INSTANCE,
                                                $userid,
                                                $id );
                                        }

                                        redisCl.decr('AUCTION_BIDS_IN_PROGRESS_' + $id, noop);
                                        redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                                    }
                                }
                                else
                                {
                                    redisCl.decr('AUCTION_BIDS_IN_PROGRESS_' + $id, function() {
                                        logger.log('info',
                                            '[%s]: User with id %s tried to bid on an auction with no data.',
                                            INSTANCE,
                                            $userid );
                                    });

                                    redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                                }
                            });
                        }
                        else
                        {
                            redisCl.decr('AUCTION_BIDS_IN_PROGRESS_' + $id, function() {
                                logger.log('info',
                                    '[%s]: User with id %s tried to bid on an auction that has already closed.',
                                    INSTANCE,
                                    $userid );
                            });

                            redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                        }
                    });
                }, function (err) {
                    redisCl.decr('AUCTION_BIDS_IN_PROGRESS_' + $id, function() {
                        logger.log('error',
                            '[%s]: Error while checking if auction has already ended: %s',
                            INSTANCE,
                            err );
                    });

                    redisCl.decrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, $cost, noop);
                });
            });
        }, $throttle );
    }

    /**
     * Handle the actual bid
     */
    redisCl.get('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, function(err, totalCredits) {
        if ( err )
        {
            logger.log('error',
                '[%s]: Error occurred while getting the total credits for bids in queue of user %s redis: %s.',
                INSTANCE,
                $userid,
                err );

            return;
        }

        totalCredits = totalCredits || 0;

        q.allSettled([db.getUser($userid),
                      db.getAuctionData($id)]).spread(function ($user, $auction) {
            if ( $auction.value.length > 0 && $user.value.length > 0 )
            {
                var userCredits       = $user.value[1]? Number($user.value[1].meta_value) : 0,
                    auctionMultiplier = $auction.value[4] ? Number($auction.value[4].meta_value) : 1;

                if ( ( ( Number(totalCredits) + Number(auctionMultiplier) ) > userCredits )
                     && ( $autobid ? $autobid_data.take_credits : false ) )
                {
                    logger.log('info',
                        '[%s]: User with id %s tried to add bid to the auction with id %s queue without having enough credits for it to process.',
                        INSTANCE,
                        $userid,
                        $id);

                    return;
                }

                redisCl.incrby('TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_' + $userid, Number(auctionMultiplier), function(err, totalUsedCredits) {
                    if ( err )
                    {
                        logger.log('error',
                            '[%s]: Error occurred while incrementing user total bids credit count in redis: %s.',
                            INSTANCE,
                            err );

                        return;
                    }

                    redisCl.incr('AUCTION_BIDS_IN_PROGRESS_' + $id, function(err, bidsInProgress) {
                        if ( err )
                        {
                            logger.log('error',
                                '[%s]: Error occurred while getting the bids in progress from redis: %s.',
                                INSTANCE,
                                err );

                            return;
                        }

                        logger.log('info', '[%s]: Bid added to queue of auction with id %s.', INSTANCE, $id);
                        handleBid(bidsInProgress, Number(auctionMultiplier));
                    });
                });
            }
        });
    });
};