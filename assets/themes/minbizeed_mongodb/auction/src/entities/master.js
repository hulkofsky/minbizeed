var auctions = require("../core/auctions.js"),
    events   = require("../core/events.js");

module.exports = function($workers) {
    var SERVER = this;

    /**
     * Only the master instance is dealing with timers checks and repetitive
     * auction actions.
     *
     * Workers have access to auction instance binding the SERVER object using
     * auction.bind();
     */

    auctions.init(SERVER);

    /**
     * Sent by SERVER.emitUserGlobal
     */

    events.on("MASTER_USER_EMIT", function($data) {
        for(var $port in $workers) {
            /**
             * Note that we use setImmediate
             * not to block node's cluster
             * IPC system
             */

            (function($port) {
                setImmediate(function() {
                    $workers[$port].send({
                        cmd: 'emit',
                        data: {
                            type: "MASTER_USER_EMIT",
                            data: $data
                        }
                    });
                });
            })($port);
        }
    });

    /**
     * Sent by SERVER.emitGlobal
     */

    events.on("MASTER_GLOBAL_EMIT", function($data) {
        for(var $port in $workers) {
            /**
             * Note that we use setImmediate
             * not to block node's cluster
             * IPC system
             */

            (function($port) {
                setImmediate(function() {
                    $workers[$port].send({
                        cmd: 'emit',
                        data: {
                            type: "MASTER_GLOBAL_EMIT",
                            data: $data
                        }
                    });
                });
            })($port);
        }
    });

    logger.log("debug", "[✓] Server initialized.");
    global.INSTANCE = "MASTER";
    SERVER.boot();
};