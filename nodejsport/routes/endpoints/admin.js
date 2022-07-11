// Express and routing
const express = require('express');
const router = express.Router();

// DB/Sequelize
const db = require(__dirname + "/../../lib/db.js");
const sequelize = require(__dirname + "/../../lib/sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these routes
const authToken = require(__dirname + '/../../lib/models/authToken.js')(sequelize);

//Body Parser to parse the JSON requests
const bodyParser = require('body-parser');
var jsonParser = bodyParser.json();
// var urlencodedParser = bodyParser.urlencoded({ extended: false });


router.get('/', (req, res) => {
    console.log("GET Request recieved - Admin: " + req.body);
    res.send({
        "status": "error",
        "errorMessage":"This is not an endpoint."
    });
});

router.post('/', jsonParser, (req, res) => {
    console.log("POST Request recieved - Admin: " + req.body);
    res.send({
        "status": "error",
        "errorMessage":"This is not an endpoint."
    });
});

router.get('/testfunction', (req, res) => {
    console.log("GET Request recieved - Admin Test Function: " + req.body);
    res.send({
        "status": "success",
        "Test Message": "Test Admin Function"
    });
});

router.post('/login', jsonParser, (req, res) => {
    var status;
    var errorType;
    var errorMessage;

    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    console.log("POST Request recieved - Admin Login: " + req.body);
    
    // Check if the authToken exists
    var authTokenSelect = authToken.findAll({
        attributes: ['authTokenId'],
        where: {
            authTokenId: req.body.authToken,
            clientIp: reqIp
        }
    })
    .then(result => {
        if (result.length !== 1) {
            status = 'error';
            errorType = 'authToken';
            errorMessage = 'You are not authorized to execute that action.';
        } else {
            status = 'success';
            errorType = '';
            errorMessage = '';
        }

        res.send({
            "status": status,
            "errorType": errorType,
            "errorMessage": errorMessage
        });
    });

    // Check if userName or email exists

    // Check if password matches the selected user in db
    
    
});

module.exports = router;
