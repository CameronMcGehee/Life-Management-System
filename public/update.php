<?php

    $ULTISCAPECONFIG = include('../config/mainConfig.php');
    error_reporting($ULTISCAPECONFIG['phpErrors']);

    $currentUpdateStatus = 'none';

    // DATABASE UPDATES ----------------------------------------

    $updatesInOrder = [
        "0.1.1b",
        "0.1.2b",
        "0.1.3b"
    ];

    // Get the current version from the database

    include '../lib/database.php';
    $database = new database;
    $currentVersion = 'unknown';

    // Check if systemInfo table exists
    try {
        $database->select('systemInfo');
    } catch (Exception $e) {
        $currentVersion = '0.1.0b';
    }

    // If the table does not exist, then it must be created and brought up to date
    if ($currentVersion == '0.1.0b') {
        //Run 0.1.1b update
        include '../lib/updateScripts/0.1.1b.php';
        if ($currentUpdateStatus == 'success') {
            $currentVersion = '0.1.1b';
            echo "systemInfo table created - reload the page.<br>";
        }
    } else {
        // Read current version
        $selectVersion = $database->select("systemInfo", "*", "WHERE var = 'currentVersion'");

        if (!$selectVersion) {
            echo "There is no version set in the database, so the current version is unknown and updates cannot proceed. It would be best to re-install the LifeMS database at this point, or insert the correct version in the systemInfo table in a row called 'currentVersion'.<br>";
        } else {
            // Figure out the list of update scripts that need to be run, IN THE RIGHT ORDER
            $currentVersion = $selectVersion[0]["val"];
            $startLocation = array_search($currentVersion, $updatesInOrder);

            // Run all the scripts after the location

            if ($startLocation != (count($updatesInOrder)-1)) {
                foreach ($updatesInOrder as $updateVersion) {
                    if (array_search($updateVersion, $updatesInOrder) > $startLocation) {
                        echo "Running update: ".$updateVersion."<br>";
                        $currentUpdateStatus = 'starting';
                        include '../lib/updateScripts/'.$updateVersion.'.php';
                        if ($currentUpdateStatus !== 'success') {
                            echo "Update ".$updateVersion.' failed...aborting all other updates. Message: '.$currentUpdateStatus."<br>";
                            break;
                        } else {
                            echo "Success installing update ".$updateVersion."<br>";
                        }
                    }
                }
            } else {
                $currentUpdateStatus = 'success';
            }

            if ($currentUpdateStatus == 'success') {
                echo 'All updates successfully installed. <a href="./admin/login">Click here to log in to LifeMS.</a><br>';
            } else {
                echo 'There was an error while updating. Either try to fix the error, reinstall, or <a href="./admin/login">click here to log in to LifeMS</a> and pray that it works somehow.<br>';
            }

        }
    }

?>