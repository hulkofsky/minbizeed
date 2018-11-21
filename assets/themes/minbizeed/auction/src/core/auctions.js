/* ================ MODULES LOADING ========================*/
var bid           = require('../actions/auctions/bid'),
    timers        = require('../actions/auctions/timers'),
    refund        = require('../actions/auctions/refund'),
    setAutobid    = require('../actions/auctions/set.autobid'),
    pauseAutobid  = require('../actions/auctions/pause.autobid'),
    resumeAutobid = require('../actions/auctions/resume.autobid'),
    autobid       = require("../actions/auctions/autobid"),
    close         = require('../actions/auctions/close');


/* ===================== METHODS ===========================*/
module.exports = {
    /**
     * Called by master instance
     * @param $sockets
     */
    init: function ($sockets) {
        this.sockets  = $sockets;
        this.isMaster = true;
        this.handleAuctions();
    },

    /**
     * Bind by workers
     * @param $sockets
     */
    bind: function($sockets) {
        this.sockets  = $sockets;
        this.isMaster = false;
    },

    setAutobid:         setAutobid,
    pauseAutobid:       pauseAutobid,
    resumeAutobid:      resumeAutobid,
    handleAuctions:     timers,
    refundBids:         refund,
    handleAuctionClose: close,
    bid:                bid,
    autobid:            autobid
};