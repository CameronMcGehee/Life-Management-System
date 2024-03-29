const waitUntil = require('wait-until');
const mysql = require('mysql');
const nodemailer = require('nodemailer');
const fs = require("fs");

const smtpConfig = JSON.parse(fs.readFileSync("./config/smtp.json", "utf8"));
const senderAddress = JSON.parse(fs.readFileSync("./config/senderAddress.json", "utf8")).address;
const sqlConfig = JSON.parse(fs.readFileSync("./config/sql.json", "utf8"));

const db = mysql.createConnection(sqlConfig);
let transporter = nodemailer.createTransport(smtpConfig);

// Connect to db
db.connect((err) => {
	if (err) {
		console.log(err);
	}
	console.log('MySql Connected');
});

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
