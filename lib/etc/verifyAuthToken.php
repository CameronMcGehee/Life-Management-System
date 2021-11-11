<?php

    function verifyAuthToken($tokenToVerify) {
        require_once dirname(__FILE__)."/../table/authToken.php";
        $token = new authToken($_POST['authToken']);

        require_once dirname(__FILE__)."/getClientIpAddress.php";
        if ($token->existed && $token->dateTimeUsed === NULL && $token->clientIp == getClientIpAddress()) {
            if ($token->delete()) {
                return true;
            }
        }
        return false;
    }

?>
