<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['calendarEventId'])) {
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
	if (!isset($_POST['completeCalendarEventAuthToken']) || !validateAuthToken($_POST['completeCalendarEventAuthToken'], 'completeCalendarEvent')) {
		echo 'tokenInvalid';
		exit();
	}

	// Verify the calendarEvent belongs to the workspace that is signed in
    require_once '../../../../../../lib/table/calendarEvent.php';
	$currentCalendarEvent = new calendarEvent($_POST['calendarEventId']);
    if (!$currentCalendarEvent->existed || $currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noCalendarEvent';
        exit();
    }

    // make a completedCalendarEvent with the info of the currentCalendarEvent
	require_once '../../../../../../lib/table/completedCalendarEvent.php';
	$completedCalendarEvent = new completedCalendarEvent();
	// Echo the completedCalendarEventId
	echo $completedCalendarEvent->completedCalendarEventId.':::';

	$completedCalendarEvent->linkedToContactId = $currentCalendarEvent->linkedToContactId;
	$completedCalendarEvent->linkedToPropertyId = $currentCalendarEvent->linkedToPropertyId;

	// Grab data for contact name
	if ($currentCalendarEvent->linkedToContactId !== NULL) {
		require_once '../../../../../../lib/table/contact.php';
		$contactInfo = new contact($currentCalendarEvent->linkedToContactId);
		$completedCalendarEvent->contactFirstName = $contactInfo->firstName;
		$completedCalendarEvent->contactLastName = $contactInfo->lastName;
	}

	// Grab data for property address
	if ($currentCalendarEvent->linkedToPropertyId !== NULL) {
		require_once '../../../../../../lib/table/property.php';
		$propertyInfo = new property($currentCalendarEvent->linkedToPropertyId);
		$completedCalendarEvent->propertyAddress1 = $propertyInfo->address1;
		$completedCalendarEvent->propertyAddress2 = $propertyInfo->address2;
		$completedCalendarEvent->propertyCity = $propertyInfo->city;
		$completedCalendarEvent->propertyState = $propertyInfo->state;
		$completedCalendarEvent->propertyZipCode = $propertyInfo->zipCode;
	}
	
	$completedCalendarEvent->name = $currentCalendarEvent->name;
	$completedCalendarEvent->description = $currentCalendarEvent->description;
	$completedCalendarEvent->privateNotes = $currentCalendarEvent->privateNotes;
	$completedCalendarEvent->price = $currentCalendarEvent->price;
	$completedCalendarEvent->estHours = $currentCalendarEvent->estHours;
	$completedCalendarEvent->isPrepaid = $currentCalendarEvent->isPrepaid;
	$completedCalendarEvent->frequencyInterval = $currentCalendarEvent->frequencyInterval;
	$completedCalendarEvent->frequency = $currentCalendarEvent->frequency;
	$completedCalendarEvent->weekday = $currentCalendarEvent->weekday;
	$completedCalendarEvent->startDateTime = $currentCalendarEvent->startDateTime;
	$completedCalendarEvent->endDateTime = $currentCalendarEvent->endDateTime;

	// If it is a singular calendarEvent, set the instance date to NULL. Otherwise use the instanceDate provided, making sure it actually is a valid instance date

	if ($currentCalendarEvent->frequencyInterval == 'none') {
		$completedCalendarEvent->instanceDate = NULL;
	} else {
		$completedCalendarEvent->linkedToCalendarEventId = $currentCalendarEvent->calendarEventId;
		// Check if it is a valid instance date
		require_once '../../../../../../lib/etc/time/getRecurringDates.php';
		$calendarEventInstancesCheck = getRecurringDates($currentCalendarEvent->startDateTime, $currentCalendarEvent->endDateTime, $currentCalendarEvent->startDateTime, $_POST['instanceDate'], $currentCalendarEvent->frequencyInterval, $currentCalendarEvent->frequency, $currentCalendarEvent->weekday);
		if (in_array($_POST['instanceDate'], $calendarEventInstancesCheck)) {
			$completedCalendarEvent->instanceDate = $_POST['instanceDate'];
		} else {
			echo 'instanceDate';
			exit();
		}
	}

	if (!$completedCalendarEvent->set()) {
		echo 'completedCalendarEventSetError';
		exit();
	}

	// To finish, if it is a singular calendarEvent simply delete it. If it is recurring, make an instance exception for that instance.

	if ($currentCalendarEvent->frequencyInterval != 'none') {
		//Make an instance exception for this instance
		require_once '../../../../../../lib/table/calendarEventInstanceException.php';
		$calendarEventInstanceException = new calendarEventInstanceException();

		$calendarEventInstanceException->calendarEventId = $currentCalendarEvent->calendarEventId;
		$calendarEventInstanceException->instanceDate = $_POST['instanceDate'];
		$calendarEventInstanceException->isRescheduled = '0';
		$calendarEventInstanceException->isCancelled = '0';
		$calendarEventInstanceException->isCompleted = '1';
		$calendarEventInstanceException->linkedToCompletedCalendarEventId = $completedCalendarEvent->completedCalendarEventId;
		$calendarEventInstanceException->name = $currentCalendarEvent->name;
		$calendarEventInstanceException->description = $currentCalendarEvent->description;
		$calendarEventInstanceException->privateNotes = $currentCalendarEvent->privateNotes;
		$calendarEventInstanceException->price = $currentCalendarEvent->price;
		$calendarEventInstanceException->estHours = $currentCalendarEvent->estHours;
		$calendarEventInstanceException->isPrepaid = $currentCalendarEvent->isPrepaid;
		$calendarEventInstanceException->startDateTime = $_POST['instanceDate'];
		$calendarEventInstanceException->endDateTime = NULL;

		if (!$calendarEventInstanceException->set()) {
			echo 'calendarEventInstanceExceptionSetError';
			exit();
		}
	} else {
		if (!$currentCalendarEvent->delete()) {
			echo 'deleteError';
			exit();
		}
	}


	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['completeCalendarEventAuthToken'], 'completeCalendarEvent');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
