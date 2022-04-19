const express = require('express');
const bodyParser = require('body-parser');
const exphbs = require('express-handlebars');
const mysql = require('mysql');
const nodemailer = require('nodemailer');

const db = mysql.createConnection({ // SET DATABASE CREDENTIALS HERE
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'ultiscape'
})

// Connect to db
db.connect((err) => {
    if (err) {
        throw err;
    }
    console.log('MySql Connected');
});

const app = express();

const checkRate = 5; // How many seconds between each queue check
const pullAmount = 40; // How many emails to grab for sending per queue check
const maxSendRate = 10; // Max emails that it can send per second

app.get('/', (req, res) => {
    res.send('Hello');
});

// Loop

setInterval(() => {

}, checkRate)