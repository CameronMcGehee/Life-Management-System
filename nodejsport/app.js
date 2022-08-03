const express = require('express');
const app = express();

const exphbs = require('express-handlebars');

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

// Handlebars
app.engine('.hbs', exphbs.engine({
    defaultLayout: 'main',
    extname: '.hbs', partialsDir: __dirname + '/views/partials/',
    helpers: require(__dirname + "/lib/hbs/helpers.js")
}));
app.set('view engine', '.hbs');

// Static folder
app.use(express.static(path.join(__dirname, 'public')));

// Routes
app.use('/admin', require('./routes/admin'));
app.use('/customer', require('./routes/customer'));
app.use('/staff', require('./routes/staff'));

app.use('/api/admin', require('./routes/endpoints/admin'));
app.use('/api/customer', require('./routes/endpoints/customer'));
app.use('/api/staff', require('./routes/endpoints/staff'));
app.use('/api/global', require('./routes/endpoints/global'));

// New API
app.use('/api/v1/admins', require('./api/v1/admins'));
app.use('/api/v1/customers', require('./api/v1/customers'));
app.use('/api/v1/staff', require('./api/v1/staff'));
app.use('/api/v1/general', require('./api/v1/general'));

app.use('/', require('./routes/global'));

const PORT = process.env.PORT || 3000;

app.listen(PORT, console.log('UltiScape is running!'));

// Email Sender
const emailSender = require('./lib/emailSender.js');
