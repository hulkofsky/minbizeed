/* ================ MODULES LOADING ========================*/
var db = require('mysql2'),
    q = require('q'),
    squel = require('squel'),
    pool = db.createPool(config.DATABASE);
var table_prefix='mb_272727023023_';
var date = require('date-and-time');

/* ===================== METHODS ============================*/
module.exports = {
    query: function ($query) { // query the database
        var deferred = q.defer();

        pool.query($query, function (err, rows) {
            if (err) {
                logger.log('error',
                    '[%s]: Error occurred while running sql query %s. Retrying running it.',
                    INSTANCE,
                    $query);

                pool.query($query, function (err, rows) {
                    logger.log('error',
                        '[%s]: Failed running query: %s, because of %s.',
                        INSTANCE,
                        $query,
                        err);

                    if (err) {
                        deferred.reject(err);
                    }
                    else {
                        deferred.resolve(rows);
                    }
                });
            }
            else {
                deferred.resolve(rows);
            }
        });

        return deferred.promise;
    },

    loadSoonToCloseAuctions: function () {
        var query = "SELECT ID, meta_value FROM \
                    "+table_prefix+"posts\
                    JOIN "+table_prefix+"postmeta\
                    ON "+table_prefix+"posts.ID="+table_prefix+"postmeta.post_id\
                    WHERE "+table_prefix+"posts.post_type = 'auction'\
                    AND "+table_prefix+"postmeta.meta_key = 'ending'\
                    AND "+table_prefix+"postmeta.meta_value <= (UNIX_TIMESTAMP() + 1200) \
                    AND "+table_prefix+"postmeta.meta_value >= UNIX_TIMESTAMP() - 1200 \
                    AND "+table_prefix+"postmeta.meta_value <> ''\
                    AND "+table_prefix+"posts.ID IN( SELECT post_id FROM "+table_prefix+"postmeta WHERE meta_key = 'closed' AND meta_value = 0 );";

        return this.query(query);
    },

    getPriceData: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key IN (\'start_price\', \'current_bid\', \'minimum_price\', \'bid_auction\')'].join(""))
            .order("FIELD(meta_key, 'start_price', 'current_bid', 'minimum_price','bid_auction')")
            .toString();

        return this.query(query);
    },

    getPostData: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'posts')
            .where(['ID = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    loadAuctions: function () {
        var query = "SELECT ID, meta_value FROM \
                    "+table_prefix+"posts\
                    JOIN "+table_prefix+"postmeta\
                    ON "+table_prefix+"posts.ID="+table_prefix+"postmeta.post_id\
                    WHERE "+table_prefix+"posts.post_type = 'auction'\
                    AND "+table_prefix+"postmeta.meta_key = 'ending'\
                    AND "+table_prefix+"postmeta.meta_value <> ''\
                    AND "+table_prefix+"posts.ID IN( SELECT post_id FROM "+table_prefix+"postmeta WHERE meta_key = 'closed' AND meta_value = 0 );";

        return this.query(query);
    },

    incrementCreditsCurrent: function ($id, $userid, $amount) {
        var query = squel
            .update()
            .table(table_prefix+'penny_assistant')
            .set("credits_current = credits_current + " + $amount)
            .where(['pid = ', $id, ' AND uid = ', $userid].join(""))
            .toString();

        return this.query(query);
    },
    bidsTransfer: function ($winner,$userCredits,$amount,$bankCredits) {
        
        var query = squel
                    .insert()
                    .into(table_prefix+"bids_transfers")
                    .setFields({
                        type:'BID_AUCTION_TRANSFER',
                        action:1,
                        by_uid: 10,
                        to_uid: $winner,
                        credits_before: (Number($userCredits)-Number($amount)),
                        credits_after: $userCredits,
                        bank_before: $bankCredits,
                        bank_after: (Number($bankCredits)-Number($amount)),
                        ip: '-',
                        amount: $amount,
                        date: date.format(new Date, 'MM/DD/YYYY hh:mm:ss A')            
                    }).toString();
                   
        
        //return self.query(query);
        this.query(query).then(null, function (err,success) {
            err ? logger.log('error', 'Error while inserting bid transfer: %s', err) : null;
            
        });
    },
    loadAutobids: function ($id, $isEnding) {
        console.log('load autobids database file')
        var query = squel
            .select()
            .from(table_prefix+'penny_assistant')
            .where(['pause = 0 AND pid = ', $id].join(""))
            .order('date_made', false)
            .toString();

        if ($isEnding) {
            query = squel
                .select()
                .from(table_prefix+'penny_assistant')
                .where(['pause = 0 AND pid = ', $id, ' AND credits_start > credits_current'].join(""))
                .order('date_made', false)
                .toString();
        }

        return this.query(query);
        
    },

    getAdminAutoBidMinimum: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'admin_auto_bid_target_price\''].join(""))
            .toString();

        return this.query(query);
    },

    getAdminAutoBidUsers: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            //.where(['post_id = ', $id, ' AND meta_key LIKE \'admin_auto_bid_users\''].join("")) // condition for single admin auto bid user
            .where(['post_id = ', $id, ' AND meta_key LIKE \'admin_auto_bid_users_%\''].join("")) // condition for multiple admin auto bid user
            .toString();

        return this.query(query);
    },

    isAdminAutobidAuction: function ($id) {
        console.log('pososal li admin hui')
        var deffered = q.defer();
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'enable_admin_auto_bid\''].join(""))
            .toString();

        this.query(query).then(function (data) {
            if (!data) {
                return deffered.resolve(false);
            }

            if (data[0].meta_value == 1) {
                deffered.resolve(true);
            }
            else {
                deffered.resolve(false);
            }
        });

        return deffered.promise;
    },

    isBidRewardAuction: function ($id) {
        var deffered = q.defer();
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'bid_auction\''].join(""))
            .toString();

        this.query(query).then(function (data) {
            if (!data) {
                return deffered.resolve(false);
            }

            if (data[0].meta_value == 1) {
                deffered.resolve(true);
            }
            else {
                deffered.resolve(false);
            }
        });

        return deffered.promise;
    },

    getAuctionBidReward: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'bid_auction_amount\''].join(""))
            .toString();

        return this.query(query);
    },

    setNoWinnerOnAuction: function ($id) {
        var deffered = q.defer();
        var metaWinnerQuery = squel
            .update()
            .table(table_prefix+'postmeta')
            .set("meta_value", 0)
            .where(['post_id = ', $id, ' AND meta_key = \'winner\''].join(""))
            .toString();
        
        var lastBidderQuery = squel
            .update()
            .table(table_prefix+'penny_bids')
            .set("winner", "0")
            .where(['pid = ', $id].join(""))
            .order("date_made", false).toString();

        q.allSettled([
            this.query(lastBidderQuery),            
            this.query(metaWinnerQuery)
        ]).spread(function () {
            deffered.resolve();
        });

        return deffered.promise;
    },

    getUserAutobids: function ($id, $user) {
        var query = squel
            .select()
            .from(table_prefix+'penny_assistant')
            .where(['pid = ', $id, ' AND uid = ', $user].join(""))
            .order('date_made', false)
            .toString();

        return this.query(query);
    },

    setAuctionClosed: function ($id) { // sets an auction status
        var self = this;
        var deffered = q.defer();
        var closeQuery = squel
            .update()
            .table(table_prefix+'postmeta')
            .set("meta_value", "1")
            .where(['post_id = ', $id, ' AND meta_key = \'closed\''].join(""))
            .toString();

        q.allSettled([this.query(closeQuery),
            this.getCurrentBidder($id),
            this.getCurrentBid($id)]).spread(function ($closed, $current_bidder, $current_bid) {

            var currentBidder;

            /**
             * Check if there is a last bidder on the auction
             */
            
            if ($current_bidder.value[0] && typeof $current_bidder.value[0]['uid']!=undefined) {
                currentBidder = $current_bidder.value[0]['uid']
            } else {
                currentBidder = 0;
            }

            var winnerQuery = squel.update()
                    .table(table_prefix+'postmeta')
                    .set("meta_value", currentBidder)
                    .where(['post_id = ', $id, ' AND meta_key = \'winner\''].join("")).toString(),
                setLastBidWinnerQuery = squel.update()
                    .table(table_prefix+'penny_bids')
                    .set("winner", "1")
                    .where(['pid = ', $id, ' AND uid = ', currentBidder].join(""))
                    .order("date_made", false)
                    .order("bid", false)
                    .limit(1).toString(),
                setWinnerBid = squel.update()
                    .table(table_prefix+'postmeta')
                    .set("meta_value", $current_bid.value[0].meta_value)
                    .where(['post_id = ', $id, ' AND meta_key = \'winner_bid\''].join("")).toString(),
                setDateClosedQuery = squel.update()
                    .table(table_prefix+'postmeta')
                    .set("meta_value", Math.floor(Date.now() / 1000))
                    .where(['post_id = ', $id, ' AND meta_key = \'closed_date\''].join("")).toString();

            q.allSettled([
                self.query(setDateClosedQuery),
                self.query(winnerQuery),
                self.query(setWinnerBid),
                self.query(setLastBidWinnerQuery)                
            ]).spread(function (setDateClosed, winner, setWinner, setLastBid) {
                if (setDateClosed.state === "fulfilled" &&
                    winner.state === "fulfilled" &&
                    setWinner.state === 'fulfilled' &&
                    setLastBid.state === 'fulfilled') {
                    deffered.resolve({id: $id, winner: currentBidder});
                }
                else {
                    var errors = [];

                    for (var i = 0, len = arguments.length; i < len; i++) {
                        if (arguments[i].state === 'rejected') {
                            errors.push(arguments[i].r);
                        }
                    }

                    deffered.reject(errors);
                }
            });
        });

        return deffered.promise;
    },
    getCurrentBid: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'current_bid\''].join(""))
            .toString();

        return this.query(query);
    },

    getAutobid: function ($id, $userid) {
        var query = squel
            .select()
            .from(table_prefix+'penny_assistant')
            .where(['uid = ', $userid, ' AND pid = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    pauseAutobid: function ($id, $userid) {
        var query = squel
            .update()
            .table(table_prefix+'penny_assistant')
            .set("pause = 1")
            .where(['uid = ', $userid, ' AND pid = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    resumeAutobid: function ($id, $userid) {
        var query = squel
            .update()
            .table(table_prefix+'penny_assistant')
            .set("pause = 0")
            .where(['uid = ', $userid, ' AND pid = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    setAutobid: function ($id, $userid, $amount) {
        var self = this;

        return this.getUserAutobids($id, $userid).then(function ($autobids) {
            if ($autobids.length > 0) {   // The user has already a bid for this auction so we just update it
                var query = squel
                    .update()
                    .table(table_prefix+'penny_assistant')
                    .set("credits_start", $amount)
                    .set("pause", 0)
                    .set("credits_current", 0)
                    .where(['uid = ', $userid, ' AND pid = ', $id].join(""))
                    .toString();

                return self.query(query);
            }
            else {
                var query = squel
                    .insert()
                    .into(table_prefix+"penny_assistant")
                    .setFields({
                        date_made: Math.floor(Date.now() / 1000),
                        pid: $id,
                        uid: $userid,
                        credits_current: 0,
                        pause: 0,
                        credits_start: $amount
                    }).toString();

                return self.query(query);
            }
        }, function (err) {
            err ? logger.log('error', 'Error while getting auto bid data: %s', err) : null;
        });
    },

    isAlreadyEnded: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'closed\''].join(""))
            .toString();

        return this.query(query);
    },

    isTimeEnded: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key = \'ending\''].join(""))
            .toString();

        return this.query(query);
    },

    getAuctionBids: function ($id) {
        var query = squel
            .select('meta_value')
            .from(table_prefix+'penny_bids')
            .where(['pid = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    handleRefund: function ($id, $uid, $bids, $callback) { // id of the refund
        var self = this;
        var query = squel
            .update()
            .table(table_prefix+'auctions_not_fulfilled_refunds')
            .set("status = 1")
            .where(['id = ', $id, ' AND uid = \'', $uid, '\''].join(""))
            .toString();

        this.query(query).then(function () {
            self.incrementUserCredits($uid, $bids).then(function () {
                $callback && $callback();
            });
        }, function () {
            logger.log('error', 'Something went wrong while refunding %s credits to user with id %s, refund id %s.', $bids, $uid, $id);
        });
    },

    getAuctionRefunds: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'auctions_not_fulfilled_refunds')
            .where(['pid = ', $id].join(""))
            .toString();

        return this.query(query);
    },

    insertRefundBid: function ($pid, $uid, $bids) {
        var query = squel
            .insert()
            .into(table_prefix+"auctions_not_fulfilled_refunds")
            .setFields({
                pid: $pid,
                uid: $uid,
                bids: $bids,
                status: 0,
                date: new Date().getTime()
            })
            .toString();

        return this.query(query);
    },

    insertBid: function ($id, $userid, $price) {
        var postMetaQuery = squel
            .insert()
            .into(table_prefix+"postmeta")
            .setFields({
                post_id: $id,
                meta_key: 'bidded_auction',
                meta_value: $userid
            })
            .toString();

        var pennyBidsQuery = squel
            .insert()
            .into(table_prefix+"penny_bids")
            .setFields({
                uid: $userid,
                pid: $id,
                bid: $price,
                paid: 0,
                reserved1: 0,
                winner: 0,
                date_choosen: 0,
                date_made: Math.floor(Date.now() / 1000)
            })
            .toString();

        this.query(pennyBidsQuery).then(null, function (err) {
            err ? logger.log('error', 'Error while inserting bid: %s', err) : null;
        });


        this.query(postMetaQuery).then(null, function (err) {
            err ? logger.log('error', 'Error while inserting bid: %s', err) : null;
        });
    },

    getLastBid: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'penny_bids')
            .where(['pid = ', $id].join(""))
            .order("bid", false)
            .toString();

       return this.query(query);
    },

    getCurrentBidder: function ($auction) {
        var query = squel
            .select('uid')
            .from(table_prefix+'penny_bids')
            .where(['pid = ', $auction].join(""))
            .order("bid", false)
            .limit(1)
            .toString();

        return this.query(query);        
    },

    getAuctionParticipants: function ($auction) {
        var query = squel
            .select('uid')
            .from(table_prefix+'penny_bids')
            .where(['pid = ', $auction].join(""))
            .toString();
        return this.query(query);              
       
    },

    // getAuctionData: function($id) {
    //     var query = squel
    //                 .select()
    //                 .from(table_prefix+'postmeta')
    //                 .where(['post_id = ', $id, ' AND meta_key IN (\'price_increase\', \'ending\', \'start_price\', \'current_bid\', \'time_increase\')'].join(""))
    //                 .order("FIELD(meta_key, 'price_increase', 'ending', 'start_price', 'current_bid', 'time_increase')")
    //                 .toString();
    //
    //     return this.query(query).then(null, function(err) {
    //         err ? logger.log('error', 'Error while get auction data: %s for auction with id: %s', err, $id) : null;
    //     });
    // },

    /*Fixing autobid 6 nov start*/
    getAuctionData: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'postmeta')
            .where(['post_id = ', $id, ' AND meta_key IN (\'price_increase\', \'ending\', \'start_price\', \'current_bid\', \'time_increase\', \'minimum_price\')'].join(""))
            .order("FIELD(meta_key, 'price_increase', 'ending', 'start_price', 'current_bid', 'time_increase','minimum_price')")
            .toString();

        return this.query(query).then(null, function (err) {
            err ? logger.log('error', 'Error while get auction data: %s for auction with id: %s', err, $id) : null;
        });
    },
    /*Fixing autobid 6 nov end*/

    updateAuctionData: function ($id, $price, $time) {
        var updatePriceQuery = squel
            .update()
            .table(table_prefix+'postmeta')
            .set("meta_value =  CAST(meta_value as DECIMAL(10,2)) + " + $price)
            .where(['post_id = ', $id, ' AND meta_key = \'current_bid\''].join(""))
            .toString();

        this.query(updatePriceQuery).then(null, function (err) {
            err ? logger.log('error', 'Error while updating current_bid: %s', err) : null;
        });

        if ($time) {   // Time left is less or equal to 15 seconds
            // UPDATE '+table_prefix+'postmeta SET meta_value = UNIX_TIMESTAMP() + 10 WHERE post_id = 426 AND meta_key = 'ending';
            var updateTimeQuery = squel
                .update()
                .table(table_prefix+'postmeta')
                .set("meta_value", Math.floor(Date.now() / 1000) + 15)
                .where(['post_id = ', $id, ' AND meta_key = \'ending\''].join(""))
                .toString();

            this.query(updateTimeQuery).then(null, function (err) {
                err ? logger.log('error', 'Error while updating time left: %s', err) : null;
            });
        }
    },

    decrementUserCredits: function ($id, $amount) {
        var query = squel
            .update()
            .table(table_prefix+'usermeta')
            .set("meta_value = meta_value - " + $amount)
            .where(['user_id = ', $id, ' AND meta_key = \'user_credits\''].join(""))
            .toString();

        return this.query(query);
    },

    incrementUserCredits: function ($id, $amount) {
        var query = squel
            .update()
            .table(table_prefix+'usermeta')
            .set("meta_value = meta_value + " + $amount)
            .where(['user_id = ', $id, ' AND meta_key = \'user_credits\''].join(""))
            .toString();

        return this.query(query);
    },

    getUser: function ($id) { // gets an user data
        
        var query = squel
            .select()
            .from(table_prefix+'usermeta')
            .where(['user_id = ', $id, ' AND meta_key IN (\'nickname\', \'user_credits\', \'_country_\', \'wp_user_avatar\')'].join(""))
            .order("FIELD(meta_key, 'nickname', 'user_credits', '_country_', 'wp_user_avatar')")
            .toString();
        return this.query(query);
    },

    // getAvatar: function ($id) {
    //     var query = squel
    //         .select()
    //         .from(table_prefix+'postmeta')
    //         .where(['post_id =', $id, ' AND meta_key = \'_wp_attached_file\''].join(""))
    //         .order("FIELD(meta_key, '_wp_attached_file')")
    //         .toString();

    //     return this.query(query);
    // }
        getAvatar: function ($id) {
        var query = squel
            .select()
            .from(table_prefix+'usermeta')
            .where(['user_id =', $id, ` AND meta_key = \'${table_prefix}user_avatar\'`].join(""))
            // .order("FIELD(meta_key, '_wp_attached_file')")
            .toString();

        return this.query(query);
    },
    // getAvatar: function ($id) {
    //     console.log($id, 'zaeblo');
    //     var query = squel
    //         .select()
    //         .from(table_prefix+'posts')
    //         .where(
    //              ['post_author =', $id, ' AND post_type = \'attachment\''].join("")
    //             //squel.expr().and(`post_author = ${$id}`).and("post_type = 'attachment'")//.join("")
    //             )
    //         .order("ID")
    //         .toString();
    //     return this.query(query);
    // }

    // getUserMaxBiddingAmount: function ($id) {
    //     var query = squel
    //         .select()
    //         .from(table_prefix+'penny_assistant')
    //         .where(['uid =', $id].join(""))
    //         .order("date_made", false)
    //         .toString();

    //     return this.query(query);
    // },

    // getUserBids: function ($id) {
    //     var query = squel
    //         .select()
    //         .from(table_prefix+'usermeta')
    //         .where(['user_id =', $id, ` AND meta_key = \'user_credits\'`].join(""))
    //         //.order("date_made")
    //         .toString();

    //     return this.query(query);
    // },

    getTodaysWinner: function ($id) {
        var today = new Date
        var dd = today.getDate()
        var ddd = today.getDate()+1
        var mm = today.getMonth()+1
        var yy = today.getFullYear()
        
        console.log(today.getDay(), 'den nedeli blyat')

        today = `${mm}/${dd}/${yy}`
        tomorrow = `${mm}/${ddd}/${yy}`

        poebat = (new Date(today).getTime()/1000)
        poebat2 = (new Date(tomorrow).getTime()/1000)

        var query = squel
            .select()
            .from(table_prefix+'penny_bids')
            .where([`uid =${$id} AND date_made > ${poebat} AND date_made < ${poebat2} AND winner = 1`].join(""))
            .toString();

        return this.query(query);
    },

    getWeekWinner: function ($id) {
        var today = new Date
        var mm = today.getMonth()+1
        var yy = today.getFullYear()
        
        switch(today.getDay()) {
            case 0:
                var weekStart = today.getDate()
                var weekEnd = today.getDate()+6
                break
            case 1:
                var weekStart = today.getDate()-1
                var weekEnd = today.getDate()+5
                break
            case 2:
                var weekStart = today.getDate()-2
                var weekEnd = today.getDate()+4
                break
            case 3:
                var weekStart = today.getDate()-3
                var weekEnd = today.getDate()+3
                break
            case 4:
                var weekStart = today.getDate()-4
                var weekEnd = today.getDate()+2
                break
            case 5:
                var weekStart = today.getDate()-5
                var weekEnd = today.getDate()+1
                break
            case 6:
                var weekStart = today.getDate()-6
                var weekEnd = today.getDate()
                break
            default:
                break
        }

        today = `${mm}/${weekStart}/${yy}`
        tomorrow = `${mm}/${weekEnd}/${yy}`

        poebat = (new Date(today).getTime()/1000)
        poebat2 = (new Date(tomorrow).getTime()/1000)

        var query = squel
            .select()
            .from(table_prefix+'penny_bids')
            .where([`uid =${$id} AND date_made > ${poebat} AND date_made < ${poebat2} AND winner = 1`].join(""))
            .toString();

        return this.query(query);
    },

    isBlocked: function ($id) {
        var today = new Date
        var dd = today.getDate()
        var mm = today.getMonth()+1
        var yy = today.getFullYear()

        today = `${mm}/${dd}/${yy}`

        poebat = (new Date(today).getTime()/1000)
        poebat2 = (new Date(tomorrow).getTime()/1000)

        var query = squel
            .select()
            .from(table_prefix+'_usermeta')
            .where([`uid =${$id} AND date_made > ${poebat} AND _user_status = 'blocked_daily' OR _user_status = 'blocked_weekly`].join(""))
            .toString();

        return this.query(query);
    },
};