var db = require("../../core/database.js"),
    q = require("q");

    
/**
 * Create an auto bid with a delay
 * @param $id
 * @param $userId
 * @param $delay
 * @param $autoBidData
 */

// function makeAutoBid($id, $userId, $delay, $autoBidData) {
//     var AUCTIONS = this;
//
//     return new Promise(function(resolve, reject) {
//         setTimeout(function () {
//             db.getCurrentBidder($id).then(function ($bidder) {
//                 var bidder = ( $bidder.length > 0 ? $bidder[0].uid : 0 );
//
//                 if ( $userId !== bidder )
//                 {
//                     logger.log('warn',
//                         '[AUTO BID][%s]: Skipping the auto bid as the current auction winner is the person to auto bid.',
//                         $id );
//
//                     AUCTIONS.bid($id, $userId, true, $autoBidData);
//                 }
//
//                 resolve();
//             });
//         }, $delay);
//     });
// };

/*Fixing autobid 6 nov start*/
function makeAutoBid($id, $userId, $delay, $autoBidData) {
    var AUCTIONS = this;

    console.log($autoBidData, 'jebat suka blyat')
    return new Promise(function (resolve, reject) {
        setTimeout(function () {
            db.getCurrentBidder($id).then(function ($bidder) {
                var bidder = ( $bidder.length > 0 ? $bidder[0].uid : 0 );

                if (parseInt($userId) !== parseInt(bidder)) {
                    logger.log('info',
                        '[AUTO BID][%s]: Making auto bid.',
                        $id);
                    AUCTIONS.bid($id, $userId, true, $autoBidData);
                }
                else {
                    logger.log('warn',
                        '[AUTO BID][%s]: Skipping the auto bid as the current auction winner is the person to auto bid.',
                        $id);
                }

                resolve();
            });
        }, $delay);
    });
};
/*Fixing autobid 6 nov end*/

/**
 * Handles auto bids for an auction
 * @param $id
 */

module.exports = function ($id) {
    /**
     * We bind the socket server through this
     * @type {module}
     */

    var AUCTIONS = this;

    /**
     * User auto bids
     */

    logger.log('silly',
        '[%s]: Handling auto bids for auction with id %s.',
        INSTANCE,
        $id);

    db
        .loadAutobids($id)
        .then(function ($autoBids) {
            console.log('ebanistika 2')
            /**
             * Users auto bids
             */

            return new Promise(function(resolve, reject) {
                if ( !$autoBids.length )
                    return resolve();

                logger.log('silly',
                    '[AUTO BID][%s]: %s auto bids were found for auction with id %s.',
                    $id,
                    $autoBids.length,
                    $id );

                for (var i = 0, len = $autoBids.length; i < len; i++)
                {
                    makeAutoBid
                        .bind(AUCTIONS)($id, $autoBids[i].uid, i * 10, {
                            credits_start:   $autoBids[i].credits_start,
                            credits_current: $autoBids[i].credits_current,
                            take_credits:    true
                        })
                        .then(function() {
                            if ( i !== len )
                                return;

                            resolve();
                        });
                }
            });
        })
        .then(function() {
            console.log('ebanistika 3')
            /**
             * Admin auto bids
             */

            return new Promise(function (resolve, reject) {
                db.isAdminAutobidAuction($id).then(function ($isAdminAutoBid) {
                    if (!$isAdminAutoBid)
                        return resolve();

                    /**
                     * Get minimum price and auction's
                     * current price
                     */

                    q.allSettled([
                        db.getAdminAutoBidMinimum($id),
                        db.getAuctionData($id)
                    ])
                        .then(function (data) {
                            var target = data[0];
                            var auction = data[1];

                            if (!target.value.length || !auction.value.length)
                                return;

                            var currentValue = Number(auction.value[3].meta_value) || 0,
                                auctionPriceIncrease = Number(auction.value[0].meta_value) || 1,
                                targetValue = Number(target.value[0].meta_value);

                            /*Fixing autobid 6 nov start*/
                            minimumPrice = Number(auction.value[5].meta_value);
                            /*Fixing autobid 6 nov end*/

                            if (currentValue >= targetValue)
                                return;

                            db.getAdminAutoBidUsers($id).then(function ($users) {
                                // for (var i = 0, len = $users.length; i < len; i++) {
                                //     if (currentValue + auctionPriceIncrease > targetValue)
                                //         break;
                                //
                                //     makeAutoBid
                                //         .bind(AUCTIONS)($id, $users[i].meta_value, i * 10, {take_credits: false})
                                //         .then(function () {
                                //             if (i !== len)
                                //                 return;
                                //
                                //             resolve();
                                //         });
                                // }

                                /*Fixing autobid 6 nov start*/
                                for (var i = 0, len = $users.length; i < len; i++)
                                    {
                                        if ( currentValue + auctionPriceIncrease > targetValue )
                                            break;

                                        if(currentValue>=minimumPrice)
                                        {
                                            makeAutoBid
                                                .bind(AUCTIONS)($id, $users[i].meta_value, i * 10, { take_credits: false })
                                                .then(function() {
                                                    if ( i !== len )
                                                        return;

                                                    resolve();
                                                });
                                        }
                                    }
                                });
                            });
                        });
                    });
                    }).then(function() {
                        console.log('ebanistika 4')
                        logger.log('info',
                            '[%s]: Processed the auto bids for auction with id %d.',
                            INSTANCE,
                            $id );
                    });
};