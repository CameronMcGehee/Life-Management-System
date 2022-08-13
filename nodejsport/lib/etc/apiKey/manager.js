// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");
const { Op } = require("sequelize");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these functions
const apiKey = require(__dirname + '/../../models/apiKey.js')(sequelize);

/**
 * Generates a new apiKey and returns it. If it cannot generate an apiKey, an error is thrown.
 * @param {string} authName - The name, (such as "adminLogin"), given to the apiKey.
 * @param {string} ip - The IP of the client requesting the authentication.
 */
async function generate(authName, ip) {
    var newApiKey;
    await apiKey.create({
        "apiKeyId": uuid.v4(),
        "authName": authName,
        "clientIp": ip,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(apiKey => {
        console.log("apiKey " + apiKey.apiKeyId + " Generated");
        newApiKey = apiKey.apiKeyId;
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return newApiKey;
}

/**
 * Verifies that an apiKey exists.
 * @param {string} key - The ID of the apiKey
 * @param {string} ip - The IP of the client requesting the verification
 */
async function verify(key, ip) {
    var apiKeyFound;
    await apiKey.findOne({
        attributes: ['apiKeyId'],
        where: {
            [Op.and]: [
                {apiKeyId: key},
                {clientIp: ip}
            ]
        }
    })
    .then(result => {
        // If there is no apiKey with the given ID then send an apiKey error
        if (result === null) {
            apiKeyFound = false;
        } else {
            apiKeyFound = true;
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return apiKeyFound;
}

/**
 * Gets an array of the permissions given to an API key.
 * @param {string} key - The ID of the apiKey
 */
 async function getPermissions(key) {

    var permissionsFound = [];

    var apiKeyFound = await this.exists();
    await apiKey.findOne({
        attributes: ['apiKeyId'],
        where: {
            [Op.and]: [
                {apiKeyId: key}
            ]
        }
    })
    .then(result => {
        // If there is no apiKey with the given ID then send an apiKey error
        if (result === null) {
            apiKeyFound = false;
        } else {
            apiKey = true;
            let rows = JSON.parse(JSON.stringify(result));

            rows.forEach(admin => {
                permissionsFound.push(admin);
            });
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return permissionsFound;
}

module.exports = {generate, verify, getPermissions};
