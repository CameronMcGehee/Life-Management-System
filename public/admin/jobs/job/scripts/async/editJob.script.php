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

	function setValuesFromForm($formData) {
		// name
		global $formData;
		global $currentJob;
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
		if (!$currentJob->existed && isset($formData['isRecurring'])) {
			$jobInstancesCheck = getRecurringDates($currentJob->startDateTime, $currentJob->endDateTime, $currentJob->startDateTime, $formData['instanceDate'], $currentJob->frequencyInterval, $currentJob->frequency, $currentJob->weekday);
			if ($jobInstancesCheck && !in_array($formData['instanceDate'], $jobInstancesCheck)) {
				echo 'instanceDate';
				exit();
			}
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
	}

	// Get instance dates for this job if it already existed (will get them later if not)
	require_once '../../../../../../lib/etc/time/getRecurringDates.php';
	if ($currentJob->existed && isset($formData['isRecurring'])) {
		$jobInstancesCheck = getRecurringDates($currentJob->startDateTime, $currentJob->endDateTime, $currentJob->startDateTime, $formData['instanceDate'], $currentJob->frequencyInterval, $currentJob->frequency, $currentJob->weekday);
		if ($jobInstancesCheck && !in_array($formData['instanceDate'], $jobInstancesCheck)) {
			echo 'instanceDate';
			exit();
		}
	}

	// If updating a recurring job,
	if ($currentJob->frequencyInterval != 'none') {

		// determine whether the frequency has been changed and whether the start date has been changed for later
		$frequencyChanged = false;
		$dateChanged = false;
	
		if ($formData['frequency'] != $currentJob->frequency || $formData['frequencyInterval'] != $currentJob->frequencyInterval || ($currentJob->frequencyInterval == 'week' && $formData['weekdaySelector'] != $currentJob->weekday) || (strpos($currentJob->weekday, '-') && $formData['monthRecurrenceSelector'] != 'weekdayOfWeekNumber')) {
			$frequencyChanged = true;
		}
	
		$dbStartDate = explode(' ', $currentJob->startDateTime);
	
		if ($formData['startDate'] != $formData['instanceDate']) {
			$dateChanged = true;
		}
	
		// UPDATE THE RECURRING JOB

		// If updating anything but recurrence interval, options are single instance and this and future
		if (!$frequencyChanged) {
			if (!in_array($formData['recurrenceUpdateType'], ['thisInstance', 'thisAndFutureInstances'])) {
				echo 'optionNotAvailable1';
				exit();
			}
		}

		// If updating the recurrence interval/freq of an instance, options are this and future and "all"
		if ($frequencyChanged && !$dateChanged) {
			if (!in_array($formData['recurrenceUpdateType'], ['allInstances', 'thisAndFutureInstances'])) {
				echo 'optionNotAvailable2';
				exit();
			}
		}

		// If updating the recurrence interval/freq AND start date of an instance, no options
		if ($frequencyChanged && $dateChanged) {

			// make new recurring job with the given details
			setValuesFromForm($formData); // But do not set this job

			$newJob = new job();
			$newJob->linkedToCustomerId = $currentJob->linkedToCustomerId;
			$newJob->linkedToPropertyId = $currentJob->linkedToPropertyId;
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

			if ($newJob->set() !== true) {
				echo 'newJobSetError';
				exit();
			}
			// Remove the current (old) job 
			if ($currentJob->delete() !== true) {
				echo 'deleteError';
				exit();
			}
		} else {
			// ACTUALLY DO THE UPDATES

			switch ($formData['recurrenceUpdateType']) {
				case 'thisInstance':

					setValuesFromForm($formData); // But do not set this job

					// Add an instance exception for this instance date
					require_once '../../../../../../lib/table/jobInstanceException.php';
					$jobInstanceException = new jobInstanceException();

					$jobInstanceException->jobId = $currentJob->jobId;
					$jobInstanceException->instanceDate = $formData['instanceDate'];
					$jobInstanceException->isRescheduled = '1';
					$jobInstanceException->isCancelled = '0';
					$jobInstanceException->isCompleted = '0';
					$jobInstanceException->linkedToCompletedJobId = NULL;
					$jobInstanceException->name = $currentJob->name;
					$jobInstanceException->description = $currentJob->description;
					$jobInstanceException->privateNotes = $currentJob->privateNotes;
					$jobInstanceException->price = $currentJob->price;
					$jobInstanceException->estHours = $currentJob->estHours;
					$jobInstanceException->isPrepaid = $currentJob->isPrepaid;
					$jobInstanceException->startDateTime = $currentJob->startDateTime;
					$jobInstanceException->endDateTime = NULL;

					if (!$jobInstanceException->set()) {
						echo 'jobInstanceExceptionSetError';
						exit();
					}

					break;
				case 'thisAndFutureInstances':

					if (new DateTime($currentJob->startDateTime) == new DateTime($formData['instanceDate'])) {
						// simply delete the job as this is the first instance and it will output wrong
						if ($currentJob->delete() !== true) {
							echo 'deleteError';
							exit();
						}
					}
					
						// End the recurring job before this instance
						$dayBeforeThisInstance = new DateTime($formData['instanceDate']);
						if ($currentJob->frequencyInterval == 'week') {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P6D'));
							$currentJob->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						} else if ($currentJob->frequencyInterval == 'month') {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P27D'));
							$currentJob->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						} else {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P1D'));
							$currentJob->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						}
						if ($currentJob->set() !== true) {
							echo 'setError';
							exit();
						}

						// and make new recurring job with the given details
						setValuesFromForm($formData); // But do not set this job

						$newJob = new job();
						$newJob->linkedToCustomerId = $currentJob->linkedToCustomerId;
						$newJob->linkedToPropertyId = $currentJob->linkedToPropertyId;
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

						if ($newJob->set() !== true) {
							echo 'newJobSetError';
							exit();
						}

					break;
				case 'allInstances':

					// Remove all instance exceptons
					$currentJob->pullInstanceExceptions();
					require_once '../../../../../../lib/table/jobInstanceException.php';
					foreach ($currentJob->instanceExceptions as $instanceExceptionId) {
						$currentInst = new instanceException($instanceExceptionId);
						if ($currentInst->existed) {
							if ($currentInst->delete() !== true) {
								echo 'instanceDeleteError';
								exit();
							}
						}
					}

					// and update the details
					setValuesFromForm($formData);
					if ($currentJob->set() !== true) {
						echo 'setError';
						exit();
					}

					break;
				default:
					echo 'optionNotAvailable3';
					exit();
					break;
			}
		}

	} else {
		setValuesFromForm($formData);

		if ($currentJob->set() !== true) {
			echo 'setError';
			exit();
		}
	}

	// Main inputs

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editJob');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
