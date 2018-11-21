var q         = require("q"),
    request   = require("request"),
    db        = require("../../core/database.js"),
    events    = require("../../core/events.js"),
    nanoTimer = require("nanotimer"),
    timer     = new nanoTimer(),
    redisCl   = require("../../core/redis.js");

module.exports = function() {
    var self = this;

    timer.setInterval(function() {
        db.loadSoonToCloseAuctions().then(function (data) { // Soon to close auctions
            for (var i = 0, len = data.length; i < len; i++)
            {
                var timeleft = ( data[i].meta_value - Math.floor(Date.now() / 1000) );
                var id       = data[i].ID;

                if ( timeleft === 900 )
                {
                    db.getAuctionParticipants(id).then(function($users) {
                        var sentTo = [];
                        var count  = 0;

                        for(var l = 0, len1 = $users.length; l < len1; l++)
                        {
                            if ( sentTo.indexOf($users[l].uid) === -1 )
                            {
                                count++;
                                (function(l, id, count) {
                                    setTimeout(function() {
                                        request.get({
                                            rejectUnauthorized: false,
                                            url: [
                                                config.APP.BASE_URL,
                                                'mail-functions-api',
                                                '?token=', config.APP.REQUEST_TOKEN,
                                                '&action=auction_reminder',
                                                '&user_id=', $users[l].uid,
                                                '&auction_id=', id
                                            ].join("")}, function(err) {
                                            err && logger.log('error', 'An error occurred while sending emails: %s', err);
                                        });
                                    }, 120 * count);
                                })(l, id, count);

                                sentTo.push($users[l].uid);
                            }
                        }

                        logger.log('info', '[%s]: Sent the emails to participants of auction with id %s as time left is now 900 seconds.', INSTANCE, id);
                    });
                }

                if ( timeleft === 0 )
                {
                    /**
                     * Handle the auto bids and hopefully they
                     * will have enough time to process to
                     * increase the timers
                     */

                    self.autobid(id);
                }

                if ( timeleft > -1 )
                {
                    self.sockets.emitGlobal('SYNC_TIMER', {
                        id: data[i].ID,
                        time_left: timeleft
                    }, ['GLOBAL', 'AUCTION_' + data[i].ID], self.isMaster);
                }

                if ( timeleft <= 15 )
                {
                    logger.log('debug', '[%][%s]: %s', data[i].ID, ( timeleft >= 0 ? timeleft : '-' ));
                }

                /**
                 * Give it 2 seconds to process
                 * the auto bids
                 */

                if ( timeleft < -1 )
                {
                    (function (i, data) {
                        redisCl.get('AUCTION_CLOSED_' + data[i].ID, function(err, isClosing) {
                            if ( err )
                            {
                                logger.log('error', err);
                                return;
                            }

                            if ( isClosing )
                            {
                                logger.log('error',
                                    '[%s]: Auction with id %s is already pending closing, something might had gone wrong.',
                                    INSTANCE,
                                    data[i].ID );

                                return;
                            }

                            redisCl.set('AUCTION_CLOSED_' + data[i].ID, 1, function() {
                                self.handleAuctionClose(data[i].ID);
                            });
                        });
                    })(i, data);
                }
            }
        })
        .catch(function(error) {
            logger.log('error', '[%s]: Error occurred on global auction timer tick.', INSTANCE, error);
        });
    }, '', '1s');
};