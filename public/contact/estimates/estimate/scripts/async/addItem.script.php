<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['estimateId'])) {
		echo 'noId';
		exit();
	}

	// Verify the estimate belongs to the workspace that is signed in
	require_once '../../../../../../lib/table/estimate.php';
	$currentEstimate = new estimate($_POST['estimateId']);
	if (!$currentEstimate->existed || $currentEstimate->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noEstimate';
        exit();
    }

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['addItemAuthToken']) || !validateAuthToken($_POST['addItemAuthToken'], 'addEstimateItem')) {
		echo 'tokenInvalid';
		exit();
	}

	require_once '../../../../../../lib/table/estimateItem.php';
	$newItem = new estimateItem();
	$newItem->estimateId = $currentEstimate->estimateId;

	echo $newItem->estimateItemId.':::';

	if ($newItem->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['addItemAuthToken'], 'addEstimateItem');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
