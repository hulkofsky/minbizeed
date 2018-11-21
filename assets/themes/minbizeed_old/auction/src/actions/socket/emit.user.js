var events   = require("../../core/events.js"),
    process  = require("process");

module.exports = function($user, $name, $data, $room) { // Emit only to workers that the user is part of
    var SERVER = this;

    if ( typeof SERVER.storage.users[$user] !== 'undefined' && SERVER.storage.users[$user].hasOwnProperty('sockets') )
    {
        for(var i = 0, len = SERVER.storage.users[$user].sockets.length; i < len; i++)
        {
            var $socket = SERVER.storage.users[$user].sockets[i];

            if ( $socket.hasOwnProperty("port") )
            {
                var $payload;

                if ( $room )
                {
                    if ( $room instanceof Array )
                    {
                        for(var l = 0, len1 = $room.length; l < len1; l++)
                        {
                            $payload = {
                                name   : $name,
                                socket : $socket,
                                room   : $room[l],
                                data   : $data
                            };

                            events.emit($socket.port + "_EMIT", $payload);
                        }
                    }
                    else
                    {
                        $payload = {
                            name   : $name,
                            socket : $socket,
                            room   : $room,
                            data   : $data
                        };

                        events.emit($socket.port + "_EMIT", $payload);
                    }
                }
                else
                {
                    $payload = {
                        name   : $name,
                        socket : $socket,
                        data   : $data
                    };

                    events.emit($socket.port + "_EMIT", $payload);
                }
            }
        }
    }
};