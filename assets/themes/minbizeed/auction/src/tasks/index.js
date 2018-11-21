/**
 * Handles the tasks
 */

module.exports = {
    /**
     * The application startup tasks
     */
    boot: function() {
        var promises = [];
        var tasks    = [
            './boot/clear.bids.queue.js',
            './boot/clear.bids.in.progress.js'
        ];

        logger.log('silly', '[%s]: Doing startup tasks.', INSTANCE);

        for(var i = 0, len = tasks.length; i < len; i++)
            promises.push(require(tasks[i])());

        Promise.all(promises).then(function() {
            logger.log('info', '[%s]: Finished doing startup tasks.', INSTANCE);
        });
    }
};