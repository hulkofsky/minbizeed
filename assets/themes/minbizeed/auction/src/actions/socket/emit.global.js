var events   = require("../../core/events.js"),
    process  = require("process");

module.exports = function($name, $data, $room, isMaster) { // Emit the Master event to workers
    var $payload     = {};

    $payload["name"] = $name || null;
    $payload["data"] = $data || null;
    $payload["room"] = $room || null;

    if ( !isMaster )
    {
        setImmediate(function() {
            process.send({
                cmd: 'emit-master',
                data: {
                    type: "MASTER_GLOBAL_EMIT",
                    data: $payload
                }
            });
        });

        return true;
    }

    events.emit("MASTER_GLOBAL_EMIT", $payload);
};