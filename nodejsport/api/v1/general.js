const express = require('express');
const router = express.Router();

router.post('/authorize', (req, res) => {
    res.send({"name":"This is not an endpoint."});
});

module.exports = router;
