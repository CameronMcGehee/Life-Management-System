<?php

    require_once dirname(__DIR__)."../../config/mainConfig.php";

    error_reporting($ULTISCAPECONFIG['phpErrors']);

    mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);

    // Check connection and start session
    if (mysqli_connect_errno()) {
        echo '<html><head><title>Ultiscape - Backup In Progress</title></head><body style="background-color: white;"><span style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;"><h1>Ultiscape is currently undergoing a backup and will be back up in a few moments. Reload the page to try again.</h1></span></body></html>';
        exit();
    } else {
        session_start();
    }

?>