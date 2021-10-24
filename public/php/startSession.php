<?php

    $ULTISCAPECONFIG = include(dirname(__DIR__)."../../config/mainConfig.php");

    error_reporting($ULTISCAPECONFIG['phpErrors']);

    mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);

    // Check connection and start session
    if (mysqli_connect_errno()) {
        echo '<html><head><title>'.$ULTISCAPECONFIG['databaseErrTitle'].'</title></head><body style="background-color: white;"><span style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;">'.$ULTISCAPECONFIG['databaseErrMessage'].'</span></body></html>';
        exit();
    } elseif ($ULTISCAPECONFIG['allowAccess'] == false) {
            echo '<html><head><title>'.$ULTISCAPECONFIG['denyAccessTitle'].'</title></head><body style="background-color: white;"><span style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;">'.$ULTISCAPECONFIG['denyAccessMessage'].'</span></body></html>';
            exit();
    } else {
        session_start();
    }

?>
