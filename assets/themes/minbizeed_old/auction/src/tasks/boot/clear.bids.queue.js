var redisCl = require("../../core/redis");

module.exports = function() {
    return new Promise(function(resolve, reject) {
        redisCl.keys("TOTAL_CREDITS_FOR_BIDS_IN_QUEUE_*", function(err, rows) {
            for(var i = 0, j = rows.length; i < j; ++i)
            {
                redisCl.del(rows[i]);
            }

            if ( rows.length > 0 )
            {
                logger.log("debug",
                    "[TASKS]: Deleted %s TOTAL_CREDITS_FOR_BIDS_IN_QUEUE rows from redis as the server had restarted.",
                    rows.length );
            }

            resolve();
        });
    });
};