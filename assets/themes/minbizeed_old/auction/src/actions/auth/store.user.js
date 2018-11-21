module.exports = function($socket) { // store the userID into the storage for direct emit messages
    var SERVER = this;

    if ( SERVER.storage.users.hasOwnProperty($socket.userid) )
    {
        SERVER.storage.users[$socket.userid].sockets.push($socket);

        logger.log(
            'info',
            '[%s]: User with id %s has opened a new socket connection, total sockets for this user %s.',
            INSTANCE,
            $socket.userid,
            SERVER.storage.users[$socket.userid].sockets.length );
    }
    else
    {
        SERVER.storage.users[$socket.userid] = { sockets: [$socket] };

        logger.log('info',
            '[%s]: The user with id %s was stored in sockets storage, total users online %s on this instance.',
            INSTANCE,
            $socket.userid,
            Object.keys(SERVER.storage.users).length);
    }
};