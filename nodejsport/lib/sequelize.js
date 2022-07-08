const mysql = require('mysql');
const Sequelize = require('sequelize');
const fs = require("fs");
var sequelize;

const sqlConfig = JSON.parse(fs.readFileSync(__dirname + "/../config/sql.json", "utf8"));
var sqlConfigNoDb = JSON.parse(fs.readFileSync(__dirname + "/../config/sql.json", "utf8"));
delete sqlConfigNoDb["database"];

var db = mysql.createConnection(sqlConfig);

module.exports = new Sequelize(sqlConfig.database, sqlConfig.user, sqlConfig.password, {
    host: sqlConfig.host,
    dialect: 'mysql',
  
    pool: {
      max: 5,
      min: 0,
      idle: 10000
    }
  });

  // Test

module.exports.authenticate()
  .then(() => console.log("Sequelize success!"))
  .catch(err => console.log(err));
