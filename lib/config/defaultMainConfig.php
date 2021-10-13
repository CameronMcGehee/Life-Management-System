<?php

    $ULTISCAPECONFIG = [];

    // Whether or not PHP errors should be output to the browser as text. 
    // Should be turned off for production and only used for debugging!
    //0 for none, E_ERROR, E_WARNING, E_PARSE, or E_ALL
    $ULTISCAPECONFIG['phpErrors'] = 0;

    // Database credentials

    $ULTISCAPECONFIG['databaseServer'] = 'localhost';
    $ULTISCAPECONFIG['databaseUsername'] = 'USERNAME';
    $ULTISCAPECONFIG['databasePassword'] = 'PASSWORD';
    $ULTISCAPECONFIG['databaseDb'] = 'ultiscape';

    $ULTISCAPECONFIG['allowAccess'] = true;
    $ULTISCAPECONFIG['denyAccessTitle'] = 'UltiScape - Down for Maintenance';
    $ULTISCAPECONFIG['denyAccessMessage'] = '<h1>UltiScape is current down for maintenance and will be back up in a few moments. Reload the page in a few minutes to try again.</h1>';

    $ULTISCAPECONFIG['databaseErrTitle'] = 'UltiScape - No Database Connection';
    $ULTISCAPECONFIG['databaseErrMessage'] = '<h1>Ultiscape currently cannot connect to the configured database. Reload the page in a few minutes to try again.</h1><p>If you are the owner of this installation, please check the database credentials in your config and the status of the server hosting the database.</p>';

?>
