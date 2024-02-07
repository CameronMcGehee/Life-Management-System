<?php

    $lifemsConfig = include(dirname(__DIR__)."../../config/mainConfig.php");

    error_reporting($lifemsConfig['phpErrors']);

    mysqli_connect($lifemsConfig['databaseServer'], $lifemsConfig['databaseUsername'], $lifemsConfig['databasePassword'], $lifemsConfig['databaseDb']);

    // Check connection and start session
    if (mysqli_connect_errno()) {
        echo '<html><head><title>'.$lifemsConfig['databaseErrTitle'].'</title></head><body style="background-color: white;"><span style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;">'.$lifemsConfig['databaseErrMessage'].'</span></body></html>';
        exit();
    } elseif ($lifemsConfig['allowAccess'] == false) {
            echo '<html><head><title>'.$lifemsConfig['denyAccessTitle'].'</title></head><body style="background-color: white;"><span style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center;">'.$lifemsConfig['denyAccessMessage'].'</span></body></html>';
            exit();
    } else {
        date_default_timezone_set("America/New_York");
        session_start();
    }

?>
