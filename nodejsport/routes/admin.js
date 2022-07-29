// Express and routing
const express = require('express');
const app = express();
const router = express.Router();

// Sequelize
const sequelize = require(__dirname + "/../lib/sequelize.js");

// Misc libraries
const fs = require("fs");
const moment = require("moment");
const authTokenManager = require(__dirname + "/../lib/etc/authToken/manager.js");
const adminManager = require(__dirname + "/../lib/etc/admin/manager.js");

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
                layout: 'adminAccountInit',
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

    const renderPage = async () => {
        try {
            var authToken = await authTokenManager.generate("adminLogin", reqIp);
            res.render('login', {
                layout: 'adminAccountInit',
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
    };

    // If an admin is already logged in and it's a valid account, redirect to the overview page
    // Otherwise clear any existing admin details in the session and render the login page
    (async () => {
        // Check if session contains valid adminId
        if (req.session.admin) {
            if (await adminManager.exists(req.session.admin.adminId)) {
                // Redirect to overview page
                res.redirect('/admin/overview');
            }
        } else {
            renderPage();
        }
    })();

});

router.get('/overview', (req, res) => {

    // Get the IP of the request for checking the authToken
    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    const renderPage = async () => {
        try {
            res.render('overview', {
                layout: 'adminAppMain',
                rootPath: '../',
                title: "Overview",
                showLogo: true,
                showProfileButton: true,
                pfpImagePath: '../images/ultiscape/icons/user_male.svg',
                bsImagePath: '../images/ultiscape/etc/noLogo.png',
                showBusinessSelector: true
            });
        } catch (err) {
            res.send("This page could not be rendered due to an error.");
        }
    };

    // If an admin is already logged in and it's a valid account, load the page
    // Otherwise clear any existing admin details in the session and render the login page
    (async () => {
        // Check if session contains valid adminId
        if (req.session.admin) {
            if (await adminManager.exists(req.session.admin.adminId)) {
                renderPage();
            }             
        } else {
            // Redirect to login page
            res.redirect('/admin/login');
        }
    })();

});

router.get('/settings', (req, res) => {

    // Get the IP of the request for checking the authToken
    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;

    const renderPage = async () => {
        try {
            res.render('admin/settings', {
                layout: 'adminAppMain',
                rootPath: '../',
                title: "Settings",
                showLogo: true,
                showProfileButton: true,
                pfpImagePath: '../images/ultiscape/icons/user_male.svg',
                bsImagePath: '../images/ultiscape/etc/noLogo.png',
                showBusinessSelector: true
            });
        } catch (err) {
            res.send("This page could not be rendered due to an error.");
        }
    };

    // If an admin is already logged in and it's a valid account, load the page
    // Otherwise clear any existing admin details in the session and render the login page
    (async () => {
        // Check if session contains valid adminId
        if (req.session.admin) {
            if (await adminManager.exists(req.session.admin.adminId)) {
                renderPage();
            }
        } else {
            // Redirect to login page
            res.redirect('/admin/login');
        }
    })();

});

module.exports = router;
