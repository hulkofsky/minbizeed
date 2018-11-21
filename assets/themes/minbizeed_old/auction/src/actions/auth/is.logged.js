var q          = require('q'),
    getSession = require('./get.session');

module.exports = function($socket) {
    var $defered = q.defer();

    getSession($socket).then(function($session) {
        $session.hasOwnProperty("uid") ? $defered.resolve() : $defered.reject();
    }, function($error) {
        if ( $error )
        {
            logger.log("error", "Error encountered while getting the session, %s.", $error);
            $defered.reject("MISSING_SESSION");
        }
        else
        {
            $defered.reject("MISSING_USER_ID");
        }
    });

    return $defered.promise;
};