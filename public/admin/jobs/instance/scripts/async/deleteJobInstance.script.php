<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['jobId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteJobInstanceAuthToken']) || !validateAuthToken($_POST['deleteJobInstanceAuthToken'], 'deleteJobInstance')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the job belongs to the business that is signed in
    require_once '../../../../../../lib/table/job.php';
	$currentJob = new job($_POST['jobId']);
    if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    // Delete the job (will cascade linked instance exceptions and such)
    if (!$currentJob->delete()) {
        echo 'deleteError';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteJobInstanceAuthToken'], 'deleteJobInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
