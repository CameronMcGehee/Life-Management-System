<?php

    function purgeAuthTokens(int $mins) {
        // Get what dateTime was $mins many minutes ago
        $currentDateTime = new DateTime();
        $allowedAfter = $currentDateTime->sub(new DateInterval('PT'.$mins.'M'))->format('Y-m-d H:i:s');

        require_once dirname(__FILE__)."/../../../lib/database.php";
        $db = new database();
        $db->delete('authToken', "WHERE dateTimeAdded < '$allowedAfter'");

        return true;
    }

?>