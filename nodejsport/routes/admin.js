const express = require('express');
const router = express.Router();
const db = require(__dirname + "../../lib/db.js");
const sequelize = require(__dirname + "../../lib/sequelize.js");
const uuid = require("uuid");
const moment = require("moment");

const authToken = require(__dirname + '../../lib/models/authToken.js')(sequelize);

router.get('/', (req, res) => {
    if (true) { // not logged in
        res.redirect('admin/login');
    } else {
        res.render('overview', {
            layout: 'overview',
            title: "UltiScape Login"
        });
    }
    
});

router.get('/login', (req, res) => {

    // Generate adminLoginAuthToken
    var adminLoginAuthToken = '';

    var reqIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress
    authToken.create({
        "authTokenId": uuid.v4(),
        "businessId": "testBusiness",
        "authName": "adminLogin",
        "clientIp": reqIp,
        "dateTimeAdded": moment().format('YYYY-MM-DD HH:mm:ss')
    })
    .then(authToken => {
        console.log("authToken " + authToken.authTokenId + " Generated");
        adminLoginAuthToken = authToken.authTokenId;

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
    .catch(err => console.log(err));



    
});

router.get('/overview', (req, res) => {
    res.render('overview');
});

module.exports = router;
