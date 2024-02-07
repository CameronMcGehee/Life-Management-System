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
	if (!isset($_POST['cancelCalendarEventAuthToken']) || !validateAuthToken($_POST['cancelCalendarEventAuthToken'], 'cancelCalendarEvent')) {
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

	// If it is a singular calendarEvent set it to cancelled. If it is recurring, make an instance exception for that instance.

	//Make an instance exception for this instance

	if ($currentCalendarEvent->frequencyInterval == 'none') {

		echo $currentCalendarEvent->calendarEventId.':::';

		$currentCalendarEvent->isCancelled = '1';

		if (!$currentCalendarEvent->set()) {
			echo 'calendarEventSetError';
			exit();
		}
	} else {
		require_once '../../../../../../lib/table/calendarEventInstanceException.php';
		$calendarEventInstanceException = new calendarEventInstanceException();
	
		echo $calendarEventInstanceException->calendarEventInstanceExceptionId.':::';
	
		$calendarEventInstanceException->calendarEventId = $currentCalendarEvent->calendarEventId;
		$calendarEventInstanceException->instanceDate = $_POST['instanceDate'];
		$calendarEventInstanceException->isRescheduled = '0';
		$calendarEventInstanceException->isCancelled = '1';
		$calendarEventInstanceException->isCompleted = '0';
		$calendarEventInstanceException->linkedToCompletedCalendarEventId = NULL;
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
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['cancelCalendarEventAuthToken'], 'cancelCalendarEvent');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
