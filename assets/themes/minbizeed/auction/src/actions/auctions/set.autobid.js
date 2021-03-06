var db = require("../../core/database.js");

module.exports = async function ($id, $userid, $amount, $isNew) {
    var self  = this;
    var error = false;

    if ( isNaN($id) )
    {
        logger.info('warn',
            '[%s]: User with id %s tried to set auto bid with %s as amount value.',
            INSTANCE,
            $userid,
            $amount );

        return;
    }

    if ( isNaN($amount) )
    {
        logger.info('warn',
            '[%s]: User with id %s tried to set auto bid with %s as amount value.',
            INSTANCE,
            $userid,
            $amount );

        return;
    }

    db.isAlreadyEnded($id).then(function ($ended) {
        if ( $ended[0].meta_value == 1 )
        {
            error = 'The auction has already ended.';

            return self.sockets.emitUserGlobal($userid, 'AUTO_BID_ERROR', {
                id: $id,
                message: error
            }, ['AUCTION_' + $id], self.isMaster);
        }

        db.getUser($userid).then(async function ($user) {
            var userCredits = Number($user[1].meta_value) || 0;

            if ( !$amount )
            {
                error = 'The amount cannot be null.';
            }

            if ( $amount <= 0 )
            {
                error = 'The amount has to be positive';
            }
            
            if ( await db.getReservedCredits($userid, $id) + userCredits < $amount ) // ERROR MESSAGE HERE
            {
                error = 'Insufficient credits!';
            }

            if ( !error )
            {
                /* code changes for _penny_assistant table */
                db.setAutobid($id, $userid, $amount).then(async function () {

                    let reservedCredits = await db.getReservedCredits($userid, $id)
                    let amountToUpdate

                    if (!Array.isArray(reservedCredits) || !reservedCredits.length) {
                        console.log('no reserved credits')
                        reservedCredits = 0
                        amountToUpdate = -$amount
                        db.setReservedCredits($userid, $id, 0)
                    }else {
                        amountToUpdate = reservedCredits[0].reserved_credits - $amount
                    }
                    //NEW CODE
                    if(amountToUpdate<0){
                        console.log('decrement')
                        await db.decrementUserCredits($userid, Math.abs(amountToUpdate))
                    }else if(amountToUpdate>0){
                        await db.incrementUserCredits($userid, amountToUpdate)
                    }
                    
                    await db.updateReservedCredits($userid, $id, $amount)
                    //NEW CODE
                    
                    logger.log("info",
                        "[%s]: User with id %s has set an auto bid of %s bids on auction with id %s.",
                        INSTANCE,
                        $userid,
                        $amount,
                        $id );

                    self.sockets.emitUserGlobal($userid, 'AUTO_BID_SET', {
                        id: $id,
                        amount: $amount,
                        userid:$userid
                    }, ['AUCTION_' + $id], self.isMaster);
                });
            }
            else
            {
                logger.log('warn', '[%s]: User with id %s tried to create an auto bid but an error occurred: %s', INSTANCE, $userid, error);

                self.sockets.emitUserGlobal($userid, 'AUTO_BID_ERROR', {
                    id: $id,
                    message: error
                }, ['AUCTION_' + $id], self.isMaster);
            }
        });
    });
};