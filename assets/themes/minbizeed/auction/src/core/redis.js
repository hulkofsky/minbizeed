var redis  = require("redis"),
    client = redis.createClient(config.REDIS);

/**
 * Export the client as a singleton
 * so we use the same connection
 */

module.exports = client;
