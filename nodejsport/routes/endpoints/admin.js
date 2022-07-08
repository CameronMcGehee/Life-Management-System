const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
    console.log("GET Request recieved - Admin: " + req.body);
    res.send({
        "status": "error",
        "errorMessage":"This is not an endpoint."
    });
});

router.post('/', (req, res) => {
    console.log("POST Request recieved - Admin: " + req.body);
    res.send({
        "status": "error",
        "errorMessage":"This is not an endpoint."
    });
});

router.get('/testfunction', (req, res) => {
    console.log("GET Request recieved - Admin Test Function: " + req.body);
    res.send({
        "status": "success",
        "Test Message": "Test Admin Function"
    });
});

module.exports = router;
