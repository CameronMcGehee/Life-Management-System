// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these functions
var table = require(__dirname + '/../../models/authToken.js')(sequelize);

async function getNewUuid(type, callback) {
    var obj = require(__dirname + '/../../models/' + type + '.js')(sequelize);

    var matchFound = false;
    var currentId;

    // Generate a new uuid
    // Keep checking until an untaken ID is found
    // Will usually only check once as it is very unlikely the same UUID will be generated twice
    while (!matchFound) {
        currentId = uuid.v4();
        // Check if the id is taken
        await obj.findOne({
            attributes: [type + 'Id'],
            where: {
                [type + "Id"]: currentId
            }
        })
        .then(result => {
            if (result === null) {
                callback(currentId);
                matchFound = true;
            }
        })
        .catch(err => {
            console.log(err);
            throw new Error(err);
        });
    }
}

module.exports = {getNewUuid};
