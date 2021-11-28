<?php

    // INSTALL PAGE, EITHER SAYS IT IS ALREADY INSTALLED OR TAKES THE USER THROUGH THE INSTALL PROCESS (mainly connecting to and creating the database)

    // Currently only makes the config file with default settings, but does not yet make the database. The database creation file will take a while to make since there are just so many tables.

    // _    _ _ _   _  _____                       
    // | |  | | | | (_)/ ____|                       
    // | |  | | | |_ _| (___   ___ __ _ _ __   ___ 
    // | |  | | | __| |\___ \ / __/ _` | '_ \ / _ \
    // | |__| | | |_| |____) | (_| (_| | |_) |  __/
    //  \____/|_|\__|_|_____/ \___\__,_| .__/ \___| 
    //                                 | |                                 
    //                                 |_|  
    //      Copyright McGehee Enterprises 2021

    if (!file_exists('../config')) {
        mkdir('../config');
    }

    // Check if crypto key has been set
    if (!file_exists('../config/cryptoKey.php')) {
        // Create the file
        copy('../lib/config/defaultCryptoKey.php', '../config/cryptoKey.php');

        echo '<html><p>Crypto Key File file has been created at <b>/config/cryptoKey.php</b>. It is recommended to change this key to something random and unique! Reload to continue.</p></html>';
        exit;
    }

    // Check if UltiScape config has been installed
    if (!file_exists('../config/mainConfig.php')) {
        // Create the file
        copy('../lib/config/defaultMainConfig.php', '../config/mainConfig.php');

        echo '<html><p>Config file has been created at <b>/config/mainConfig.php</b>. Input SQL database login credentials in the config to continue setup.</p></html>';
        exit;
    }

    $ULTISCAPECONFIG = include('../config/mainConfig.php');
    error_reporting($ULTISCAPECONFIG['phpErrors']);

    $conn = mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);

    // Check connection

    if (mysqli_connect_errno()) {
        
        // 1049 is database error for an unknown database, meaning the database has not been created yet
        if (mysqli_connect_errno() == 1049) {
            // Reconnect without a database
            $conn = mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword']);
            
            // Create the database
            mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `".$ULTISCAPECONFIG['databaseDb']."`");
            
            // Try connecting again with the new database
            $conn = mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);
            
            if (!mysqli_connect_errno()) {
                set_time_limit(0); // Make sure it runs all the way as that could cause issues
                ignore_user_abort(1);

                // Add all the tables since if the database wasn't there to begin with they obviously need to be added
                if (mysqli_multi_query($conn, file_get_contents('../lib/config/createTables.sql'))) {
                    do {
                      // Store first result set
                      if ($result = mysqli_store_result($conn)) {
                        while ($row = mysqli_fetch_row($result)) {
                        //   printf("%s\n", $row[0]);
                        }
                        mysqli_free_result($result);
                      }

                      if (mysqli_more_results($conn)) {
                        // printf("-------------\n");
                      }
                    } while (mysqli_next_result($conn));
                } else {
                    echo '<html><p><b>SQL Server Initialization Error: </b> Error creating tables in database. Check your config and make sure you have the correct database selected and that the database user has access to the database specified.</p></html>';
                    exit;
                }
            }
        // Otherwise it must be some other error
        } else { 
            echo '<html><p><b>SQL Server Authentication Error: </b>Input SQL server login credentials in the config to continue setup. (Error code: '.mysqli_connect_errno().')</p></html>';
            exit;
        }

    }

    echo '<html><p>Successfully Installed. <a href="';
    
    echo 'admin/createaccount';
    
    echo '">Click here to begin using UltiScape!</a></p></html>';

?>
