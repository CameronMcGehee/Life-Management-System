<?php

    function useAuthToken(string $tokenToVerify, string $authName) {
        require_once dirname(__FILE__)."/../../table/authToken.php";
        $token = new authToken($tokenToVerify);

        require_once dirname(__FILE__)."/../getClientIpAddress.php";
        if ($token->existed && $token->authName == $authName && $token->clientIp == getClientIpAddress()) {
            if (true) { /* if ($token->delete()) { */
                return true;
            }
        }
        return false;
    }

?>
