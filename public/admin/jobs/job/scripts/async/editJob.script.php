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
	} else {
		$currentJob->name = $formData['name'];
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

	// price
	if (!isset($formData['price']) || empty($formData['price']) || !is_numeric($formData['price']) || $formData['price'] < .01) {
		if ($formData['price'] != '') {
			echo 'price';
			exit();
		} else {
			$currentJob->price = NULL;
		}
	} else {
		$currentJob->price = round((float)$formData['price'], 2);
	}

	// estHours
	if (!isset($formData['estHours']) || empty($formData['estHours']) || !is_numeric($formData['estHours'])) {
		$currentJob->estHours = 0;
	} else {
		$currentJob->estHours = (float)$formData['estHours'];
	}

	// isPrepaid
	if (!isset($formData['isPrepaid'])) {
		$currentJob->isPrepaid = '0';
	} else {
		$currentJob->isPrepaid = '1';
	}

	require_once '../../../../../../lib/etc/time/validateDate.php';

	//startDate
	if (!isset($formData['startDate']) || empty($formData['startDate']) || !validateDate($formData['startDate'], 'Y-m-d')) {
		echo 'startDate';
		exit();
	} else {
		$startDate = $formData['startDate'];
	}

	//startTime
	if (!isset($formData['startTime']) || empty($formData['startTime']) || !validateDate($formData['startTime'], 'H:i')) {
		$currentJob->startDateTime = $startDate.' 00:00:00';
	} else {
		$currentJob->startDateTime = $startDate.' '.$formData['startTime'].':00';
	}

	//endDate
	if (!isset($formData['endDate']) || empty($formData['endDate']) || !validateDate($formData['endDate'], 'Y-m-d')) {
		$currentDate = new DateTime();
		$endDate = $currentDate->format('Y-m-d');
	} else {
		$endDate = $formData['endDate'];
	}

	//endTime
	if (!isset($formData['endTime']) || empty($formData['endTime']) || !validateDate($formData['endTime'], 'H:i')) {
		$currentJob->endDateTime = $endDate.' 00:00:00';
	} else {
		$currentJob->endDateTime = $endDate.' '.$formData['endTime'].':00';
	}

	// isRecurring
	if (!isset($formData['isRecurring'])) {
		$currentJob->frequencyInterval = 'none';
		$currentJob->frequency = 0;
	} else {
		// frequencyInterval
		if (!isset($formData['frequencyInterval']) || empty($formData['frequencyInterval']) || !in_array($formData['frequencyInterval'], ['day', 'week', 'month', 'year'])) {
			echo 'frequencyInterval';
			exit();
		} else {
			$currentJob->frequencyInterval = $formData['frequencyInterval'];
		}

		// frequency
		if (!isset($formData['frequency']) || empty($formData['frequency']) || !is_numeric($formData['frequency']) || (int)$formData['frequency'] < 0) {
			echo 'frequency';
			exit();
		} else {
			$currentJob->frequency = (int)$formData['frequency'];
		}
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
