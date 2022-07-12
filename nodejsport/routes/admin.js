// Express and routing
const express = require('express');
const router = express.Router();

// DB/Sequelize
const db = require(__dirname + "/../lib/db.js");
const sequelize = require(__dirname + "/../lib/sequelize.js");

// Misc libraries
const uuid = require("uuid");
const moment = require("moment");

// Import sequelize models used for these routes
const authToken = require(__dirname + '/../lib/models/authToken.js')(sequelize);

//Body Parser to parse the JSON requests
const bodyParser = require('body-parser');
var jsonParser = bodyParser.json();
// var urlencodedParser = bodyParser.urlencoded({ extended: false });

// If someone goes to the base page of /admin, check if they are logged in. 
// If they are not, check for a saved login. If there is a saved login then sign it back in and redirect to overview
// Otherwise, just redirect to the login page
router.get('/', (req, res) => {
    if (true) { // not logged in
        res.redirect('/admin/login');
    } else {
        res.render('overview', {
            layout: 'overview',
            title: "UltiScape Login"
        });
    }
});

// Admin createaccount page
router.get('/createaccount', (req, res) => {

    // Get the IP of the request for checking the authToken
    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    // Generate createAccountAuthToken
    authToken.create({
        "authTokenId": uuid.v4(),
        "authName": "createAccount",
        "clientIp": reqIp,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(authToken => {
        console.log("authToken " + authToken.authTokenId + " Generated");
        var createAccountAuthToken = authToken.authTokenId;

        res.render('createaccount', {
            layout: 'createaccount',
            rootPath: '../',
            title: "Create an UltiScape Account",
            showLogo: true,
            showProfileButton: false,
            pfpImagePath: '../images/ultiscape/icons/user_male.svg',
            bsImagePath: '../images/ultiscape/etc/noLogo.png',
            showBusinessSelector: false,
            createAccountAuthToken: createAccountAuthToken
        });

    })
    .catch(err => {
        console.log(err);
        res.redirect('../');
    });
    
});

// Admin login page
router.get('/login', (req, res) => {

    // Get the IP of the request for checking the authToken
    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    // Generate adminLoginAuthToken
    authToken.create({
        "authTokenId": uuid.v4(),
        "authName": "adminLogin",
        "clientIp": reqIp,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(authToken => {
        console.log("authToken " + authToken.authTokenId + " Generated");
        var adminLoginAuthToken = authToken.authTokenId;

        res.render('login', {
            layout: 'login',
            rootPath: '../',
            title: "UltiScape Login",
            showLogo: true,
            showProfileButton: false,
            pfpImagePath: '../images/ultiscape/icons/user_male.svg',
            bsImagePath: '../images/ultiscape/etc/noLogo.png',
            showBusinessSelector: false,
            adminLoginAuthToken: adminLoginAuthToken
        });

    })
    .catch(err => {
        console.log(err);
        res.redirect('../');
    });
    
});

router.get('/overview', (req, res) => {
    res.render('overview');
});

module.exports = router;
