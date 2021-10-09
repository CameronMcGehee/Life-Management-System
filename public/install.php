<?php

    // INSTALL PAGE, EITHER SAYS IT IS ALREADY INSTALLED OR TAKES THE USER THROUGH THE INSTALL PROCESS (mainly connecting to and creating the database)

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
        copy('../lib/config/defaultMainConfig.php', '../config/mainConfig.php');
    }

    // If database exists and there are tables

    // if () {

    // }

?>