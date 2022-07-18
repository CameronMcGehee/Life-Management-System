// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");
const bcrypt = require("bcrypt");

// Import sequelize models used for these functions

function encrypt(authName, ip, callback) {
    // Hash the given string
}

function verify(hashed, string) {
    // Check if the hashed string 
}

module.exports = {encrypt, verify};
