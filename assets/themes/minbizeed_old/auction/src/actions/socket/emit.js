var events   = require("../../core/events.js"),
    process  = require("process");

module.exports = function($name, $data, $room) { // Emit events to cluster workers
    if ( $room && !$room instanceof Array )
    {
        events.emit("GLOBAL_EMIT", {
            name: $name,
            room: $room,
            data: $data
        });
    }

    if ( $room && $room instanceof Array )
    {
        for (var i = 0, len = $room.length; i < len; i++)
        {
            events.emit("GLOBAL_EMIT", {
                name: $name,
                room: $room[i],
                data: $data
            });
        }
    }
    else
    {
        events.emit("GLOBAL_EMIT", {
            name: $name,
            data: $data
        });
    }
};