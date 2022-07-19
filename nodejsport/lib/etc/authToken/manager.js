// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");
const { Op } = require("sequelize");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these functions
const authToken = require(__dirname + '/../../models/authToken.js')(sequelize);

async function generate(authName, ip) {
    var newAuthToken;
    await authToken.create({
        "authTokenId": uuid.v4(),
        "authName": authName,
        "clientIp": ip,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(authToken => {
        console.log("authToken " + authToken.authTokenId + " Generated");
        newAuthToken = authToken.authTokenId;
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return newAuthToken;
}

async function verify(token, name, ip) {
    var authTokenFound;
    await authToken.findOne({
        attributes: ['authTokenId'],
        where: {
            [Op.and]: [
                {authTokenId: token},
                {clientIp: ip},
                {authName: name}
            ]
        }
    })
    .then(result => {
        // If there is no authToken with the given ID then send an authToken error
        if (result === null) {
            authTokenFound = false;
        } else {
            authTokenFound = true;
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return authTokenFound;
}

module.exports = {generate, verify};
