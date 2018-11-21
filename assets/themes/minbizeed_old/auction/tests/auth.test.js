var redis = require('redis');
var fs = require('fs');
var q = require('q');
var logger = {};
var phpunse = require('php-unserialize');

logger.log = function (a, b, c, d, e, f) {
    console.log(a, b, c, d, e, f);
};
var redisCl = redis.createClient({
    hostname: "127.0.0.1",
    port: "6379",
    password: "T6[hG[AZjcfs7L"
});
var cookie = require("cookie");

var http = require("https");
var mcrypt = require('mcrypt');
var server = http.createServer({
        key: fs.readFileSync('/etc/nginx/ssl/trendingbid_com.key'),
        cert: fs.readFileSync('/etc/nginx/ssl/trendingbid_com.crt')
    }),
    io = require("socket.io")(server);

io.on("connection", function ($socket) {
    console.log('connected we go');
    var cookies = cookie.parse($socket.handshake.headers.cookie || '');

    function getSession() {
        var $defered = q.defer();

        console.log(cookies);
        if (cookies['_auth']) {
            var secret = /\b((?:[0-9]{1,3}\.){3}[0-9]{1,3})\b/i.exec($socket.handshake.address)[0];

            console.log(cookies['_auth']);

            while (secret.length < 16) {
                secret += '#';
            }

            try {
                var bfEcb = new mcrypt.MCrypt('rijndael-128', 'ecb');
                bfEcb.open(secret);

                var ciphertext = new Buffer(cookies['_auth'], 'base64');
                var plaintext = bfEcb.decrypt(ciphertext).toString();
                var key = "PHPREDIS_SESSION:" + plaintext;

                console.log(key, 'HEEEEEEEEEEEEEE');

                console.log('PHPREDIS_SESSION:46fsdgcr9g4grfik79effe52e6' === key, '<============', 'PHPREDIS_SESSION:46fsdgcr9g4grfik79effe52e6', '===', key);

                redisCl.get(key.replace(/\0[\s\S]*$/g, ''), function (err, data) {
                    console.log('THE ERROR', err);
                    console.log('THE DATA', data);
                    if (data) {
                        try {
                            var sessionData = phpunse.unserializeSession(data.toString('utf-8'));
                            $defered.resolve(sessionData);
                        }
                        catch ($e) {
                            logger.log("info", "Couldn't parse session data.");
                            $defered.reject($e);
                        }
                    }
                    else {
                        $defered.reject("MISSING_SESSION");
                    }
                });
            }
            catch ($e)
            {
                logger.log('info', 'Error occurred while decrypting the _auth cookie on secret %s, sending MISSING_SESSION response.', secret, cookies['_auth']);
                $defered.reject("MISSING_SESSION");
            }
        }
        else {
            $defered.reject("MISSING_SESSION");
        }

        return $defered.promise;
    }

    function isLogged() {
        var $defered = q.defer();

        getSession().then(function ($session) {
            $session.hasOwnProperty("uid") ? $defered.resolve() : $defered.reject();
        }, function ($error) {
            if ($error) {
                logger.log("info", "Error encountered while getting the session, %s.", $error);
                $defered.reject("MISSING_SESSION");
            }
            else {
                $defered.reject("MISSING_USER_ID");
            }
        });

        return $defered.promise;
    }

    getSession().then(function ($session) {
        console.log('everything OK', $session);
    }, function ($error) {
        if ($error) logger.log("info", "Error encountered while getting the session, %s.", $error);
        $socket.disconnect();
    });

});

server.listen(9000);