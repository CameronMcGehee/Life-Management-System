<?php

    require_once '../../../../php/startSession.php';

    $result = 'incomplete'; // Incomplete means there has not yet been an error so the script will continue.

    // Check if input is set and valid
    if ( (!isset($_POST['businessName']) || strlen($_POST['businessName']) < $ULTISCAPECONFIG['businessNameMinLength']) || (!isset($_POST['authToken']) || strlen($_POST['authToken']) < 17)) {
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
        $newBusiness->adminDisplayName = $_POST['businessName'];
        
        if ($newBusiness->set() !== true) {
            $result = 'setError';
        }
    }

    if ($result == 'incomplete') {
        // Link the current admin to the new business
        require_once '../../../../../lib/database.php';
        $db = new database();
        if ($db->insert("adminBusinessBridge", array("businessId" => $newBusiness->businessId, "adminId" => $_SESSION['ultiscape_adminId'], "adminIsOwner" => 1))) {
            $result = 'success';
            // Set the current business to the new one
            $_SESSION['ultiscape_businessId'] = $newBusiness->businessId;
        } else {
            $result = 'linkError';
            $newBusiness->delete();
        }
    }
    
    // Redirect
    if ($result == 'success') {
        header("location: ../../../overview?popup=businessCreated");
        exit();
    } else {
        header("location: ../../../createBusiness?e=".urlencode($result));
        exit();
    }

?>
