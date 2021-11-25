<?php

    require_once '../../../../php/startSession.php';

    $result = 'incomplete'; // Incomplete means there has not yet been an error so the script will continue.

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId'])) {
        header("location: ../../../login");
        exit();
    }

    // Check if input is set and valid
    if ( (!isset($_POST['businessName']) || strlen($_POST['businessName']) < $ULTISCAPECONFIG['businessNameMinLength']) || (!isset($_POST['authToken']) || strlen($_POST['authToken']) < 17)) {
        $result = 'inputInvalid';
    }

    if ($result == 'incomplete') {
        // Verify the auth token
        require_once '../../../../../lib/etc/authToken/useAuthToken.php';
        if (!useAuthToken($_POST['authToken'], 'createBusiness')) {
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
        require_once '../../../../../lib/database.php'; $db = new database();
        // Link the current admin to the new business
        if ($db->insert("adminBusinessBridge", array("businessId" => $newBusiness->businessId, "adminId" => $_SESSION['ultiscape_adminId'], "adminIsOwner" => 1, "dateTimeAdded" => date('Y-m-d H:i:s')))) {
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
        header("location: ../../../createbusiness?e=".urlencode($result));
        exit();
    }

?>
