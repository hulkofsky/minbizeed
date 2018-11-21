/* ================ MODULES LOADING ========================*/
var winston = require('winston');
var toYAML  = require('winston-console-formatter');
var logger  = new winston.Logger({
    level: 'silly'
});

/* ==================== CONFIG =============================*/
logger.add(winston.transports.File, {
    filename: [config.APP.LOGS.PATH, '/app.log'].join(""),
    maxsize: config.APP.LOGS.MAX_SIZE,
    zippedArchive: true
});

logger.add(winston.transports.Console, toYAML.config());

module.exports = logger;