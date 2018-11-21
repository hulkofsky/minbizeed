var events   = require("../../core/events.js"),
    process  = require("process");

module.exports = function($user, $name, $data, $room, isMaster) { // Emit the Master event to workers
    var $payload = {};

    $payload["user"] = $user || null;
    $payload["name"] = $name || null;
    $payload["data"] = $data || null;
    $payload["room"] = $room || null;

    if ( typeof $room === "boolean" )
    {
        isMaster         = $room;
        $payload["room"] = null;
    }

    if ( !isMaster )
    {
        setImmediate(function() {
            process.send({
                cmd: 'emit-master',
                data: {
                    type: "MASTER_USER_EMIT",
                    data: $payload
                }
            });
        });

        return true;
    }

    events.emit("MASTER_USER_EMIT", $payload);
};