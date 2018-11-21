/* ================ MODULES LOADING ========================*/
var auctions       = require("./auctions.js"),
    cluster        = require("./cluster.js"),
    tasks          = require("../tasks/index"),
    $master        = require("../entities/master.js"),
    $worker        = require("../entities/worker.js"),
    getSession     = require("../actions/auth/get.session.js"),
    storeUser      = require("../actions/auth/store.user.js"),
    emit           = require("../actions/socket/emit.js"),
    emitGlobal     = require("../actions/socket/emit.global.js"),
    removeUser     = require("../actions/auth/remove.user.js"),
    emitUser       = require("../actions/socket/emit.user"),
    emitUserGlobal = require("../actions/socket/emit.user.global.js"),
    isLogged       = require("../actions/auth/is.logged.js");

/* ===================== METHODS ===========================*/
module.exports = {
    storage: {
        users: {}
    },

    init: function() {
        var master = $master.bind(this);
        var worker = $worker.bind(this);
        cluster.init(master, worker);
    },

    boot: function() {
        /**
         * Run the on boot tasks
         */

        tasks.boot();
    },

    addEventListeners: function($socket) { // sets the events and emitters
        var self = this;

        getSession($socket).then(function($session) {
            var auctionId = $socket.handshake.query.auctionId;
            var room      =  ( auctionId === "global" ) ? "GLOBAL" : "AUCTION_" + auctionId;

            $socket.join(room);
            $socket.room = auctionId;

            $socket.on('latency', function (startTime, cb) {
                cb(startTime);
            });

            if ( $session.uid )
            {
                $socket.userid = $session.uid;
                self.storeUser($socket);

                $socket.on('SET_AUTOBID', function($data) {
                    isLogged($socket).then(function() {
                        logger.log("info",
                            "[%s]: User with id %s is trying to set an auto bid on auction with id %s.",
                            INSTANCE,
                            $socket.userid,
                            $data.id );
                        /* code changes for _penny_assistant table*/
                        auctions.setAutobid($data.id, $socket.userid, $data.amount);
                    }, function($error) {
                        $socket.emit("USER_NOT_LOGGED", $error);
                    });
                });

                $socket.on('PAUSE_AUTOBID',  function($data) {
                    isLogged($socket).then(function() {
                        /* code changes for _penny_assistant table */
                        auctions.pauseAutobid($data.id, $socket.userid);
                    }, function($error) {
                        $socket.emit("USER_NOT_LOGGED", $error);
                    });
                });

                $socket.on('RESUME_AUTOBID', function($data) {
                    isLogged($socket).then(function() {
                        /* code changes for _penny_assistant table */
                        auctions.resumeAutobid($data.id, $socket.userid);
                    }, function($error) {
                        $socket.emit("USER_NOT_LOGGED", $error);
                    });
                });

                $socket.on('NEW_BID', self.onBid.bind($socket));
                $socket.on('disconnect', function() { self.removeUser($socket); });
                $socket.on('SEND_ADMIN_NOTIFICATION', function($data) { emitGlobal("SEND_ADMIN_NOTI", $data); });
            }
        })
        .catch(function($error) {
            if ( $error )
            {
                logger.log("info",
                    "[%s]: Error encountered while getting the session, %s.",
                    INSTANCE,
                    $error );
            }

            $socket.disconnect();
        });
    },

    onBid: function($data) {
        var $socket = this;

        isLogged($socket).then(function() {
            logger.log('info',
                '[%s]: User with id %s has sent a NEW_BID request on auction %s.',
                INSTANCE,
                $socket.userid,
                $data.id );

            auctions.bid($data.id, $socket.userid);
        })
        .catch(function($error) {
            $socket.emit("USER_NOT_LOGGED", $error);
        });
    },

    emit:           emit,
    storeUser:      storeUser,
    emitUser:       emitUser,
    emitUserGlobal: emitUserGlobal,
    emitGlobal:     emitGlobal,
    removeUser:     removeUser
};
