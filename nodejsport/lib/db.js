const mysql = require('mysql2');
const Sequelize = require('sequelize');
const fs = require("fs");
var sequelize;

const sqlConfig = JSON.parse(fs.readFileSync(__dirname + "/../config/sql.json", "utf8"));
var sqlConfigNoDb = JSON.parse(fs.readFileSync(__dirname + "/../config/sql.json", "utf8"));
delete sqlConfigNoDb["database"];

var db = mysql.createConnection(sqlConfig);

// Connect to db
db.connect((err) => {
	if (err) {
        console.log(err);
        // try creating the database
        console.log("There was an error, attempting to create database.");
        var db = mysql.createConnection(sqlConfigNoDb);

        db.connect((err) => {
            if (err) {
                console.log(err);
                exit();
            } else {
                var sql = "CREATE DATABASE IF NOT EXISTS `" + sqlConfig['database'] + "`";
		
                var query = db.query(sql, (err) => {
                    if (err) {
                        // console.log(err);
                        exit();
                    } else {
                        var db = mysql.createConnection(sqlConfig);
                        db.connect((err) => {
                            if (err) {
                                // console.log(err);
                                exit();
                            } else {
                                console.log("Created database successfully. Adding tables.");

                                var sql = fs.readFileSync("./lib/defaultConfigs/createTables.sql", "utf8");
		
                                var query = db.query(sql, (err) => {
                                    if (err) {
                                        // console.log(err);
                                        exit();
                                    } else {
                                        console.log("Added tables successfully. Starting.");
                                    }
                                });
                            }
                        })

                    }
                });

                
            }
        });

        
	} else {
        sequelize = new Sequelize(sqlConfig.database, sqlConfig.user, sqlConfig.password, {
            host: sqlConfig.host,
            dialect: 'mysql',
          
            pool: {
              max: 5,
              min: 0,
              idle: 10000
            }
          });
        console.log('MySql Connected successfully');
    }
});

module.exports = db;