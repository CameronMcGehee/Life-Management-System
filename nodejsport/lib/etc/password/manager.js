// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");
const bcrypt = require("bcrypt");

// Import sequelize models used for these functions

async function encrypt(password, saltRounds = 10) {
    const hashedPassword = await new Promise((resolve, reject) => {
        bcrypt.hash(password, saltRounds, function(err, hash) {
          if (err) reject(err)
          resolve(hash)
        });
      });
    
      return hashedPassword;
}

function verify(hashed, string) {
    // Check if the hashed string 
}

module.exports = {encrypt, verify};
