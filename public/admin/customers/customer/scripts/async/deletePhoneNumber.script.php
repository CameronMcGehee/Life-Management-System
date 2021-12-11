<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['phoneNumberId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deletePhoneNumberAuthToken']) || !validateAuthToken($_POST['deletePhoneNumberAuthToken'], 'deletePhoneNumber')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../../lib/table/customerPhoneNumber.php';
	$currentPhoneNumber = new customerPhoneNumber($_POST['phoneNumberId']);
    if ($currentPhoneNumber->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentPhoneNumber->customerId.'::::';

    // Delete the address
    if ($currentPhoneNumber->delete()) {
        echo 'success';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deletePhoneNumberAuthToken'], 'deletePhoneNumber');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
