// Sequelize
const sequelize = require(__dirname + "/../../sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");
const bcrypt = require("bcrypt");

// Import sequelize models used for these functions

/**
 * Hashes a string for use in password validation.
 * @param {string} password - The string to hash
 * @param {int} saltRounds - Number of rounds to use to generate the salt used when hashing
 */
async function encrypt(password, saltRounds = 10) {
	const hashedPassword = await new Promise((resolve, reject) => {
		bcrypt.hash(password, saltRounds, function(err, hash) {
		  if (err) {
			reject(err);
		  } else {
			resolve(hash);
		  }
		});
	  });
	
	  return hashedPassword;
}

/**
 * Checks if a string matches a hash.
 * @param {string} hash - The hash to check to
 * @param {string} string - The string to check from
 */
async function verify(hash, string) {

	const checkResult = await new Promise((resolve, reject) => {
		bcrypt.compare(string, hash, function(err, result) {
			if (err) {
				reject(err);
			  } else {
				console.log(result);
				resolve(result);
			  }
		});
	});

	return checkResult;
	
}

module.exports = {encrypt, verify};
