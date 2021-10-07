<?php

    $ULTISCAPECONFIG = [];

    // Whether or not PHP errors should be output to the browser as text. 
    // Should be turned off for production and only used for debugging!
    //0 for none, E_ERROR, E_WARNING, E_PARSE, or E_ALL
    $ULTISCAPECONFIG['phpErrors'] = E_ALL;

    $ULTISCAPECONFIG['databaseServer'] = 'localhost:3307';
    $ULTISCAPECONFIG['databaseUsername'] = 'USERNAME';
    $ULTISCAPECONFIG['databasePassword'] = 'PASSWORD';
    $ULTISCAPECONFIG['databaseDb'] = 'ultiscape';

    $ULTISCAPECONFIG['allowAccess'] = true;
    $ULTISCAPECONFIG['denyAccessMessage'] = '<h1>UltiScape is current down for maintenance and will be back up in a few moments. Reload the page in a few minutes to try again.</h1>';
    $ULTISCAPECONFIG['backupMessage'] = '<h1>Ultiscape is currently undergoing a backup and will be back up in a few moments. Reload the page in a few minutes to try again.</h1>';

?>
