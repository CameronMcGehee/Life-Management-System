<?php

    return array(

        // Whether or not PHP errors should be output to the browser as text.
        // Should be turned off for production and only used for debugging!
        //0 for none, E_ERROR, E_WARNING, E_PARSE, or E_ALL
            "phpErrors" => 0,

        // Root user - Can create new admin accounts and businesses in single-business mode.
            "rootUserUsername" => 'root',
            "rootUserPassword" => '1234567890', // Change this!!

        // Database Credentials
            "databaseServer" => 'localhost',
            "databaseUsername" => 'USERNAME',
            "databasePassword" => 'PASSWORD',
            "databaseDb" => 'ultiscape',

        // Global Access Control
            "allowAccess" => true, // Essentially takes down LifeMS. Users will not be able to access the site. The message below will be shown instead.
            "denyAccessTitle" => 'LifeMS - Down for Maintenance',
            "denyAccessMessage" => '<h1>LifeMS is currently down for maintenance and will be back up in a few moments. Reload the page in a few minutes to try again.</h1>',

            "databaseErrTitle" => 'LifeMS - No Database Connection',
            "databaseErrMessage" => '<h1>LifeMS currently cannot connect to the database. Reload the page in a few minutes to try again.</h1>',

        // Business Settings

            // Available modules:
            // - customers
            // - invoices
            // - estimates
            // - properties
            // - jobs
            // - equipment
            // - chemicals
            // - staff
            // - crews
            // - payroll
            // - email

            // Default Modules to enable when a new business is created by an admin.
            "defaultBusinessModules" => array("customers", "properties", "jobs", "invoices"),

        // Email SMTP settings - It's recommended to use something like Amazon SES for your email sending to avoid being blacklisted for using a private server
        // These settings are NOT REQUIRED as long as you do not intend to use the email module in LifeMS.
            "SMTPUseAuth" => true, // Set to false if your SMTP server does not use SMTP authentication...although it really should
            "SMTPAuthUsername" => 'username',
            "SMTPAuthPassword" => 'password',
            "SMTPAuthHost" => 'host',
            "SMTPAuthUsername" => 587,
            "SMTPAuthSecure" => 'tls', // Set to whatever you want to encrypt messages with. Usually depends on what your SMTP server supports
        
        // Input Settings

            // How many minutes to store an authToken, usually for a form, before purging it from the database
            "authTokenDefaultPurge" => 120,

            // Min lengths of credentials when singing up or editing a profile.
            // Not recommended to have these set less than 1. Setting any to less than 0 will break things!
            "emailMinLength" => 5,
            "nameTitleMinLength" => 0,
            "firstNameMinLength" => 3,
            "lastNameMinLength" => 3,
            "usernameMinLength" => 5,
            "passwordMinLength" => 8,

            // Whether to use email validation when the user enters an email address
            "useEmailValidation" => true,

            // Business inputs
            "businessNameMinLength" => 5,
            "businessNameMaxLength" => 100,
            "businessInternalNameMinLength" => 5,
            "businessInternalNameMaxLength" => 100,

            // File Upload settings
            "allowedImageUploadTypes" => array('jpg', 'jpeg', 'png')

        // Staff Settings
    );

?>
