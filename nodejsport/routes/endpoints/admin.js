// Express and routing
const express = require('express');
const router = express.Router();

// Passport
const passport = require('passport');

// Session
const session = require('express-session');

// DB/Sequelize
const db = require(__dirname + "/../../lib/db.js");
const sequelize = require(__dirname + "/../../lib/sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these routes
const authToken = require(__dirname + '/../../lib/models/authToken.js')(sequelize);
const admin = require(__dirname + '/../../lib/models/admin.js')(sequelize);

//Body Parser to parse the JSON requests
const bodyParser = require('body-parser');
var jsonParser = bodyParser.json();
// var urlencodedParser = bodyParser.urlencoded({ extended: false });


function sendStandardRes(res, errors) {
    var status;
    if (errors.length > 0) {
        status = 'error'
    } else {
        status = 'success';
    }

    res.send({
        "status": status,
        "errors": errors
    });

    console.log(errors);
}


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

router.post('/createaccount', jsonParser, (req, res) => {
    console.log("POST Request recieved - Create Account: " + req.body);

    var status = '';
    var errors = [];

    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    // firstName check
    if (!req.body.firstName || typeof req.body.firstName == 'undefined' || req.body.firstName.length < 2) {
        errors.push({
            type: 'firstName',
            msg: 'Must be at least 2 characters long.'
        });
    }
    
    // lastName check
    if (!req.body.lastName || typeof req.body.lastName == 'undefined' || req.body.lastName.length < 2) {
        errors.push({
            type: 'lastName',
            msg: 'Must be at least 2 characters long.'
        });
    }

    // email check
    if (!req.body.email || typeof req.body.email == 'undefined' || req.body.email.length < 2 || /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(req.body.email) !== true) {
        errors.push({
            type: 'email',
            msg: 'Enter a valid email address.'
        });
    }

    // username check
    if (!req.body.username || typeof req.body.username == 'undefined' || req.body.username.length < 5) {
        errors.push({
            type: 'username',
            msg: 'Must be at least 5 characters long.'
        });
    }

    // password check
    if (!req.body.password || typeof req.body.password == 'undefined' || req.body.password.length < 10) {
        errors.push({
            type: 'password',
            msg: 'Must be at least 10 characters long.'
        });
    }
    
    // Check if the authToken exists
    var authTokenSelect = authToken.findAll({
        attributes: ['authTokenId'],
        where: {
            authTokenId: req.body.authToken,
            clientIp: reqIp
        }
    })
    .then(result => {
        var goOn = true;
        // If there is no authToken with the given ID then send an authToken error
        if (result.length !== 1) {
            errors.push({
                type: 'authToken',
                msg: 'You are not authorized to execute this action.'
            });
            var goOn = false;
        }
    })
    .then(goOn => {
        if (goOn) {
            // Check if username or email exists
            var adminUsernameSelect = admin.findOne({
                attributes: ['adminId'],
                where: sequelize.where(sequelize.fn('lower', sequelize.col('username')), req.body.username.toLowerCase())
            })
            .then(result => {
                if (result.length == 1) {
                    errors.push({
                        type: 'username',
                        msg: 'This username is already taken.'
                    });
                }
            })
            .then(goOn => {
                sendStandardRes(res, errors);
            })
            .catch(err => {
                console.log(err);

                errors.push({
                    type: 'general',
                    msg: 'An error occurred while checking username availability.'
                });

                sendStandardRes(res, errors);

            });
        } else {
            sendStandardRes(res, errors);
        }
    })
    .catch(err => {
        // If there is an error executing the query then send an unknown error
        console.log(err);

        errors.push({
            type: 'general',
            msg: 'An error occurred while checking username availability.'
        });

        sendStandardRes(res, errors);

        console.log(errors);

    });

    // Check if userName or email exists
    
    
});

router.post('/login', jsonParser, (req, res) => {
    console.log("POST Request recieved - Admin Login: " + req.body);
    
    var status = '';
    var errors = [];

    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
    
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
            errors.push({
                type: 'authToken',
                msg: 'You are not authorized to execute this action.'
            });
        }

        sendStandardRes(res, errors);
    });

    // Check if userName or email exists

    // Check if password matches the selected user in db
    
    
});

module.exports = router;
