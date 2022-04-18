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
	
	// Verify the estimate belongs to the business that is signed in
	require_once '../../../../../../lib/table/estimate.php';
	$currentEstimate = new estimate($_POST['estimateId']);
	if (!$currentEstimate->existed || $currentEstimate->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noEstimate';
        exit();
    }

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['removeApprovalAuthToken']) || !validateAuthToken($_POST['removeApprovalAuthToken'], 'removeApproval')) {
		echo 'tokenInvalid';
		exit();
	}

	$currentEstimate->approvedByAdminId = NULL;
	$currentEstimate->adminReason = NULL;
	$currentEstimate->dateTimeApproved = NULL;

	if ($currentEstimate->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['removeApprovalAuthToken'], 'removeApproval');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
