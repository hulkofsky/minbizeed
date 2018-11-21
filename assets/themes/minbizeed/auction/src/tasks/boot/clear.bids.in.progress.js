var redisCl = require("../../core/redis");

module.exports = function() {
    return new Promise(function(resolve, reject) {
        redisCl.keys("AUCTION_BIDS_IN_PROGRESS_*", function(err, rows) {
            for(var i = 0, j = rows.length; i < j; ++i)
            {
                redisCl.del(rows[i]);
            }

            if ( rows.length > 0 )
            {
                logger.log("debug",
                    "[TASKS]: Deleted %s AUCTION_BIDS_IN_PROGRESS rows from redis as the server had restarted.",
                    rows.length );
            }
        });

        resolve();
    });
};