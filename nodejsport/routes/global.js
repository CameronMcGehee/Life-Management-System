const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    res.render('appselect', {
        layout: 'global',
        title: "Welcome to Ultiscape!"
    });
});

module.exports = router;