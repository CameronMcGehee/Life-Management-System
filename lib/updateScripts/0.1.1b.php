<?php

    $currentUpdateStatus = 'run';

    $updateQuery = '
        CREATE TABLE IF NOT EXISTS `systemInfo` (
        `var` varchar(50) NOT NULL,
        `val` text NOT NULL,
        `lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`var`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ';

    $updateResult = $database->mysqlQuery($updateQuery);

    if ($updateResult !== false) {
        $database->insert("systemInfo", [
            "var" => "currentVersion",
            "val" => "0.1.1b"
        ]);
        $currentUpdateStatus = 'success';
    } else {
        // Get the SQL error
        $sqlErrorMessage = htmlspecialchars(strval($database->getLastError()));
        $currentUpdateStatus = 'Could not create the table. MySQL Error: '.$sqlErrorMessage;
    }

?>
