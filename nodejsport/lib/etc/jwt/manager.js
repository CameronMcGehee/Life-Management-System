// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");
const { Op } = require("sequelize");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");
const jwt = require('jsonwebtoken');

// Import sequelize models used for these functions
// const jwt = require(__dirname + '/../../models/jwt.js')(sequelize);
const permission = require(__dirname + '/../../models/permission.js')(sequelize);

/**
 * Generates a new jwt and returns it. If it cannot create an jwt, an error is thrown.
 * @param {object} payload - The data assigned and embedded into the token.
 * @param {string} secret - The secret key used to create the token.
 */
async function create(payload, secret = 'replaceThis9823urhjfiokvsdnbvc55iocnuia!shudfjg') {
    return new Promise((resolve, reject) => {
        jwt.sign(payload, secret, (err, token) => {
            console.log("token: ", token);
            if (err) {
                reject(false);
            } else {
                resolve(token);
            }
        });
    });
    
    
}

/**
 * (Optionally middleware) function that verifies that a jwt exists in the headers of the given request, and then sets req.token.
 * @param {string} req - The request that contains the authorization header
 * @param {string} res - The response to send a 403 status to if using as middleware
 * @param {function} next - The function to run after verified successfully if using as middlware
 */
function verifyHeader(req, res = null, next = null) {
    // Get header value
    const bearerHeader = req.headers['authorization'];

    // Check if undefined
    if (typeof bearerHeader !== 'undefined') {
        // Split at string
        const bearer = bearerHeader.split(' ');
        // Get token from array
        const bearerToken = bearer[1];
        // Set the token
        req.token = bearerToken;

        if (next) {
            next();
        } else {
            return true;
        }
    } else {
        if (res) {
            res.sendStatus(403);
        } else {
            return false;
        }
    }
}

/**
 * Gets an array of the permissions given to an API key.
 * @param {string} key - The ID of the jwt
 */
 async function getPermissions(key) {

    var permissionsFound = [];

    await permission.findAll({
        attributes: ['permissionName'],
        where: [
                {
                    jwtId: key
                }
            ]
    })
    .then(result => {
        // If there is no jwt with the given ID then send an jwt error
        if (result === null || result === []) {
            permissionsFound = null;
        } else {
            let rows = JSON.parse(JSON.stringify(result));

            rows.forEach(permission => {
                permissionsFound.push(permission.permissionName);
            });
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return permissionsFound;
}

/**
 * Gets an array of the permissions given to an API key.
 * @param {string} key - The ID of the jwt
 */
 async function checkPermission(key, name) {

    var permissionValid = false;

    await permission.findAll({
        attributes: ['permissionName'],
        where: {
            [Op.and]: [
                {jwtId: key},
                {permissionName: name}
            ]
        }
    })
    .then(result => {
        if (result === null || result === []) {
            permissionValid = false;
        } else {
            permissionValid = true;
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return permissionValid;
}

module.exports = {create, verifyHeader, getPermissions, checkPermission};
