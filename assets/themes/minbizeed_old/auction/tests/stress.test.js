/**
 * @description Module containing database methods
 */

/* ================ MODULES LOADING ========================*/
var cluster = require('cluster');
var config = {
    HOST: '127.0.0.1',
    USER: 'tb_test',
    PASSWORD: 'Tods85FzYMfhUVJM',
    DATABASE: 'tb_test',
    PORT: '/var/run/mysqld/mysqld.sock'
};

var db = require('mysql'),
    logger = require('winston'),
    q = require('q'),
    squel = require('squel'),
    pool = db.createPool({
        connectionLimit: 100,
        host: config.HOST,
        user: config.USER,
        password: config.PASSWORD,
        database: config.DATABASE,
        socketPath: config.PORT
    });

/* ===================== METHODS ============================*/
var DB = {
    countDone: 0,
    query: function ($query) { // query the database
        var deferred = q.defer();
        //pool.getConnection(function(err, connection) {
        pool.query($query, function (err, rows) {
            if (err) {
                console.log(err);
                pool.query({sql: $query, timeout: 30000}, function (err, rows) {

//connection.release();
                    if (err) {
                        deferred.reject(err);
                    }
                    else {
                        DB.countDone += 1;
                        deferred.resolve(rows);
                    }
                });
            }
            else {
                DB.countDone += 1;
                deferred.resolve(rows);
//connection.release();
            }
        });
//});

        return deferred.promise;
    },

    loadSoonToCloseAuctions: function () {
        var query = "SELECT ID, meta_value FROM \
                    tb_5550121_posts\
                    JOIN tb_5550121_postmeta\
                    ON tb_5550121_posts.ID=tb_5550121_postmeta.post_id\
                    WHERE tb_5550121_posts.post_type = 'auction'\
                    AND tb_5550121_postmeta.meta_key = 'ending'\
                    AND tb_5550121_postmeta.meta_value <= (UNIX_TIMESTAMP() + 1200) \
                    AND tb_5550121_postmeta.meta_value >= UNIX_TIMESTAMP() \
                    AND tb_5550121_postmeta.meta_value <> ''\
                    AND tb_5550121_posts.ID IN( SELECT post_id FROM tb_5550121_postmeta WHERE meta_key = 'closed' AND meta_value = 0 );";

        return DB.query(query);
    },

    getPriceData: function ($id) {
        var query = squel.select().from('tb_5550121_postmeta').where(['post_id = ', $id, ' AND meta_key IN (\'start_price\', \'current_bid\', \'minimum_price\')'].join("")).order("FIELD(meta_key, 'start_price', 'current_bid', 'minimum_price')").toString();
        return DB.query(query);
    },

    loadAuctions: function () {
        var query = "SELECT ID, meta_value FROM \
                    tb_5550121_posts\
                    JOIN tb_5550121_postmeta\
                    ON tb_5550121_posts.ID=tb_5550121_postmeta.post_id\
                    WHERE tb_5550121_posts.post_type = 'auction'\
                    AND tb_5550121_postmeta.meta_key = 'ending'\
                    AND tb_5550121_postmeta.meta_value <> ''\
                    AND tb_5550121_posts.ID IN( SELECT post_id FROM tb_5550121_postmeta WHERE meta_key = 'closed' AND meta_value = 0 );";

        return DB.query(query);
    },

    incrementCreditsCurrent: function ($id, $userid, $amount) {
        var query = squel.update().table('tb_5550121_penny_assistant').set("credits_current = credits_current + " + $amount).where(['pid = ', $id, ' AND uid = ', $userid].join("")).toString();
        return DB.query(query);
    },

    loadAutobids: function ($id, $isEnding) {
        var query = squel.select().from('tb_5550121_penny_assistant').where(['pause = 0 AND pid = ', $id].join("")).order('date_made', false).toString();

        if ($isEnding) {
            query = squel.select().from('tb_5550121_penny_assistant').where(['pause = 0 AND pid = ', $id, ' AND credits_start > credits_current'].join("")).order('date_made', false).toString()
        }

        return DB.query(query);
    },

    setNoWinnerOnAuction: function ($id) {
        var deffered = q.defer();
        var metaWinnerQuery = squel.update().table('tb_5550121_postmeta').set("meta_value", 0).where(['post_id = ', $id, ' AND meta_key = \'winner\''].join("")).toString();
        var lastBidderQuery = squel.update()
            .table('tb_5550121_penny_bids')
            .set("winner", "0")
            .where(['pid = ', $id].join(""))
            .order("date_made", false).toString();

        q.allSettled([DB.query(lastBidderQuery), DB.query(metaWinnerQuery)]).spread(function () {
            deffered.resolve();
        });

        return deffered.promise;
    },

    getUserAutobids: function ($id, $user) {
        var query = squel.select().from('tb_5550121_penny_assistant').where(['pid = ', $id, ' AND uid = ', $user].join("")).order('date_made', false).toString();
        return DB.query(query);
    },

    setAuctionClosed: function ($id) { // sets an auction status
        var deffered = q.defer();
        var closeQuery = squel.update().table('tb_5550121_postmeta').set("meta_value", "1").where(['post_id = ', $id, ' AND meta_key = \'closed\''].join("")).toString();

        q.allSettled([DB.query(closeQuery),
            DB.getCurrentBidder($id),
            DB.getCurrentBid($id)]).spread(function ($closed, $current_bidder, $current_bid) {

            var currentBidder;

            /**
             * Check if there is a last bidder on the auction
             */
            if ($current_bidder.value[0] && $current_bidder.value[0].hasOwnProperty('uid')) {
                currentBidder = $current_bidder.value[0].uid
            } else {
                currentBidder = 0;
            }

            var winnerQuery = squel.update()
                    .table('tb_5550121_postmeta')
                    .set("meta_value", currentBidder)
                    .where(['post_id = ', $id, ' AND meta_key = \'winner\''].join("")).toString(),
                setLastBidWinnerQuery = squel.update()
                    .table('tb_5550121_penny_bids')
                    .set("winner", "1")
                    .where(['pid = ', $id, ' AND uid = ', currentBidder].join(""))
                    .order("date_made", false)
                    .order("bid", false)
                    .limit(1).toString(),
                setWinnerBid = squel.update()
                    .table('tb_5550121_postmeta')
                    .set("meta_value", $current_bid.value[0].meta_value)
                    .where(['post_id = ', $id, ' AND meta_key = \'winner_bid\''].join("")).toString(),
                setDateClosedQuery = squel.update()
                    .table('tb_5550121_postmeta')
                    .set("meta_value", Math.floor(Date.now() / 1000))
                    .where(['post_id = ', $id, ' AND meta_key = \'closed_date\''].join("")).toString();

            q.allSettled([
                DB.query(setDateClosedQuery),
                DB.query(winnerQuery),
                DB.query(setWinnerBid),
                DB.query(setLastBidWinnerQuery)
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
        var query = squel.select('meta_value').from('tb_5550121_postmeta').where(['post_id = ', $id, ' AND meta_key = \'current_bid\''].join("")).toString();
        return DB.query(query);
    },

    pauseAutobid: function ($id, $userid) {
        var query = squel.update().table('tb_5550121_penny_assistant').set("pause = 1").where(['uid = ', $userid, ' AND pid = ', $id].join("")).toString();
        return DB.query(query);
    },

    resumeAutobid: function ($id, $userid) {
        var query = squel.update().table('tb_5550121_penny_assistant').set("pause = 0").where(['uid = ', $userid, ' AND pid = ', $id].join("")).toString();
        return DB.query(query);
    },

    setAutobid: function ($id, $userid, $amount) {
        DB.getUserAutobids($id, $userid).then(function ($autobids) {
            if ($autobids.length > 0) {   // The user has already a bid for this auction so we just update it
                var query = squel.update().table('tb_5550121_penny_assistant').set("credits_start", $amount).set("credits_current", 0).where(['uid = ', $userid, ' AND pid = ', $id].join("")).toString();
                return DB.query(query);
            }
            else {
                var query = squel.insert().into("tb_5550121_penny_assistant").setFields({
                    date_made: Math.floor(Date.now() / 1000),
                    pid: $id,
                    uid: $userid,
                    credits_current: 0,
                    credits_start: $amount
                }).toString();

                return DB.query(query);
            }
        }, function (err) {
            err ? logger.log('info', 'Error while getting auto bid data: %s', err) : null;
        });
    },

    isAlreadyEnded: function ($id) {
        var query = squel.select('meta_value').from('tb_5550121_postmeta').where(['post_id = ', $id, ' AND meta_key = \'closed\''].join("")).toString();
        return DB.query(query);
    },

    getAuctionBids: function ($id) {
        var query = squel.select('meta_value').from('tb_5550121_penny_bids').where(['pid = ', $id].join("")).toString();
        return DB.query(query);
    },

    handleRefund: function ($id, $uid, $bids, $callback) { // id of the refund
        var self = this;
        var query = squel.update()
            .table('tb_5550121_auctions_not_fulfilled_refunds')
            .set("status = 1").where(['id = ', $id, ' AND uid = \'', $uid, '\''].join("")).toString();

        DB.query(query).then(function () {
            self.incrementUserCredits($uid, $bids).then(function () {
                $callback && $callback();
            });
        }, function () {
            logger.log('info', 'Something went wrong while refunding %s credits to user with id %s, refund id %s.', $bids, $uid, $id);
        });
    },

    getAuctionRefunds: function ($id) {
        var query = squel.select().from('tb_5550121_auctions_not_fulfilled_refunds').where(['pid = ', $id].join("")).toString();
        return DB.query(query);
    },

    insertRefundBid: function ($pid, $uid, $bids) {
        var query = squel.insert().into("tb_5550121_auctions_not_fulfilled_refunds").setFields({
            pid: $pid,
            uid: $uid,
            bids: $bids,
            status: 0,
            date: new Date().getTime()
        }).toString();

        return DB.query(query);
    },

    insertBid: function ($id, $userid, $price) {
        var postMetaQuery = squel.insert().into("tb_5550121_postmeta").setFields({
            post_id: $id,
            meta_key: 'bidded_auction',
            meta_value: $userid
        }).toString();

        var pennyBidsQuery = squel.insert().into("tb_5550121_penny_bids").setFields({
            uid: $userid,
            pid: $id,
            bid: $price,
            paid: 0,
            reserved1: 0,
            winner: 0,
            date_choosen: 0,
            date_made: Math.floor(Date.now() / 1000)
        }).toString();

        DB.query(pennyBidsQuery).then(null, function (err) {
            err ? logger.log('info', 'Error while inserting bid: %s', err) : null;
        });

        DB.query(postMetaQuery).then(null, function (err) {
            err ? logger.log('info', 'Error while inserting bid: %s', err) : null;
        });
    },

    getLastBid: function ($id) {
        var query = squel.select().from('tb_5550121_penny_bids').where(['pid = ', $id].join("")).order("bid", false).toString();
        return DB.query(query);
    },

    getCurrentBidder: function ($auction) {
        var query = squel.select('uid').from('tb_5550121_penny_bids').where(['pid = ', $auction].join("")).order("bid", false).limit(1).toString();
        return DB.query(query);
    },

    getAuctionParticipants: function ($auction) {
        var query = squel.select('uid').from('tb_5550121_penny_bids').where(['pid = ', $auction].join("")).toString();
        return DB.query(query);
    },

    getAuctionData: function ($id) {
        var query = squel.select().from('tb_5550121_postmeta').where(['post_id = ', $id, ' AND meta_key IN (\'price_increase\', \'ending\', \'start_price\', \'current_bid\', \'time_increase\')'].join("")).order("FIELD(meta_key, 'price_increase', 'ending', 'start_price', 'current_bid', 'time_increase')").toString();
        return DB.query(query).then(null, function (err) {
            err ? logger.log('error', 'Error while get auction data: %s for auction with id: %s', err, $id) : null;
        });
    },

    updateAuctionData: function ($id, $price, $time) {
        var updatePriceQuery = squel.update().table('tb_5550121_postmeta').set("meta_value = meta_value + " + $price).where(['post_id = ', $id, ' AND meta_key = \'current_bid\''].join("")).toString();

        DB.query(updatePriceQuery).then(null, function (err) {
            err ? logger.log('error', 'Error while updating current_bid: %s', err) : null;
        });

        if ($time) { // Time left is less or equal to 10 seconds
            var updateTimeQuery = squel.update().table('tb_5550121_postmeta').set("meta_value", Math.floor(Date.now() / 1000) + 15).where(['post_id = ', $id, ' AND meta_key = \'ending\''].join("")).toString();
            DB.query(updateTimeQuery).then(null, function (err) {
                err ? logger.log('error', 'Error while updating time left: %s', err) : null;
            });
        }
    },

    decrementUserCredits: function ($id, $amount) {
        var query = squel.update().table('tb_5550121_usermeta').set("meta_value = meta_value - " + $amount).where(['user_id = ', $id, ' AND meta_key = \'user_credits\''].join("")).toString();
        return DB.query(query);
    },

    incrementUserCredits: function ($id, $amount) {
        var query = squel.update().table('tb_5550121_usermeta').set("meta_value = meta_value + " + $amount).where(['user_id = ', $id, ' AND meta_key = \'user_credits\''].join("")).toString();
        return DB.query(query);
    },

    getUser: function ($id) { // gets an user data
        var query = squel.select().from('tb_5550121_usermeta').where(['user_id = ', $id, ' AND meta_key IN (\'nickname\', \'user_credits\', \'_country_\', \'wp_user_avatar\')'].join("")).order("FIELD(meta_key, 'nickname', 'user_credits', '_country_', 'wp_user_avatar')").toString();
        return DB.query(query);
    },

    getAvatar: function ($id) {
        var query = squel.select().from('tb_5550121_postmeta').where(['post_id = ', $id, ' AND meta_key = \'_wp_attached_file\''].join("")).order("FIELD(meta_key, '_wp_attached_file')").toString();
        return DB.query(query);
    }
};


function Test() {
    if (cluster.isMaster) {
        console.log('Spawning 40 threads');
        for (var i = 0; i < 40; i++) {
            cluster.fork();
        }
    }
    else {
        var i = 0;
        //while(true) {
        i++;
        var testInterval = setInterval(function () {
            DB.loadSoonToCloseAuctions();
        }, 1);

        /*        var nestInterval = setInterval(function() {
         DB.loadSoonToCloseAuctions();
         }, 2);*/

        /*        var restInterval = setInterval(function() {
         DB.loadSoonToCloseAuctions();
         }, 3);*/


        setInterval(function () {
            console.log(DB.countDone);
        }, 1000);

        //}
    }
}

Test();
