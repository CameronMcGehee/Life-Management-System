// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these functions
const authToken = require(__dirname + '/../../models/authToken.js')(sequelize);

function generateAuthToken(authName, ip, callback) {
    authToken.create({
        "authTokenId": uuid.v4(),
        "authName": authName,
        "clientIp": ip,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(authToken => {
        console.log("authToken " + authToken.authTokenId + " Generated");
        callback(authToken.authTokenId);
    })
    .catch(err => {
        console.log(err);
        callback(err);
    });
}

async function verifyAuthToken(token, name, ip) {
    authToken.findAll({
        attributes: ['authTokenId'],
        where: {
            authTokenId: req.body.authToken,
            clientIp: reqIp
        }
    })
    .then(result => {
        // If there is no authToken with the given ID then send an authToken error
        if (result.length !== 1) {
            
        }
    })
}

module.exports = {generateAuthToken};
