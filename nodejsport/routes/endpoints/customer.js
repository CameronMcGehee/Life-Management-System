const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    res.send({"name":"This is not an endpoint."});
});

router.post('/', (req, res) => {
    res.send({"name":"This is not an endpoint."});
});

router.get('/testfunction', (req, res) => {
    res.send({"name":"Test Customer Function"});
});

module.exports = router;