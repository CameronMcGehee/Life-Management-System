const express = require('express');
const exphbs = require('express-handlebars');
const dotenv = require('dotenv');
const mysql = require('mysql');
const fs = require("fs");
const path = require("path");
const nodemailer = require('nodemailer');
const waitUntil = require('wait-until');
const { exit } = require('process');

// Load config
dotenv.config({path: './config/config.env'});

const smtpConfig = JSON.parse(fs.readFileSync("./config/smtp.json", "utf8"));
const senderAddress = JSON.parse(fs.readFileSync("./config/senderAddress.json", "utf8")).address;
const sqlConfig = JSON.parse(fs.readFileSync("./config/sql.json", "utf8"));
var sqlConfigNoDb = JSON.parse(fs.readFileSync("./config/sql.json", "utf8"));

delete sqlConfigNoDb["database"];

var db = mysql.createConnection(sqlConfig);
let transporter = nodemailer.createTransport(smtpConfig);

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
        console.log('MySql Connected successfully');
    }
});

const app = express();

// Handlebars
app.engine('.hbs', exphbs.engine({defaultLayout: 'main', extname: '.hbs', partialsDir: __dirname + '/views/partials/'}));
app.set('view engine', '.hbs');

// Static folder
app.use(express.static(path.join(__dirname, 'public')))

// Routes
app.use('/', require('./routes/global'));
app.use('/admin', require('./routes/admin'));
app.use('/customer', require('./routes/customer'));
app.use('/staff', require('./routes/staff'));

const PORT = process.env.PORT || 80;

app.listen(PORT, console.log('lifePanel is running!'));