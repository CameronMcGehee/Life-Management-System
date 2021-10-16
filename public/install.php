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

    // Check if UltiScape config has been installed already

    if (!file_exists('../config/mainConfig.php')) {
        // Create the file
        if (!file_exists('../config')) {
            mkdir('../config');
        }
        copy('../lib/config/defaultMainConfig.php', '../config/mainConfig.php');
        echo '<html><p>Config File has been created at <pre>/config/mainConfig.php</pre>. Input SQL database login credentials in the config to continue setup.</p></html>';
        exit;
    }
    include_once '../config/mainConfig.php';
    error_reporting($ULTISCAPECONFIG['phpErrors']);

    $conn = mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);

    // Check connection

    if (mysqli_connect_errno()) {
        // 1049 is database error for an unknown database, meaning the database has not been created yet but the connection was successful
        if (mysqli_connect_errno() == 1049) {
            // Create the database
            mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `".mysqli_real_escape_string($ULTISCAPECONFIG['databaseDb'])."`");
            // Try connecting again with the new database
            $conn = mysqli_connect($ULTISCAPECONFIG['databaseServer'], $ULTISCAPECONFIG['databaseUsername'], $ULTISCAPECONFIG['databasePassword'], $ULTISCAPECONFIG['databaseDb']);
            if (!mysqli_connect_errno()) {
                // Add all the tables since if the database wasn't there to begin with they obviously need to be added
                $result = mysqli_query($conn, file_get_contents('../lib/config/createTables.sql'));
            }
        // Otherwise it must be some other error
        } else { 
            echo '<html><p><b>SQL Server Authentication Error: </b>Input SQL server login credentials in the config to continue setup. (Error code: '.mysqli_connect_errno().')</p></html>';
            exit;
        }
    }

    // Function that checks the number of tables in the selected database, since it is used twice

    function countTables($conn) {
        $tablesResult = mysqli_query($conn, "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES");
        var_dump($tablesResult);
        if (!$tablesResult) {
            echo '<html><p><b>1 SQL Server Initialization Error: </b>Error verifying tables. Check your config and make sure you have the correct database selected and that the database user has access to the database specified.</p></html>';
            exit;
        }
    
        // Count tables
        $numTables = 0;
        while ($row = mysqli_fetch_assoc($tablesResult)) {
            $numTables++;
        }
        return $numTables;
    }

    // Check if all the tables are there.

    $minTables = 25; // How many tables are created in the createTables.sql file

    if (countTables($ULTISCAPECONFIG['databaseDb']) < $minTables) {
        // Run the create tables script if there are not enough tables
        mysqli_query($conn, file_get_contents('../lib/config/createTables.sql'));
        // Count new tables
        if (countTables($ULTISCAPECONFIG['databaseDb']) < $minTables) {
            echo '<html><p><b>SQL Server Initialization Error: </b>Error creating tables. Check your config and make sure you have the correct database selected and that the database user has access to the database specified.</p></html>';
            exit;
        }
    }

    echo '<html><p>Successfully Installed. <a href="';
    
    if ($ULTISCAPECONFIG['singleBusinessMode']) {
        echo 'admin/login';
    } else {
        echo 'admin/register';
    }
    
    echo '">Click here to begin using UltiScape!</a></p></html>';

?>
