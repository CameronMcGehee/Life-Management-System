<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	if (!isset($_POST['jobId'])) {
		echo 'noId';
		exit();
	}
	require_once '../../../../../../lib/table/job.php';
	$currentJob = new job($_POST['jobId']);
	if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noJob';
        exit();
    }

	echo $currentJob->jobId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editJob')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// name
	if (!isset($formData['name']) || empty($formData['name'])) {
		echo 'name';
		exit();
	}

	// privateNotes
	if (!isset($formData['privateNotes'])) {
		echo 'privateNotes';
		exit();
	}
	if (empty($formData['privateNotes'])) {
		$currentJob->privateNotes = NULL;
	} else {
		$currentJob->privateNotes = $formData['privateNotes'];
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editJob');

	if ($currentJob->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
