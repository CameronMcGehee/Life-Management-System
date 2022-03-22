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
	if (!isset($_POST['completeJobInstanceAuthToken']) || !validateAuthToken($_POST['completeJobInstanceAuthToken'], 'completeJobInstance')) {
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

	// Get the parent job
	require_once '../../../../../../lib/table/job.php';
	$parentJob = new job($currentInstance->jobId);
    if ($parentJob->businessId != $_SESSION['ultiscape_businessId'] || !$parentJob->existed) {
        echo 'unauthorized';
        exit();
    }

    // make a completedJob with the info of the currentInstance
	require_once '../../../../../../lib/table/completedJob.php';
	$completedJob = new completedJob();
	// Echo the completedJobId
	echo $completedJob->completedJobId.':::';

	$completedJob->linkedToCustomerId = $currentInstance->linkedToCustomerId;
	$completedJob->linkedToPropertyId = $currentInstance->linkedToPropertyId;

	// Grab data for customer name
	if ($currentInstance->linkedToCustomerId !== NULL) {
		require_once '../../../../../../lib/table/customer.php';
		$customerInfo = new customer($currentInstance->linkedToCustomerId);
		$completedJob->customerFirstName = $customerInfo->firstName;
		$completedJob->customerLastName = $customerInfo->lastName;
	}

	// Grab data for property address
	if ($currentInstance->linkedToPropertyId !== NULL) {
		require_once '../../../../../../lib/table/property.php';
		$propertyInfo = new property($currentInstance->linkedToPropertyId);
		$completedJob->propertyAddress1 = $propertyInfo->address1;
		$completedJob->propertyAddress2 = $propertyInfo->address2;
		$completedJob->propertyCity = $propertyInfo->city;
		$completedJob->propertyState = $propertyInfo->state;
		$completedJob->propertyZipCode = $propertyInfo->zipCode;
	}
	
	$completedJob->name = $currentInstance->name;
	$completedJob->description = $currentInstance->description;
	$completedJob->privateNotes = $currentInstance->privateNotes;
	$completedJob->price = $currentInstance->price;
	$completedJob->estHours = $currentInstance->estHours;
	$completedJob->isPrepaid = $currentInstance->isPrepaid;
	$completedJob->frequencyInterval = $parentJob->frequencyInterval;
	$completedJob->frequency = $parentJob->frequency;
	$completedJob->weekday = $parentJob->weekday;
	$completedJob->startDateTime = $currentInstance->startDateTime;
	$completedJob->endDateTime = $currentInstance->endDateTime;

	// Check if it is a valid instance date
	require_once '../../../../../../lib/etc/time/getRecurringDates.php';
	$jobInstancesCheck = getRecurringDates($currentInstance->startDateTime, $currentInstance->endDateTime, $currentInstance->startDateTime, $_POST['instanceDate'], $parentJob->frequencyInterval, $parentJob->frequency, $parentJob->weekday);
	if (in_array($_POST['instanceDate'], $jobInstancesCheck)) {
		$completedJob->instanceDate = $_POST['instanceDate'];
	} else {
		echo 'instanceDate';
		exit();
	}

	if (!$completedJob->set()) {
		echo 'completedJobSetError';
		exit();
	}

	// Set the instance to isCompleted

	$currentInstance->isCompleted = true;
	$currentInstance->isRescheduled = false;
	$currentInstance->isCancelled = false;

	if (!$currentInstance->set()) {
		echo 'current$currentInstanceSetError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['completeJobInstanceAuthToken'], 'completeJobInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
