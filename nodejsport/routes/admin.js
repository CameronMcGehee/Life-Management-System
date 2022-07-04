const express = require('express');
const router = express.Router();

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
    res.render('login', {
        layout: 'login',
        rootPath: '../',
        title: "UltiScape Login",
        showLogo: true,
        showProfileButton: true,
        pfpImagePath: '../images/ultiscape/icons/user_male.svg',
        bsImagePath: '../images/ultiscape/etc/noLogo.png',
        showBusinessSelector: true
    });
});

router.get('/overview', (req, res) => {
    res.render('overview');
});

module.exports = router;