<?php

    function validateAuthToken(string $tokenToValidate, string $authName) {
        require_once dirname(__FILE__)."/../../table/authToken.php";
        $token = new authToken($tokenToValidate);

        require_once dirname(__FILE__)."/../getClientIpAddress.php";
        if ($token->existed && $token->authName == $authName && $token->clientIp == getClientIpAddress()) {
            return true;
        }
        return false;
    }

?>
