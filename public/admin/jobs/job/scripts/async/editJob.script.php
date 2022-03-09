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

	// Make sure the instance date is a valid instance date
	require_once '../../../../../../lib/etc/time/validateDate.php';
	if (!validateDate($formData['instanceDate'], 'Y-m-d')) {
		echo 'instanceDate';
		exit();
	}

	// Get instance dates for this job if it already existed (will get them later if not)
	require_once '../../../../../../lib/etc/time/getRecurringDates.php';
	if ($currentJob->existed) {
		$jobInstancesCheck = getRecurringDates($currentJob->startDateTime, $currentJob->endDateTime, $currentJob->startDateTime, $formData['instanceDate'], $currentJob->frequencyInterval, $currentJob->frequency, $currentJob->weekday);
	}














	// If updating a recurring job, determine whether the frequency has been changed and whether the start date has been changed for later
	$frequencyChanged = false;
	$dateChanged = false;

	if ($formData['frequency'] != $currentJob->frequency || $formData['frequencyInterval'] != $currentJob->frequencyInterval || $formData['weekdaySelector'] != $currentJob->weekday) {
		$frequencyChanged = true;
	}

	$dbStartDate = explode(' ', $currentJob->startDateTime);

	if ($formData['startDate'] != $dbStartDate[0]) {
		$dateChanged = true;
	}

	// Main inputs

	// name
	if (!isset($formData['name']) || empty($formData['name'])) {
		echo 'name';
		exit();
	} else {
		$currentJob->name = $formData['name'];
	}

	// customer
	if (!isset($formData['customer']) || empty($formData['customer'])) {
		echo 'customer';
		exit();
	} else {
		require_once '../../../../../../lib/table/customer.php';
		$currentCustomer = new customer($formData['customer']);
		if ($currentCustomer->existed) {
			$currentJob->linkedToCustomerId = $formData['customer'];
		} else {
			$currentJob->linkedToCustomerId = 'NULL';
		}
	}

	// property
	if (!isset($formData['property']) || empty($formData['property'])) {
		$currentJob->linkedToPropertyId = 'NULL';
	} else {
		require_once '../../../../../../lib/table/property.php';
		$currentProperty = new property($formData['property']);
		if ($currentProperty->existed) {
			$currentJob->linkedToPropertyId = $formData['property'];
		} else {
			$currentJob->linkedToPropertyId = 'NULL';
		}
	}

	// description
	if (!isset($formData['description'])) {
		echo 'description';
		exit();
	}
	if (empty($formData['description'])) {
		$currentJob->description = NULL;
	} else {
		$currentJob->description = $formData['description'];
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
		$endDate = NULL;
	} else {
		$endDate = $formData['endDate'];
	}

	//endTime
	if (!isset($formData['endTime']) || empty($formData['endTime']) || !validateDate($formData['endTime'], 'H:i')) {
		if ($endDate == NULL) {
			$currentJob->endDateTime = NULL;
		} else {
			$currentJob->endDateTime = $endDate.' 00:00:00';
		}
	} else {
		$currentJob->endDateTime = $endDate.' '.$formData['endTime'].':00';
	}



	// Check if the given job instance exists for the dates given (if the job already existed it was done at the top of the script)
	if (!$currentJob->existed) {
		$jobInstancesCheck = getRecurringDates($currentJob->startDateTime, $currentJob->endDateTime, $currentJob->startDateTime, $formData['instanceDate'], $currentJob->frequencyInterval, $currentJob->frequency, $currentJob->weekday);
	}



	// isRecurring and recurring settings
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











		// HOW TO UPDATE THE RECURRING JOB

		// If updating anything but recurrence interval, options are single instance and this and future
		// if ($frequencyChanged == false) {
		// 	if (!in_array($formData['recurrenceUpdateType'], ['thisInstance', 'thisAndFutureInstances'])) {
		// 		echo 'optionNotAvailable';
		// 		exit();
		// 	}
		// }

		// // If updating the recurrence interval/freq of an instance, options are this and future and "all"
		// if ($frequencyChanged == true && $dateChanged == false) {
		// 	if (!in_array($formData['recurrenceUpdateType'], ['allInstances', 'thisAndFutureInstances'])) {
		// 		echo 'optionNotAvailable';
		// 		exit();
		// 	}
		// }
		
		// // If updating the recurrence interval/freq AND the start date of an instance, there are no options, the parent recurring job is ended the day before the one being updated and a new one is created with those new options

		// if ($frequencyChanged == true && $dateChanged == true) {
		// 	// Get the day before the one being updated. If it is the first job then just use the start date as the end

		// 	if ($instanceDate == $dbStartDate) {

		// 	}
		// }

		// if () {
		// 	// if just this event, add an instance exception for this instance date

		// } else { // Otherwise, end parent recurring job the day before the one being updated and make new recurring job with same details
			
		// }







		// recurrence interval
		if (!isset($formData['weekdaySelector']) || !in_array($formData['weekdaySelector'], [0, 1, 2, 3, 4, 5, 6, '0', '1', '2', '3', '4', '5', '6'])) {
			echo 'weekday';
			exit();
		} else {
			if ($currentJob->frequencyInterval == 'month') {
				if (!isset($formData['monthRecurrenceSelector']) || empty($formData['monthRecurrenceSelector'])) {
					echo 'monthRecurrenceSelector';
					exit();
				}
				// If they have selected to repeat on a certain weekday, combine the week number (1,2,3,4) and the day of the week (0-6) in a string
				if ($formData['monthRecurrenceSelector'] == 'weekdayOfWeekNumber') {
					require_once '../../../../../../lib/etc/time/getWeekNumbers.php';
					// Get the week number of the month
					$weekNumberOfMonth = getWeekOfMonth(strtotime($formData['startDate']));

					// Get the number of the day of the week
					$dayOfWeekNumber = new DateTime($formData['startDate']);
					$dayOfWeekNumber = $dayOfWeekNumber->format('w');

					// Update
					$currentJob->weekday = $weekNumberOfMonth.'-'.$dayOfWeekNumber;
				} else {
					// Set weekday to NULL to indicate that it should recur on the day number in the month
					$currentJob->weekday = NULL;
				}
				
			} elseif ($currentJob->frequencyInterval == 'week') {
				// Set weekday to the weekday they selected
				$currentJob->weekday = $formData['weekdaySelector'];
			} else {
				$currentJob->weekday = NULL;
			}
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
