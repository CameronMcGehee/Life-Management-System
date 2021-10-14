<?php

    // This is NOT the configuration file! If you are looking for the configuration file, edit the /config/mainConfig.php file, not this one!
    // DO NOT EDIT THIS FILE!
    // This is to be used by the installer when installing Ultiscape

    $ULTISCAPECONFIG = array(

        // Whether or not PHP errors should be output to the browser as text. 
        // Should be turned off for production and only used for debugging!
        //0 for none, E_ERROR, E_WARNING, E_PARSE, or E_ALL
        "phpErrors" => 0,

        // Database Credentials
        "databaseServer" => 'localhost',
        "databaseUsername" => 'USERNAME',
        "databasePassword" => 'PASSWORD',
        "databaseDb" => 'ultiscape',

        // Global Access Control
        "allowAccess" => true, // Essential takes down UltiScape. Users will not be able to access the site. The message below will be shown instead.
        "denyAccessTitle" => 'UltiScape - Down for Maintenance',
        "denyAccessMessage" => '<h1>UltiScape is current down for maintenance and will be back up in a few moments. Reload the page in a few minutes to try again.</h1>',

        "databaseErrTitle" => 'UltiScape - No Database Connection',
        "databaseErrMessage" => '<h1>Ultiscape currently cannot connect to the configured database. Reload the page in a few minutes to try again.</h1><p>If you are the owner of this installation, please check the database credentials in your config and the status of the server hosting the database.</p>',

        // Global Admin Constraints and Settings

        "askForNameTitleOnRegister" => false, // nameTitle just means Mr., Ms., etc.

        // Min lengths of admin details when singing up or editing profile.
        // Not recommended to have these set less than 1. Setting any to less than 0 will break things!
        "adminEmailMinLength" => 5,
        "adminUseEmailValidation" => true,
        "adminNameTitleMinLength" => 0,
        "adminFirstNameMinLength" => 3,
        "adminLastNameMinLength" => 3,
        "adminUsernameMinLength" => 5,
        "adminPasswordMinLength" => 8
    );

?>
