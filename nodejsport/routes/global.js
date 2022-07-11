const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
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