<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
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
	if (!isset($_POST['completeCalendarEventInstanceAuthToken']) || !validateAuthToken($_POST['completeCalendarEventInstanceAuthToken'], 'completeCalendarEventInstance')) {
		echo 'tokenInvalid';
		exit();
	}

	// Verify the calendarEvent belongs to the workspace that is signed in
    require_once '../../../../../../lib/table/calendarEventInstanceException.php';
	$currentInstance = new calendarEventInstanceException($_POST['instanceId']);
    if ($currentInstance->workspaceId != $_SESSION['lifems_workspaceId'] || !$currentInstance->existed) {
        echo 'noCalendarEvent';
        exit();
    }

	// Get the parent calendarEvent
	require_once '../../../../../../lib/table/calendarEvent.php';
	$parentCalendarEvent = new calendarEvent($currentInstance->calendarEventId);
    if ($parentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId'] || !$parentCalendarEvent->existed) {
        echo 'unauthorized';
        exit();
    }

    // make a completedCalendarEvent with the info of the currentInstance
	require_once '../../../../../../lib/table/completedCalendarEvent.php';
	$completedCalendarEvent = new completedCalendarEvent();
	// Echo the completedCalendarEventId
	echo $completedCalendarEvent->completedCalendarEventId.':::';

	$completedCalendarEvent->linkedToCalendarEventId = $currentInstance->calendarEventId;
	$completedCalendarEvent->linkedToContactId = $currentInstance->linkedToContactId;
	$completedCalendarEvent->linkedToPropertyId = $currentInstance->linkedToPropertyId;

	// Grab data for contact name
	if ($currentInstance->linkedToContactId !== NULL) {
		require_once '../../../../../../lib/table/contact.php';
		$contactInfo = new contact($currentInstance->linkedToContactId);
		$completedCalendarEvent->contactFirstName = $contactInfo->firstName;
		$completedCalendarEvent->contactLastName = $contactInfo->lastName;
	}

	// Grab data for property address
	if ($currentInstance->linkedToPropertyId !== NULL) {
		require_once '../../../../../../lib/table/property.php';
		$propertyInfo = new property($currentInstance->linkedToPropertyId);
		$completedCalendarEvent->propertyAddress1 = $propertyInfo->address1;
		$completedCalendarEvent->propertyAddress2 = $propertyInfo->address2;
		$completedCalendarEvent->propertyCity = $propertyInfo->city;
		$completedCalendarEvent->propertyState = $propertyInfo->state;
		$completedCalendarEvent->propertyZipCode = $propertyInfo->zipCode;
	}
	
	$completedCalendarEvent->name = $currentInstance->name;
	$completedCalendarEvent->description = $currentInstance->description;
	$completedCalendarEvent->privateNotes = $currentInstance->privateNotes;
	$completedCalendarEvent->price = $currentInstance->price;
	$completedCalendarEvent->estHours = $currentInstance->estHours;
	$completedCalendarEvent->isPrepaid = $currentInstance->isPrepaid;
	$completedCalendarEvent->frequencyInterval = $parentCalendarEvent->frequencyInterval;
	$completedCalendarEvent->frequency = $parentCalendarEvent->frequency;
	$completedCalendarEvent->weekday = $parentCalendarEvent->weekday;
	$completedCalendarEvent->startDateTime = $currentInstance->startDateTime;
	$completedCalendarEvent->endDateTime = $currentInstance->endDateTime;
	$completedCalendarEvent->instanceDate = $_POST['instanceDate'];

	// Check if it is a valid instance date
	// require_once '../../../../../../lib/etc/time/getRecurringDates.php';
	// $calendarEventInstancesCheck = getRecurringDates($currentInstance->startDateTime, $currentInstance->endDateTime, $currentInstance->startDateTime, $_POST['instanceDate'], $parentCalendarEvent->frequencyInterval, $parentCalendarEvent->frequency, $parentCalendarEvent->weekday);
	// if (in_array($_POST['instanceDate'], $calendarEventInstancesCheck)) {
	// 	$completedCalendarEvent->instanceDate = $_POST['instanceDate'];
	// } else {
	// 	echo 'instanceDate';
	// 	exit();
	// }

	if (!$completedCalendarEvent->set()) {
		echo 'completedCalendarEventSetError';
		exit();
	}

	// Set the instance to isCompleted

	$currentInstance->isCompleted = true;
	$currentInstance->isRescheduled = false;
	$currentInstance->isCancelled = false;

	if (!$currentInstance->set()) {
		echo 'currentInstanceSetError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['completeCalendarEventInstanceAuthToken'], 'completeCalendarEventInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
