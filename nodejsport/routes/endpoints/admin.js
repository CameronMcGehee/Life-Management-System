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
const uuidManager = require(__dirname + '/../../lib/etc/uuid/manager.js');
const passwordManager = require(__dirname + '/../../lib/etc/password/manager.js');

// Import sequelize models used for these routes
const authToken = require(__dirname + '/../../lib/models/authtoken.js')(sequelize);
const admin = require(__dirname + '/../../lib/models/admin.js')(sequelize);

//Body Parser to parse the JSON requests
const bodyParser = require('body-parser');
var jsonParser = bodyParser.json();

function checkUsernameExists(username) {
    // Check if username or email exists
    admin.findAll({
        attributes: ['adminId'],
        where: sequelize.where(sequelize.fn('lower', sequelize.col('username')), username.toLowerCase())
    })
    .then(result => {
        if (result.length == 1) {
            errors.push({
                type: 'username',
                msg: 'This username is already taken.'
            });
            return true;
        }
    })
    .catch(err => {
        console.log(err);
        throw new Error(err);
    });
    return false;
}


function sendStandardRes(res, errors) {
    var status;
    if (errors.length > 0) {
        status = 'error';
        // console.log(errors);
    } else {
        status = 'success';
    }

    res.send({
        "status": status,
        "errors": errors
    });
}

router.get('/', (req, res) => {
    console.log("GET Request recieved - Admin: " + req.body);
    res.send({
        "status": "error",
        "errorMessage":"This is not an endpoint."
    });
});

// router.get('/test', (req, res) => {
//     console.log("GET Request recieved (TEST PAGE) - Admin: " + req.body);

//     res.send({
//         "status": "testing"
//     });

//     authToken.findOne({
//         attributes: ['authTokenId'],
//         where: {
            
//         }
//     })
//     .then(result => {
//         console.log(result);
//     })
// });

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

    var goOn = true;

    // Check if the authToken exists
    console.log(req.body.authToken);
    authToken.findAll({
        attributes: ['authTokenId'],
        where: {
            authTokenId: req.body.authToken,
            clientIp: reqIp,
            authName: 'adminLogin'
        }
    })
    .then(result => {
        // console.log("length: " + Object.keys(result).length);
        console.log("RESULT: " + result);
        // If there is no authToken with the given ID then send an authToken error
        if (result === null) {
            errors.push({
                type: 'authToken',
                msg: 'You\'re not authorized to sign up.'
            });
            sendStandardRes(res, errors);
            goOn = false;
        }
    }).then(() => {
        // Check if username is taken
        if (goOn) {
            try {
                var usernameExistsCheck = checkUsernameExists(req.body.username);
            } catch (err) {
                console.log(err);
                errors.push({
                    type: 'username',
                    msg: 'Something\'s wrong with this username. Try another.'
                });
                sendStandardRes(res, errors);
                goOn = false;
            }
            if (usernameExistsCheck) {
                errors.push({
                    type: 'username',
                    msg: 'This username is already taken.'
                });
                sendStandardRes(res, errors);
                goOn = false;
            }
        } else {
            sendStandardRes(res, errors);
            goOn = false;
        }
    }).then(() => {
        if (errors.length == 0 && goOn) {
            // Create user
            (async () => {
                var newAdminId = await uuidManager.getNewUuid('admin');
                var hashedPassword = await passwordManager.encrypt(req.body.password);

                admin.create({
                    adminId: newAdminId,
                    username: req.body.username,
                    password: hashedPassword,
                    email: req.body.email,
                    profilePicture: null,
                    allowSignIn: 1,
                    dateTimeJoined: moment().format('YYYY-MM-DD HH:mm:ss'),
                    dateTimeLeft: null,
                    firstName: req.body.firstName,
                    lastName: req.body.lastName
                })
                .then((err) => {
                    sendStandardRes(res, errors);
                    goOn = false;
                })
                .catch(err => {
                    console.log(err);
                    errors.push({
                        type: 'general',
                        msg: 'An unknown error occurred (here).'
                    });
                    sendStandardRes(res, errors);
                    goOn = false;
                });
            })();
        } else {
            sendStandardRes(res, errors);
            goOn = false;
        }
    })
    .catch(msg => {
        if (goOn) {
            console.log("authToken db error: " + msg);
            errors.push({
                type: 'general',
                msg: 'An unknown error occurred.'
            });
            sendStandardRes(res, errors);
            goOn = false;
        }
    });
    
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
