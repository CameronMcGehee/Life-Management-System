<?php

    require_once '../../../../php/startSession.php';

    $result = 'incomplete'; // Incomplete means there has not yet been an error so the script will continue.

    // Check if input is set and valid
    if ( (!isset($_POST['businessName']) || strlen($_POST['businessName']) < 6) || (!isset($_POST['authToken']) || strlen($_POST['authToken']) < 17)) {
        $result = 'inputInvalid';
    }

    if ($result == 'incomplete') {
        // Verify the auth token
        require_once '../../../../../lib/etc/authToken/verifyAuthToken.php';
        if (!verifyAuthToken($_POST['authToken'], 'createBusiness')) {
            $result = 'tokenInvalid';
        }
    }

    if ($result == 'incomplete') {
        // Create the business
        require_once '../../../../../lib/table/business.php';
        $newBusiness = new business();
        $newBusiness->displayName = $_POST['businessName'];
        if ($newBusiness->set()) {
            $result = 'success';
            // Set the current business to the new one
            $_SESSION['ultiscape_businessId'] = $newBusiness->businessId;
        } else {
            $result = 'unknown';
        }
    }
    
    // Redirect
    if ($result == 'success') {
        header("location: ../../../?popup=businessCreated");
        exit();
    } else {
        header("location: ../../?e=".urlencode($result));
        exit();
    }

?>
