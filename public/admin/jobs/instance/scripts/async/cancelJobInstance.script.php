<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['instanceId'])) {
		echo 'noId';
		exit();
	}

	if (!isset($_POST['instanceDate'])) {
		echo 'noInstanceDate';
		exit();
	}

	// Validate the date format
	require_once '../../../../../../lib/etc/time/validateDate.php';
	if (!validateDate($_POST['instanceDate'], 'Y-m-d')) {
		echo 'instanceDate';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['cancelJobInstanceAuthToken']) || !validateAuthToken($_POST['cancelJobInstanceAuthToken'], 'cancelJobInstance')) {
		echo 'tokenInvalid';
		exit();
	}

	// Verify the job belongs to the business that is signed in
    require_once '../../../../../../lib/table/jobInstanceException.php';
	$currentInstance = new jobInstanceException($_POST['instanceId']);
    if ($currentInstance->businessId != $_SESSION['ultiscape_businessId'] || !$currentInstance->existed) {
        echo 'unauthorized';
        exit();
    }

	echo $currentInstance->jobInstanceExceptionId.':::';

	// Set the instance to isCancelled

	$currentInstance->isCompleted = false;
	$currentInstance->isRescheduled = false;
	$currentInstance->isCancelled = true;

	if (!$currentInstance->set()) {
		echo 'currentInstanceSetError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['cancelJobInstanceAuthToken'], 'cancelJobInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
