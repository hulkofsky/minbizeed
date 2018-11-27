/* ===================== INIT ==============================*/
process.env.ENV = 'prod';

process.on('uncaughtException', function(e) {
    console.error('Unhandled exception:', e.stack);
});

/* ================ GLOBAL VARIABLES ========================*/
global.config = require("./config/" + process.env.ENV + ".json");
global.logger = require("./src/core/logger.js");

/* ================ MODULES LOADING ========================*/
var server    = require("./src/core/server.js");

const socket = require("socket.io")(server)

// setTimeout(() => {
//     console.log('eshelme beshelme')
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });
//     socket.emit('AUCTION_WON', { message: 'Pishov na hui!' });

// }, 1000);

/* ================== CONSTRUCTOR ==========================*/
server.init();
