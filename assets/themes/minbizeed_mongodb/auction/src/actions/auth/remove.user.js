module.exports = function($socket) { // removes the user from the users storage
    var SERVER = this;

    logger.log('info',
        '[%s]: Socket disconnected, removing it from sockets storage.',
        INSTANCE );

    if ( $socket.hasOwnProperty('userid') &&
        ( $socket.userid in SERVER.storage.users ) &&
        SERVER.storage.users[$socket.userid].hasOwnProperty('sockets') )
    {
        for(var i = 0, len = SERVER.storage.users[$socket.userid].sockets.length; i < len; i++)
        {
            if ( SERVER.storage.users[$socket.userid].sockets[i].id === $socket.id )
            {
                logger.log('info', '[%s]: Socket of user with id %s has disconnected.',
                    INSTANCE,
                    $socket.userid );

                SERVER.storage.users[$socket.userid].sockets.splice(i, 1);

                break;
            }
        }

        if ( SERVER.storage.users[$socket.userid].sockets.length === 0 )
        {
            logger.log('info', '[%s]: User with id %s does not have any other sockets connected.',
                INSTANCE,
                $socket.userid );

            delete SERVER.storage.users[$socket.userid];
        }
    }
};