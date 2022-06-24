<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['completedJobId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['uncompleteCompletedJobAuthToken']) || !validateAuthToken($_POST['uncompleteCompletedJobAuthToken'], 'uncompleteCompletedJob')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the job belongs to the business that is signed in
    require_once '../../../../../../lib/table/completedJob.php';
	$currentJob = new completedJob($_POST['completedJobId']);
    if (!$currentJob->existed || $currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noJob';
        exit();
    }

    require_once '../../../../../../lib/table/job.php';
	if (!empty($currentJob->linkedToJobId)) {
		$linkedJob = new job($currentJob->linkedToJobId);
	}

	// If it is linked to a singular job (no job at all since it was deleted), create a new job with all the same details as if it was still there

	if ($currentJob->frequencyInterval == 'none') {
		require_once '../../../../../../lib/table/job.php';
		require_once '../../../../../../lib/table/customer.php';
		require_once '../../../../../../lib/table/property.php';
		$newJob = new job();

		// In case the customer or property has been deleted since the time of job completion

		if (!empty($currentJob->linkedToCustomerId)) {
			$currentCustomer = new customer($currentJob->linkedToCustomerId);
			if ($currentCustomer->existed) {
				$newJob->linkedToCustomerId = $currentJob->linkedToCustomerId;
			}
		}

		if (!empty($currentJob->linkedToPropertyId)) {
			$currentProperty = new property($currentJob->linkedToPropertyId);
			if ($currentProperty->existed) {
				$newJob->linkedToPropertyId = $currentJob->linkedToPropertyId;
			}
		}

		$newJob->name = $currentJob->name;
		$newJob->description = $currentJob->description;
		$newJob->privateNotes = $currentJob->privateNotes;
		$newJob->price = $currentJob->price;
		$newJob->estHours = $currentJob->estHours;
		$newJob->isPrepaid = $currentJob->isPrepaid;
		$newJob->frequencyInterval = $currentJob->frequencyInterval;
		$newJob->frequency = $currentJob->frequency;
		$newJob->weekday = $currentJob->weekday;
		$newJob->startDateTime = $currentJob->startDateTime;
		$newJob->endDateTime = $currentJob->endDateTime;
		
		if (!$newJob->set()) {
			echo 'newJobSetError';
			exit();
		}

		echo $newJob->jobId.':::job';

	} else {
		// If it is linked to a recurring job remove the instance exception for this completed job's instance date
		require_once '../../../../../../lib/database.php';
		$db = new database();
		$select = $db->select('jobInstanceException', 'jobInstanceExceptionId', "WHERE jobId = '$currentJob->linkedToJobId' AND instanceDate = '$currentJob->instanceDate'");
		if (!$select || count($select) !== 1) {
			// Not having the same instance date means that it was an instance that was rescheduled, so instead of deleting that instance exception, find it and change the bools from isCompleted to isRescheduled
			$select = $db->select('jobInstanceException', 'jobInstanceExceptionId', "WHERE jobId = '$currentJob->linkedToJobId' AND startDateTime = '$currentJob->startDateTime'");
			if (!$select || count($select) !== 1) {
				echo 'noInstanceException';
				exit();
			}

			$instanceExceptionId = $select[0]['jobInstanceExceptionId'];

			require_once '../../../../../../lib/table/jobInstanceException.php';
			$instanceException = new jobInstanceException($instanceExceptionId);
			if (!$instanceException->existed) {
				echo 'noInstanceException';
				exit();
			}

			$instanceException->isCompleted = '0';
			$instanceException->isCancelled = '0';
			$instanceException->isRescheduled = '1';
			
			if (!$instanceException->set()) {
				echo 'instanceExceptionSetError';
				exit();
			}

			echo $instanceException->jobId.':::instance';

		} else {
			$instanceExceptionId = $select[0]['jobInstanceExceptionId'];

			require_once '../../../../../../lib/table/jobInstanceException.php';
			$instanceException = new jobInstanceException($instanceExceptionId);

			echo $instanceException->jobId.':::job';

			if (!$instanceException->delete()) {
				echo 'instanceExceptionDeleteError';
				exit();
			}
		}
		
	}

    // Delete the job (will cascade linked instance exceptions and such)
    if (!$currentJob->delete()) {
        echo 'deleteError';
		exit();
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['uncompleteCompletedJobAuthToken'], 'uncompleteCompletedJob');
	
	// Success if gotten to bottom of script
	exit();

?>
