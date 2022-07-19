// Express and routing
const express = require('express');
const router = express.Router();

// Sequelize
const sequelize = require(__dirname + "/../lib/sequelize.js");

// Misc libraries
const moment = require("moment");
const authTokenManager = require(__dirname + "/../lib/etc/authToken/manager.js");

// Import sequelize models used for these routes
// None yet

//Body Parser to parse the JSON requests
const bodyParser = require('body-parser');
var jsonParser = bodyParser.json();

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

    (async () => {
        try {
            var authToken = await authTokenManager.generate("createAccount", reqIp);
            res.render('createaccount', {
                layout: 'createaccount',
                rootPath: '../',
                title: "Create an UltiScape Account",
                showLogo: true,
                showProfileButton: false,
                pfpImagePath: '../images/ultiscape/icons/user_male.svg',
                bsImagePath: '../images/ultiscape/etc/noLogo.png',
                showBusinessSelector: false,
                createAccountAuthToken: authToken
            });
        } catch (err) {
            res.send("This page could not be rendered due to an error.");
        }
    })();
    
});

// Admin login page
router.get('/login', (req, res) => {

    // Get the IP of the request for checking the authToken
    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    (async () => {
        try {
            var authToken = await authTokenManager.generate("adminLogin", reqIp);
            res.render('login', {
                layout: 'login',
                rootPath: '../',
                title: "UltiScape Login",
                showLogo: true,
                showProfileButton: false,
                pfpImagePath: '../images/ultiscape/icons/user_male.svg',
                bsImagePath: '../images/ultiscape/etc/noLogo.png',
                showBusinessSelector: false,
                adminLoginAuthToken: authToken
            });
        } catch (err) {
            res.send("This page could not be rendered due to an error.");
        }
    })();
    
});

router.get('/overview', (req, res) => {
    res.render('overview');
});

module.exports = router;
