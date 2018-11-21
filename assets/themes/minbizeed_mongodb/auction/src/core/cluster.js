/* ================ MODULES LOADING ========================*/
var events   = require("./events.js"),
    os       = require("os"),
    cluster  = require("cluster");


/* ===================== METHODS ===========================*/
module.exports = {
    $workers: {

    },

    forkWorker: function($port) {
        var worker  = cluster.fork();
        worker.port = $port;

        worker.send({
            cmd: 'start',
            port: worker.port
        });

        worker.on('message', function($msg) {
            switch( $msg.cmd )
            {
                case "emit-master":
                {
                    events.emit($msg.data.type, $msg.data.data);
                } break;
            }
        });

        this.$workers[$port] = worker;
    },

    init: function($master, $worker) {
        if ( cluster.isMaster )
        {
            var proxy         = require('./proxy');
            var self          = this;
            var workersLength = ( config.APP.WORKERS === 'auto' ? os.cpus().length : config.APP.WORKERS );

            for (var i = 0, len = workersLength; i < len; i++)
            {
                this.forkWorker(config.APP.START_PORT + i);

                if ( i + 1 === len )
                    logger.log("debug", "[✓] %s workers initialized.", len);
            }

            cluster.on('exit', function ($worker) {
                logger.log("error", "Worker with port %s has died, restarting the worker.", $worker.port);
                self.forkWorker($worker.port);
            });

            setTimeout(function () {
                proxy.init(workersLength, config.APP.START_PORT, config.APP.LOAD_BALANCER_PORT, function ($req) {
                    return $req.connection.remoteAddress;
                }, false);
            }, 1500);

            typeof $master === "function" && $master(self.$workers);
            return;
        }

        process.on('message', function($msg) {
            switch( $msg.cmd )
            {
                case "start":
                {
                    typeof $worker === "function" && $worker($msg.port);
                } break;

                case "emit-master":
                {
                    setImmediate(function() {
                        process.send({
                            cmd: "emit",
                            data: {
                                type: $msg.data.type,
                                data: $msg.data.data
                            }
                        });
                    });
                } break;

                case "emit":
                {
                    events.emit($msg.data.type, $msg.data.data);
                } break;
            }
        });
    }
};
