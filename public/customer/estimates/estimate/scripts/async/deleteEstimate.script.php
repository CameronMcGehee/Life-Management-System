<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['estimateId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteEstimateAuthToken']) || !validateAuthToken($_POST['deleteEstimateAuthToken'], 'deleteEstimate')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the estimate belongs to the business that is signed in
    require_once '../../../../../../lib/table/estimate.php';
	$currentEstimate = new estimate($_POST['estimateId']);
    if (!$currentEstimate->existed || $currentEstimate->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noEstimate';
        exit();
    }

	// Delete any linked payments
    require_once '../../../../../../lib/database.php';
	$db = new database();
	$db->delete('payment', "WHERE linkedToEstimateId = '$currentEstimate->estimateId'", 1);

    // Delete the estimate (will cascade linked instance exceptions and such)
    if (!$currentEstimate->delete()) {
        echo 'deleteError';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteEstimateAuthToken'], 'deleteEstimate');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
