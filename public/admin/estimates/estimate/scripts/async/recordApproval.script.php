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

	if (!isset($_POST['reason'])) {
		echo 'recordApprovalReason';
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
	if (!isset($_POST['recordApprovalAuthToken']) || !validateAuthToken($_POST['recordApprovalAuthToken'], 'recordApproval')) {
		echo 'tokenInvalid';
		exit();
	}

	$currentEstimate->approvedByAdminId = $_SESSION['ultiscape_adminId'];
	if (empty($_POST['reason'])) {
		$currentEstimate->adminReason = NULL;
	} else {
		$currentEstimate->adminReason = $_POST['reason'];
	}

	$currentDate = new DateTime();
	$currentEstimate->dateTimeApproved = $currentDate->format('Y-m-d H:i:s');

	if ($currentEstimate->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['recordApprovalAuthToken'], 'recordApproval');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
