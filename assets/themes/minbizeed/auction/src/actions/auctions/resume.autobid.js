var db = require("../../core/database.js");

module.exports = function ($id, $userid) {
    var self  = this;
    var error = false;

    console.log('start avtobid ebat ego v anal')
    if ( isNaN($id) )
    {
        logger.info('warn',
            '[%s]: User with id %s tried to resume auto bid with %s as auction id.',
            INSTANCE,
            $userid,
            $id );

        return;
    }

    db.isAlreadyEnded($id).then(function ($ended) {
        if ($ended[0].meta_value == 1)
        {
            error = 'The auction has already ended.';

            return self.sockets.emitUserGlobal($userid, 'AUTO_BID_ERROR', {
                id: $id,
                message: error
            }, ['AUCTION_' + $id], self.isMaster);
        }

        db.resumeAutobid($id, $userid).then(function () {
            /* code changes for _penny_assistant table */
            logger.log("info",
                "[%s]: User with id %s has resumed the auto bid on auction with id %s.",
                INSTANCE,
                $userid,
                $id );

            self.sockets.emitUserGlobal($userid, 'AUTO_BID_RESUMED', {
                id: $id,
                userid:$userid
            }, ['AUCTION_' + $id], self.isMaster);
        });
    });
};