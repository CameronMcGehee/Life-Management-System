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
	if (!isset($_POST['completeJobAuthToken']) || !validateAuthToken($_POST['completeJobAuthToken'], 'completeJob')) {
		echo 'tokenInvalid';
		exit();
	}

	// Verify the job belongs to the business that is signed in
    require_once '../../../../../../lib/table/job.php';
	$currentJob = new job($_POST['jobId']);
    if (!$currentJob->existed || $currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noJob';
        exit();
    }

    // make a completedJob with the info of the currentJob
	require_once '../../../../../../lib/table/completedJob.php';
	$completedJob = new completedJob();
	// Echo the completedJobId
	echo $completedJob->completedJobId.':::';

	$completedJob->linkedToCustomerId = $currentJob->linkedToCustomerId;
	$completedJob->linkedToPropertyId = $currentJob->linkedToPropertyId;

	// Grab data for customer name
	if ($currentJob->linkedToCustomerId !== NULL) {
		require_once '../../../../../../lib/table/customer.php';
		$customerInfo = new customer($currentJob->linkedToCustomerId);
		$completedJob->customerFirstName = $customerInfo->firstName;
		$completedJob->customerLastName = $customerInfo->lastName;
	}

	// Grab data for property address
	if ($currentJob->linkedToPropertyId !== NULL) {
		require_once '../../../../../../lib/table/property.php';
		$propertyInfo = new property($currentJob->linkedToPropertyId);
		$completedJob->propertyAddress1 = $propertyInfo->address1;
		$completedJob->propertyAddress2 = $propertyInfo->address2;
		$completedJob->propertyCity = $propertyInfo->city;
		$completedJob->propertyState = $propertyInfo->state;
		$completedJob->propertyZipCode = $propertyInfo->zipCode;
	}
	
	$completedJob->name = $currentJob->name;
	$completedJob->description = $currentJob->description;
	$completedJob->privateNotes = $currentJob->privateNotes;
	$completedJob->price = $currentJob->price;
	$completedJob->estHours = $currentJob->estHours;
	$completedJob->isPrepaid = $currentJob->isPrepaid;
	$completedJob->frequencyInterval = $currentJob->frequencyInterval;
	$completedJob->frequency = $currentJob->frequency;
	$completedJob->weekday = $currentJob->weekday;
	$completedJob->startDateTime = $currentJob->startDateTime;
	$completedJob->endDateTime = $currentJob->endDateTime;

	// If it is a singular job, set the instance date to NULL. Otherwise use the instanceDate provided, making sure it actually is a valid instance date

	if ($currentJob->frequencyInterval == 'none') {
		$completedJob->instanceDate = NULL;
	} else {
		$completedJob->linkedToJobId = $currentJob->jobId;
		// Check if it is a valid instance date
		require_once '../../../../../../lib/etc/time/getRecurringDates.php';
		$jobInstancesCheck = getRecurringDates($currentJob->startDateTime, $currentJob->endDateTime, $currentJob->startDateTime, $_POST['instanceDate'], $currentJob->frequencyInterval, $currentJob->frequency, $currentJob->weekday);
		if (in_array($_POST['instanceDate'], $jobInstancesCheck)) {
			$completedJob->instanceDate = $_POST['instanceDate'];
		} else {
			echo 'instanceDate';
			exit();
		}
	}

	if (!$completedJob->set()) {
		echo 'completedJobSetError';
		exit();
	}

	// To finish, if it is a singular job simply delete it. If it is recurring, make an instance exception for that instance.

	if ($currentJob->frequencyInterval != 'none') {
		//Make an instance exception for this instance
		require_once '../../../../../../lib/table/jobInstanceException.php';
		$jobInstanceException = new jobInstanceException();

		$jobInstanceException->jobId = $currentJob->jobId;
		$jobInstanceException->instanceDate = $_POST['instanceDate'];
		$jobInstanceException->isRescheduled = '0';
		$jobInstanceException->isCancelled = '0';
		$jobInstanceException->isCompleted = '1';
		$jobInstanceException->linkedToCompletedJobId = $completedJob->completedJobId;
		$jobInstanceException->name = $currentJob->name;
		$jobInstanceException->description = $currentJob->description;
		$jobInstanceException->privateNotes = $currentJob->privateNotes;
		$jobInstanceException->price = $currentJob->price;
		$jobInstanceException->estHours = $currentJob->estHours;
		$jobInstanceException->isPrepaid = $currentJob->isPrepaid;
		$jobInstanceException->startDateTime = $_POST['instanceDate'];
		$jobInstanceException->endDateTime = NULL;

		if (!$jobInstanceException->set()) {
			echo 'jobInstanceExceptionSetError';
			exit();
		}
	} else {
		if (!$currentJob->delete()) {
			echo 'deleteError';
			exit();
		}
	}


	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['completeJobAuthToken'], 'completeJob');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
