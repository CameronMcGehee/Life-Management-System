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
	if (!isset($_POST['cancelJobAuthToken']) || !validateAuthToken($_POST['cancelJobAuthToken'], 'cancelJob')) {
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

	// If it is a singular job set it to cancelled. If it is recurring, make an instance exception for that instance.

	//Make an instance exception for this instance

	if ($currentJob->frequencyInterval == 'none') {

		echo $currentJob->jobId.':::';

		$currentJob->isCancelled = '1';

		if (!$currentJob->set()) {
			echo 'jobSetError';
			exit();
		}
	} else {
		require_once '../../../../../../lib/table/jobInstanceException.php';
		$jobInstanceException = new jobInstanceException();
	
		echo $jobInstanceException->jobInstanceExceptionId.':::';
	
		$jobInstanceException->jobId = $currentJob->jobId;
		$jobInstanceException->instanceDate = $_POST['instanceDate'];
		$jobInstanceException->isRescheduled = '0';
		$jobInstanceException->isCancelled = '1';
		$jobInstanceException->isCompleted = '0';
		$jobInstanceException->linkedToCompletedJobId = NULL;
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
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['cancelJobAuthToken'], 'cancelJob');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
