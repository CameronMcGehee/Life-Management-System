<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['emailAddressId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteEmailAddressAuthToken']) || !validateAuthToken($_POST['deleteEmailAddressAuthToken'], 'deleteEmailAddress')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../../lib/table/customerEmailAddress.php';
	$currentEmailAddress = new customerEmailAddress($_POST['emailAddressId']);
    if ($currentEmailAddress->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentEmailAddress->customerId.'::::';

    // Delete the address
    if ($currentEmailAddress->delete()) {
        echo 'success';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteEmailAddressAuthToken'], 'deleteEmailAddress');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
