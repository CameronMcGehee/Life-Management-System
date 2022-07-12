const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    res.render('appselect', {
        layout: 'global',
        title: "Welcome to Ultiscape!"
    });
});

// If someone goes to /login but doesn't specify a type, redirect to the appselect page
router.get('/login', (req, res) => {
    res.render('appselect', {
        layout: 'global',
        title: "Welcome to Ultiscape!"
    });
});

// Catch all unknown routes and redirect to an error page
router.get('*', function(req, res) {
	res.render('unknownpage', {
        layout: 'global',
        title: "Unknown Page"
    });
});

module.exports = router;
