"use strict";

/* ================ MODULES LOADING ========================*/
var http   = require("http"),
    https  = require("https"),
    proxy  = require("http-proxy"),
    fs     = require("fs"),
    util   = require("util");

/* ===================== METHODS ===========================*/
module.exports = {
    proxies: {},
    stickers: {},
    current: 0,
    workers: 0,
    $server: false,

    init: function($workers, $startPort, $proxyPort, $sessionHash, $noSockets) {
        this.workers = $workers;
        this.createProxies($startPort);
        this.createServer($sessionHash, $noSockets, $proxyPort);
    },

    createServer: function($sessionHash, $noSockets, $proxyPort) {
        var self = this;

        if ( config.SERVER.https )
        {
            this.$server = https.createServer({
                key: fs.readFileSync(config.SERVER.ssl.key),
                cert: fs.readFileSync(config.SERVER.ssl.cert)
            }, function($req, $res) {
                try
                {
                    self.getProxy($sessionHash, $req, $res).web($req, $res, function(e) { console.error(e); });
                }
                catch($e)
                {
                    $res.end();
                }
            })
            .on('error', function($e) {
                logger.log('error', '[%s]: Error occurred on proxy server: ', INSTANCE, $e);
            });
        }
        else
        {
            this.$server = http.createServer(function($req, $res) {
                try
                {
                    self.getProxy($sessionHash, $req, $res).web($req, $res, function(e) { console.error(e); });
                }
                catch($e)
                {
                    $res.end();
                }
            })
            .on('error', function($e) {
                logger.log('error', '[%s]: Error occurred on proxy server: ', INSTANCE, $e);
            });
        }

        if ( !$noSockets )
        {
            this.$server.on('upgrade', function($req, $socket, $head) {
                try
                {
                    self.getProxy($sessionHash, $req).ws($req, $socket, $head);
                }
                catch($e)
                {
                    logger.log('error', '[%s]: Error occurred on proxy server: ', INSTANCE, $e);
                }
            });
        }

        this.$server.listen($proxyPort);
    },

    createProxies: function($startPort) {
        var self = this;

        for(var i = 0; i < this.workers; i++)
        {
            var options = {
                target : {
                    host : "127.0.0.1",
                    port : $startPort + i
                },
                xfwd: true,
                ws: true
            };

            if ( config.SERVER.https )
            {
                options.ssl = {
                    key: fs.readFileSync(config.SERVER.ssl.key),
                    cert: fs.readFileSync(config.SERVER.ssl.cert)
                };
            }

            self.proxies[i] = new proxy.createProxyServer(options);

            self.proxies[i].on("error", function(error, req, res) {
                logger.log("error", "[%s]: Proxy has encountered an error: %s.", INSTANCE, error);
                res.end();
            });
        }
    },

    nextProxy: function() {
        var proxy = this.proxies[this.current];
        util.inspect(this.proxies, false, null);
        this.current = ( this.current + 1 ) % this.workers;
        return proxy;
    },

    getProxy: function($sessionHash, $req, $res) {
        var $hash = $sessionHash($req, $res);
        var proxy;

        if ( $hash )
        {
            if ( this.stickers[$hash] )
            {
                return this.stickers[$hash].proxy;
            }
            else
            {
                proxy                = this.nextProxy();
                this.stickers[$hash] = { proxy: proxy };
                return proxy;
            }
        }

        return this.nextProxy();
    }
};