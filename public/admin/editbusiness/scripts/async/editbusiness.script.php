<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
        echo 'unauthorized';
        exit();
    }

    // Check if input is set and valid
    if (!isset($_POST['businessName']) || strlen($_POST['businessName']) < $ULTISCAPECONFIG['businessNameMinLength'] || strlen($_POST['businessName']) > $ULTISCAPECONFIG['businessNameMaxLength']) {
        echo 'businessName';
        exit();
    }
    if (true) {
        echo 'input';
        exit();
    }
    // ...

    // Verify the auth token
    require_once '../../../../../lib/etc/authToken/verifyAuthToken.php';
    if (!verifyAuthToken($_POST['authToken'], 'createBusiness')) {
        echo 'tokenInvalid';
        exit();
    }

    // Update the business
    require_once '../../../../../lib/table/business.php';
    $business = new business($_SESSION['ultiscape_businessId']);
    if (!$business->existed) {
        echo 'businessDoesntExist';
        exit();
    }
    $business->displayName = $_POST['displayName'];
    $business->adminDisplayName = $_POST['adminDisplayName'];
    // ...

    if ($business->set() !== true) {
        echo 'setError';
        exit();
    }
    
    // Redirect
    echo 'success';
    exit();

?>
