<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}

	parse_str($_POST['formData'], $formData);

	if (!isset($_POST['calendarEventId'])) {
		echo 'noId';
		exit();
	}
	require_once '../../../../../../lib/table/calendarEvent.php';
	$currentCalendarEvent = new calendarEvent($_POST['calendarEventId']);
	if ($currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noCalendarEvent';
        exit();
    }

	echo $currentCalendarEvent->calendarEventId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editCalendarEvent')) {
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
		global $currentCalendarEvent;
		if (!isset($formData['name']) || empty($formData['name'])) {
			echo 'name';
			exit();
		} else {
			$currentCalendarEvent->name = $formData['name'];
		}

		// contact
		if (!isset($formData['contact']) || empty($formData['contact'])) {
			echo 'contact';
			exit();
		} else {
			require_once '../../../../../../lib/table/contact.php';
			$currentContact = new contact($formData['contact']);
			if ($currentContact->existed) {
				$currentCalendarEvent->linkedToContactId = $formData['contact'];
			} else {
				$currentCalendarEvent->linkedToContactId = 'NULL';
			}
		}

		// property
		if (!isset($formData['property']) || empty($formData['property'])) {
			$currentCalendarEvent->linkedToPropertyId = 'NULL';
		} else {
			require_once '../../../../../../lib/table/property.php';
			$currentProperty = new property($formData['property']);
			if ($currentProperty->existed) {
				$currentCalendarEvent->linkedToPropertyId = $formData['property'];
			} else {
				$currentCalendarEvent->linkedToPropertyId = 'NULL';
			}
		}

		// description
		if (!isset($formData['description'])) {
			echo 'description';
			exit();
		}
		if (empty($formData['description'])) {
			$currentCalendarEvent->description = NULL;
		} else {
			$currentCalendarEvent->description = $formData['description'];
		}

		// privateNotes
		if (!isset($formData['privateNotes'])) {
			echo 'privateNotes';
			exit();
		}
		if (empty($formData['privateNotes'])) {
			$currentCalendarEvent->privateNotes = NULL;
		} else {
			$currentCalendarEvent->privateNotes = $formData['privateNotes'];
		}

		// price
		if (!isset($formData['price']) || empty($formData['price']) || !is_numeric($formData['price']) || $formData['price'] < .01) {
			if ($formData['price'] != '') {
				echo 'price';
				exit();
			} else {
				$currentCalendarEvent->price = NULL;
			}
		} else {
			$currentCalendarEvent->price = round((float)$formData['price'], 2);
		}

		// estHours
		if (!isset($formData['estHours']) || empty($formData['estHours']) || !is_numeric($formData['estHours'])) {
			$currentCalendarEvent->estHours = 0;
		} else {
			$currentCalendarEvent->estHours = (float)$formData['estHours'];
		}

		// isPrepaid
		if (!isset($formData['isPrepaid'])) {
			$currentCalendarEvent->isPrepaid = '0';
		} else {
			$currentCalendarEvent->isPrepaid = '1';
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
			$currentCalendarEvent->startDateTime = $startDate.' 00:00:00';
		} else {
			$currentCalendarEvent->startDateTime = $startDate.' '.$formData['startTime'].':00';
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
				$currentCalendarEvent->endDateTime = NULL;
			} else {
				$currentCalendarEvent->endDateTime = $endDate.' 00:00:00';
			}
		} else {
			$currentCalendarEvent->endDateTime = $endDate.' '.$formData['endTime'].':00';
		}

		// Check if the given calendarEvent instance exists for the dates given (if the calendarEvent already existed it was done at the top of the script)
		if (!$currentCalendarEvent->existed && isset($formData['isRecurring'])) {
			$calendarEventInstancesCheck = getRecurringDates($currentCalendarEvent->startDateTime, $currentCalendarEvent->endDateTime, $currentCalendarEvent->startDateTime, $formData['instanceDate'], $currentCalendarEvent->frequencyInterval, $currentCalendarEvent->frequency, $currentCalendarEvent->weekday);
			if ($calendarEventInstancesCheck && !in_array($formData['instanceDate'], $calendarEventInstancesCheck)) {
				echo 'instanceDate';
				exit();
			}
		}

		// isRecurring and recurring settings
		if (!isset($formData['isRecurring'])) {
			$currentCalendarEvent->frequencyInterval = 'none';
			$currentCalendarEvent->frequency = 0;
		} else {
			// frequencyInterval
			if (!isset($formData['frequencyInterval']) || empty($formData['frequencyInterval']) || !in_array($formData['frequencyInterval'], ['day', 'week', 'month', 'year'])) {
				echo 'frequencyInterval';
				exit();
			} else {
				$currentCalendarEvent->frequencyInterval = $formData['frequencyInterval'];
			}

			// frequency
			if (!isset($formData['frequency']) || empty($formData['frequency']) || !is_numeric($formData['frequency']) || (int)$formData['frequency'] < 0) {
				echo 'frequency';
				exit();
			} else {
				$currentCalendarEvent->frequency = (int)$formData['frequency'];
			}


			// recurrence interval
			if (!isset($formData['weekdaySelector']) || !in_array($formData['weekdaySelector'], [0, 1, 2, 3, 4, 5, 6, '0', '1', '2', '3', '4', '5', '6'])) {
				echo 'weekday';
				exit();
			} else {
				if ($currentCalendarEvent->frequencyInterval == 'month') {
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
						$currentCalendarEvent->weekday = $weekNumberOfMonth.'-'.$dayOfWeekNumber;
					} else {
						// Set weekday to NULL to indicate that it should recur on the day number in the month
						$currentCalendarEvent->weekday = NULL;
					}
					
				} elseif ($currentCalendarEvent->frequencyInterval == 'week') {
					// Set weekday to the weekday they selected
					$currentCalendarEvent->weekday = $formData['weekdaySelector'];
				} else {
					$currentCalendarEvent->weekday = NULL;
				}
			}
		}
	}

	// Get instance dates for this calendarEvent if it already existed (will get them later if not)
	require_once '../../../../../../lib/etc/time/getRecurringDates.php';
	if ($currentCalendarEvent->existed && isset($formData['isRecurring'])) {
		$calendarEventInstancesCheck = getRecurringDates($currentCalendarEvent->startDateTime, $currentCalendarEvent->endDateTime, $currentCalendarEvent->startDateTime, $formData['instanceDate'], $currentCalendarEvent->frequencyInterval, $currentCalendarEvent->frequency, $currentCalendarEvent->weekday);
		if ($calendarEventInstancesCheck && !in_array($formData['instanceDate'], $calendarEventInstancesCheck)) {
			echo 'instanceDate';
			exit();
		}
	}

	// If updating a recurring calendarEvent,
	if ($currentCalendarEvent->frequencyInterval != 'none') {

		// determine whether the frequency has been changed and whether the start date has been changed for later
		$frequencyChanged = false;
		$dateChanged = false;
	
		if ($formData['frequency'] != $currentCalendarEvent->frequency || $formData['frequencyInterval'] != $currentCalendarEvent->frequencyInterval || ($currentCalendarEvent->frequencyInterval == 'week' && $formData['weekdaySelector'] != $currentCalendarEvent->weekday) || (strpos($currentCalendarEvent->weekday, '-') && $formData['monthRecurrenceSelector'] != 'weekdayOfWeekNumber')) {
			$frequencyChanged = true;
		}
	
		$dbStartDate = explode(' ', $currentCalendarEvent->startDateTime);
	
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

			// make new recurring calendarEvent with the given details
			setValuesFromForm($formData); // But do not set this calendarEvent

			$newCalendarEvent = new calendarEvent();
			$newCalendarEvent->linkedToContactId = $currentCalendarEvent->linkedToContactId;
			$newCalendarEvent->linkedToPropertyId = $currentCalendarEvent->linkedToPropertyId;
			$newCalendarEvent->name = $currentCalendarEvent->name;
			$newCalendarEvent->description = $currentCalendarEvent->description;
			$newCalendarEvent->privateNotes = $currentCalendarEvent->privateNotes;
			$newCalendarEvent->price = $currentCalendarEvent->price;
			$newCalendarEvent->estHours = $currentCalendarEvent->estHours;
			$newCalendarEvent->isPrepaid = $currentCalendarEvent->isPrepaid;
			$newCalendarEvent->frequencyInterval = $currentCalendarEvent->frequencyInterval;
			$newCalendarEvent->frequency = $currentCalendarEvent->frequency;
			$newCalendarEvent->weekday = $currentCalendarEvent->weekday;
			$newCalendarEvent->startDateTime = $currentCalendarEvent->startDateTime;
			$newCalendarEvent->endDateTime = $currentCalendarEvent->endDateTime;

			if ($newCalendarEvent->set() !== true) {
				echo 'newCalendarEventSetError';
				exit();
			}
			// Remove the current (old) calendarEvent 
			if ($currentCalendarEvent->delete() !== true) {
				echo 'deleteError';
				exit();
			}
		} else {
			// ACTUALLY DO THE UPDATES

			switch ($formData['recurrenceUpdateType']) {
				case 'thisInstance':

					setValuesFromForm($formData); // But do not set this calendarEvent

					// Add an instance exception for this instance date
					require_once '../../../../../../lib/table/calendarEventInstanceException.php';
					$calendarEventInstanceException = new calendarEventInstanceException();

					$calendarEventInstanceException->calendarEventId = $currentCalendarEvent->calendarEventId;
					$calendarEventInstanceException->instanceDate = $formData['instanceDate'];
					$calendarEventInstanceException->isRescheduled = '1';
					$calendarEventInstanceException->isCancelled = '0';
					$calendarEventInstanceException->isCompleted = '0';
					$calendarEventInstanceException->linkedToCompletedCalendarEventId = NULL;
					$calendarEventInstanceException->name = $currentCalendarEvent->name;
					$calendarEventInstanceException->description = $currentCalendarEvent->description;
					$calendarEventInstanceException->privateNotes = $currentCalendarEvent->privateNotes;
					$calendarEventInstanceException->price = $currentCalendarEvent->price;
					$calendarEventInstanceException->estHours = $currentCalendarEvent->estHours;
					$calendarEventInstanceException->isPrepaid = $currentCalendarEvent->isPrepaid;
					$calendarEventInstanceException->startDateTime = $currentCalendarEvent->startDateTime;
					$calendarEventInstanceException->endDateTime = NULL;

					if (!$calendarEventInstanceException->set()) {
						echo 'calendarEventInstanceExceptionSetError';
						exit();
					}

					break;
				case 'thisAndFutureInstances':

					if (new DateTime($currentCalendarEvent->startDateTime) == new DateTime($formData['instanceDate'])) {
						// simply delete the calendarEvent as this is the first instance and it will output wrong
						if ($currentCalendarEvent->delete() !== true) {
							echo 'deleteError';
							exit();
						}
					}
					
						// End the recurring calendarEvent before this instance
						$dayBeforeThisInstance = new DateTime($formData['instanceDate']);
						if ($currentCalendarEvent->frequencyInterval == 'week') {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P6D'));
							$currentCalendarEvent->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						} else if ($currentCalendarEvent->frequencyInterval == 'month') {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P27D'));
							$currentCalendarEvent->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						} else {
							$dayBeforeThisInstance = $dayBeforeThisInstance->sub(new DateInterval('P1D'));
							$currentCalendarEvent->endDateTime = $dayBeforeThisInstance->format('Y-m-d');
						}
						if ($currentCalendarEvent->set() !== true) {
							echo 'setError';
							exit();
						}

						// and make new recurring calendarEvent with the given details
						setValuesFromForm($formData); // But do not set this calendarEvent

						$newCalendarEvent = new calendarEvent();
						$newCalendarEvent->linkedToContactId = $currentCalendarEvent->linkedToContactId;
						$newCalendarEvent->linkedToPropertyId = $currentCalendarEvent->linkedToPropertyId;
						$newCalendarEvent->name = $currentCalendarEvent->name;
						$newCalendarEvent->description = $currentCalendarEvent->description;
						$newCalendarEvent->privateNotes = $currentCalendarEvent->privateNotes;
						$newCalendarEvent->price = $currentCalendarEvent->price;
						$newCalendarEvent->estHours = $currentCalendarEvent->estHours;
						$newCalendarEvent->isPrepaid = $currentCalendarEvent->isPrepaid;
						$newCalendarEvent->frequencyInterval = $currentCalendarEvent->frequencyInterval;
						$newCalendarEvent->frequency = $currentCalendarEvent->frequency;
						$newCalendarEvent->weekday = $currentCalendarEvent->weekday;
						$newCalendarEvent->startDateTime = $currentCalendarEvent->startDateTime;
						$newCalendarEvent->endDateTime = $currentCalendarEvent->endDateTime;

						if ($newCalendarEvent->set() !== true) {
							echo 'newCalendarEventSetError';
							exit();
						}

					break;
				case 'allInstances':

					// Remove all instance exceptons
					$currentCalendarEvent->pullInstanceExceptions();
					require_once '../../../../../../lib/table/calendarEventInstanceException.php';
					foreach ($currentCalendarEvent->instanceExceptions as $instanceExceptionId) {
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
					if ($currentCalendarEvent->set() !== true) {
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

		if ($currentCalendarEvent->set() !== true) {
			echo 'setError';
			exit();
		}
	}

	// Main inputs

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editCalendarEvent');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
