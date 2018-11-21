var auctions = require("../core/auctions.js");

/**
 * Socket worker code
 * @param $port
 */
module.exports = function($port) {
    var SERVER = this,
        http   = require("http"),
        server = http.createServer(),
        events = require("../core/events.js"),
        io     = require("socket.io")(server, { pingInterval: 2000, pingTimeout: 5000 });

    /**
     * Bind the auctions to the worker
     * so it can access the bid method
     */

    auctions.bind(this);

    /**
     * A new socket connection to the worker socket.io server has been
     * made, we are assigning the port (instance) of the socket for SERVER.emitUser
     * method
     *
     * note: Each worker has its own instance to SERVER object.
     */
    io.on("connection", function($socket) {
        logger.log('info',
            '[%s]: A socket has connected.',
            INSTANCE );

        $socket.port = $port;
        SERVER.addEventListeners($socket);
    });

    /**
     * Triggered by each auction instance once an user bids.
     */
    events.on("GLOBAL_EMIT", function($data) {
        if ( $data.hasOwnProperty("room") )
        {
            io.in($data.room).emit($data.name, $data.data);
        }
        else
        {
            io.emit($data.name, $data.data);
        }
    });

    /**
     * Master instance is sending the message to emit the MASTER_USER_EMIT, and triggers
     * the emitUser which checks for the users on the current instance and if found,
     * sends the data to the user.
     */
    events.on("MASTER_USER_EMIT", function($data) {
        SERVER.emitUser($data.user, $data.name, $data.data, $data.room);
    });

    events.on("MASTER_GLOBAL_EMIT", function($data) {
        SERVER.emit($data.name, $data.data, $data.room);
    });

    /**
     * Emit only on the current socket instance using $port identifier.
     */
    events.on($port + "_EMIT", function($data) {
        if ( $data.hasOwnProperty("room") && !$data.hasOwnProperty("socket") )
        {
            io.in($data.room).emit($data.name, $data.data);
        }
        else if ( $data.hasOwnProperty("room")
                  && $data.hasOwnProperty("socket")
                  && $data.socket.hasOwnProperty("rooms")
                  && $data.socket.rooms.hasOwnProperty($data.room) )
        {
            $data.socket.emit($data.name, $data.data);
        }
        else if ( !$data.hasOwnProperty("room") && $data.hasOwnProperty("socket")  )
        {
            $data.socket.emit($data.name, $data.data);
        }
        else
        {
            io.emit($data.name, $data.data);
        }
    });

    /**
     * Instance port (config.APP.START_PORT + current assigned core )
     */

    server.listen($port);

    /**
     * Name the current worker instance
     */

    global.INSTANCE = $port;
};