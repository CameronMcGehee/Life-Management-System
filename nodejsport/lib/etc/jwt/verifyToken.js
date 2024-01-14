function verifyToken(req, res, next) {
    // Get header value
    const bearerHeader = req.headers['authorization'];

    // Check if undefined
    if (typeof bearerHeader !== 'undefined') {

    } else {
        res.sendStatus(403);
    }
}