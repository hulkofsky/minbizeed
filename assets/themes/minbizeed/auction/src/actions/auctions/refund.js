var q        = require("q"),
    db       = require("../../core/database.js"),
    events   = require("../../core/events.js"),
    request  = require("request");

module.exports = function($id) {
    var self = this;
    console.log('got into refund')
    logger.log('verbose', '[%s]: Refunding credits for auction with id %s as it didn\'t reach minimum bid price.', INSTANCE, $id);

    


    q.allSettled([db.getAuctionData($id),
                  db.getAuctionBids($id),
                  db.getPostData($id)]).spread(async function($auction, $bids,$postdata) {

        

        var usersBids  = {};
        var cost       = $auction.value[4].meta_value || 1;

        for(var i = 0, len = $bids.value.length; i < len; i++)
        {
            var current = $bids.value[i];
            console.log(current, 'current suka')
            if ( !usersBids.hasOwnProperty(current.uid) )
            {
                usersBids[current.uid]      = {};
                usersBids[current.uid].bids = Number(cost);
            }
            else
            {
                usersBids[current.uid].bids += Number(cost);
            }
        }

        var done = 0;

        for(var user in usersBids)
        {
            if ( usersBids.hasOwnProperty(user) )
            {
                db.insertRefundBid($id, user, usersBids[user].bids).then(function() {
                    done++;

                    if ( Object.keys(usersBids).length === done )
                    {
                        db.getAuctionRefunds($id).then(function(refunds) {
                            for(var i = 0, len = refunds.length; i < len; i++)
                            {
                                var refund = refunds[i];
                                db.handleRefund(refund.id, refund.uid, refund.bids, (function(refund) {
                                    self.sockets.emitUserGlobal(refund.uid, 'AUCTION_REFUNDED', {
                                        bids: refund.bids,
                                        auction:$postdata.value[0].post_title
                                    }, self.isMaster);

                                    (function(refund, $id, i) {
                                        setTimeout(function() {
                                            request.get({
                                                rejectUnauthorized: false,
                                                url: [
                                                    config.APP.BASE_URL,
                                                    'mail-functions-api',
                                                    '?token=', config.APP.REQUEST_TOKEN,
                                                    '&action=auction_not_fullfilled',
                                                    '&user_id=', refund.uid,
                                                    '&auction_id=', $id,
                                                    '&returned_bids=', refund.bids
                                                ].join("")
                                            }, function(err) {
                                                err && logger.log('error', 'An error occurred while sending emails: %s', err);
                                            });
                                        }, 120 * i);
                                    })(refund, $id, i);

                                })(refund));
                            }

                            logger.log('info', 'Finished refunding the credits to users.');
                        });
                    }
                }, function(err) {
                    logger.log('info', err);
                });
            }
        }
    });
};