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
    }

    // If database exists and there are tables

    // if () {

    // }

    echo '<html><p>Successfully Installed. <a href="index">Click begin using UltiScape!</a></p></html>';

?>