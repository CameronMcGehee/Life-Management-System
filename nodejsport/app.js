const express = require('express');
const exphbs = require('express-handlebars');
const dotenv = require('dotenv');
const mysql = require('mysql');
const fs = require("fs");
const path = require("path");
const waitUntil = require('wait-until');
const Sequelize = require('sequelize');
var sequelize;
const { exit } = require('process');

// Load config
dotenv.config({path: './config/config.env'});

const sqlConfig = JSON.parse(fs.readFileSync("./config/sql.json", "utf8"));
var sqlConfigNoDb = JSON.parse(fs.readFileSync("./config/sql.json", "utf8"));

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

app.use('/api/admin', require('./routes/endpoints/admin'));
app.use('/api/customer', require('./routes/endpoints/customer'));
app.use('/api/staff', require('./routes/endpoints/staff'));
app.use('/api/global', require('./routes/endpoints/global'));

const PORT = process.env.PORT || 80;

app.listen(PORT, console.log('UltiScape is running!'));

// Email Sender

const nodemailer = require('nodemailer');

const smtpConfig = JSON.parse(fs.readFileSync("./config/smtp.json", "utf8"));
const senderAddress = JSON.parse(fs.readFileSync("./config/senderAddress.json", "utf8")).address;

let transporter = nodemailer.createTransport(smtpConfig);

const checkRate = 5; // How many seconds between each queue check
const pullLimit = 40; // How many emails to grab for sending per queue check
const maxSendRate = 10; // Max emails that can send per second (will send evenly)

var grabWorking = false; // Used to keep from grabbing more emails if the current grab hasn't finished sending
var sending = false; // Used to keep from trying to send more than 1 email at a time

var details;

// Loop

setInterval(() => {

	if (grabWorking == false) {
		grabWorking = true;
		// Grab up to the pullLimit of emails and put them in an array
		let sql = 'SELECT * FROM emailQueueMessage ORDER BY dateTimeAdded DESC LIMIT ' + pullLimit;

		let query = db.query(sql, (err, result) => {

			if (err) {
				console.log(err);
			}

			console.log(result.length + " Email(s) grabbed!");

			result.forEach (async (message) => {
				
				waitUntil(100, Infinity, function condition() {
					return (sending == false ? true : false);
				}, function done() {
					// If the type is template, grab the template values and make the email text
					if (message.messageType == 'template') {

						// Get the subject and content of the template 
						
						// Replace all the variables with the provided variables in the templateVarInputs field

					} else if (message.messageType == 'general') {
						// If the type is general, use the content provided
						details = {
							from: '"' + message.fromName + '" <' + senderAddress + '>', // sender address
							to: message.toEmails, // list of receivers
							subject: message.subject, // Subject line
							// text: "Hello world?", // plain text body
							html: message.contentHtml, // html body
						};
					}

					// Send the message off with the finished details
					sending = true;
					let info = transporter.sendMail(details, (err, info) => {
						if (err) {
							console.log(err);
						} else {
							console.log("'" + details.subject + "' was sent.")

							//Delete the queue listing
							let sql = "DELETE FROM emailQueueMessage WHERE emailQueueMessageId = '" + message.emailQueueMessageId + "'";
		
							let query = db.query(sql, (err) => {
								if (err) {
									console.log(err);
								}
							});

							sending = false;
						}
					});
				});

			});
		});

		grabWorking = false;
	}

}, checkRate * 1000);