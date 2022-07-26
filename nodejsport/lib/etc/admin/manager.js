// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");
const { Op } = require("sequelize");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these functions
const admin = require(__dirname + '/../../models/admin.js')(sequelize);

/**
 * Checks if an admin exists.
 * @param {string} id - The ID of the admin
 */
async function exists(id) {
    var adminFound;
    await admin.findOne({
        attributes: ['adminId'],
        where: {
            adminId: id
        }
    })
    .then(result => {
        // If there is no admin with the given ID then send an admin error
        if (result === null) {
            adminFound = false;
        } else {
            adminFound = true;
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });

    return adminFound;
}

/**
 * Returns an array of objects, with properties for each desired attribute, that match the sequlize 'where' criteria.
 * @param {array} attributes - columns which should be returned from the matching rows
 * @param {object} where - The sequelize where input
 */
 async function getWhere(attributes, where) {
    var adminsFound = [];
    await admin.findAll({
        attributes,
        where
    })
    .then(result => {
        // If there is no admin with the given ID then send an admin error
        if (result.length !== 0) {
            var rows = JSON.stringify(result);
            rows = JSON.parse(rows);

            rows.forEach(admin => {
                adminsFound.push(admin);
            });
        } else {
            adminsFound = null;
        }
    })
    .catch(err => {
        throw new Error(err);
    });

    return adminsFound;
}

module.exports = {exists, getWhere};
