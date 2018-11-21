var request = require('request');

for (var i = 0; i < 200; i++) {
    setTimeout(function () {
        request.get({
            rejectUnauthorized: false, url: [
                'https://minbizeed.com/',
                'mail-functions-api',
                '?token=', "9kV95XxL5sPE8tfWtjYnUO27GG1O5z",
                '&action=auction_reminder',
                '&user_id=', 117,
                '&auction_id=', 200
            ].join("")
        }, function (err) {
            err && logger.info('An error occurred while sending emails: %s', err);
        });
    }, i * 150);
}

console.log('DONE :D');