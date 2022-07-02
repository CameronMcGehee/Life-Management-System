const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    if (true) { // not logged in
        res.render('login', {
            layout: 'login',
            title: "UltiScape Login"
        });
    } else {
        res.render('overview', {
            layout: 'overview',
            title: "UltiScape Login"
        });
    }
    
});

router.get('/overview', (req, res) => {
    res.render('overview');
});

module.exports = router;