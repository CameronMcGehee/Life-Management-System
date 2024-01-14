const express = require('express');
const app = express();

const dotenv = require('dotenv');
const fs = require("fs");

const path = require("path");

const { exit } = require('process');

// Load config
dotenv.config({path: './config/config.env'});

// Connect to the db
const db = require('./lib/db.js');
const sequelize = require('./lib/sequelize.js');

// Session
const session = require('express-session');
app.use(session( JSON.parse(fs.readFileSync(__dirname + "/config/session.json", "utf8")) ));

// New API
app.use('/api/v1/admins', require('./api/v1/admins'));
app.use('/api/v1/customers', require('./api/v1/customers'));
app.use('/api/v1/staff', require('./api/v1/staff'));
app.use('/api/v1', require('./api/v1/general'));

const PORT = process.env.PORT || 3000;

app.listen(PORT, console.log('UltiScape is running!'));

// Email Sender
const emailSender = require('./lib/emailSender.js');
