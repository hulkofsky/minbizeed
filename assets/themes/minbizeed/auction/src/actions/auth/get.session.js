var mcrypt  = require('mcrypt'),
    q       = require('q'),
    logger  = require('../../core/logger'),
    cookie  = require("cookie"),
    phpunse = require("php-unserialize"),
    redisCl = require('../../core/redis');

/**
 * Gets a socket's session from php
 * @param $socket
 */

module.exports = function($socket) {
    var cookies  = cookie.parse($socket.handshake.headers.cookie || '');
    var $defered = q.defer();

    if ( cookies['_auth'] )
    {
        var secret = '9031606119255751';

        try
        {
            var bfEcb = new mcrypt.MCrypt('rijndael-128', 'ecb');
            bfEcb.open(secret);

            var ciphertext = Buffer.from(cookies['_auth'], 'base64');
            var plaintext  = bfEcb.decrypt(ciphertext).toString();
            var key        = "PHPREDIS_SESSION:" + plaintext;

            redisCl.get(key.replace(/\0[\s\S]*$/g,''), function(err, data) {
                if ( err )
                {
                    logger.log(
                        "error",
                        "[%s]: Redis error while getting the session: %s.",
                        INSTANCE,
                        err );

                    $defered.reject("MISSING_SESSION");
                }

                if ( data )
                {
                    try
                    {
                        var sessionData = phpunse.unserializeSession(data.toString('utf-8'));
                        $defered.resolve(sessionData);
                    }
                    catch($e)
                    {
                        logger.log("error", "[%s]: Couldn't parse session data.", INSTANCE);
                        $defered.reject($e);
                    }
                }
                else
                {
                    logger.log('warn',
                        '[%s]: Could not find sessionId in redis with id %s.',
                        INSTANCE,
                        key );

                    $defered.reject("MISSING_SESSION");
                }
            });
        }
        catch($e)
        {
            logger.log('error',
                '[%s]: Error occurred while decrypting the _auth cookie on secret %s, sending MISSING_SESSION response.',
                INSTANCE,
                secret,
                cookies['_auth']);

            $defered.reject("MISSING_SESSION");
        }
    }
    else
    {
        logger.log('warn',
            '[%s]: Could not find the _auth cookie on user, sending MISSING_SESSION response.',
            INSTANCE );

        $defered.reject("MISSING_SESSION");
    }

    return $defered.promise;
};