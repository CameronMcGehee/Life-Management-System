<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
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

    require_once '../../../../../../lib/table/contactEmailAddress.php';
	$currentEmailAddress = new contactEmailAddress($_POST['emailAddressId']);
    if ($currentEmailAddress->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentEmailAddress->contactId.'::::';

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
