function generateAuthToken(authName) {
    require(__dirname + '../table/authToken.js');
    var token = new authToken();
    token.authName = $authName;
    token.set();
}