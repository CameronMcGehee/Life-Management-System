<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['completedCalendarEventId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['uncompleteCompletedCalendarEventAuthToken']) || !validateAuthToken($_POST['uncompleteCompletedCalendarEventAuthToken'], 'uncompleteCompletedCalendarEvent')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the calendarEvent belongs to the workspace that is signed in
    require_once '../../../../../../lib/table/completedCalendarEvent.php';
	$currentCalendarEvent = new completedCalendarEvent($_POST['completedCalendarEventId']);
    if (!$currentCalendarEvent->existed || $currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noCalendarEvent';
        exit();
    }

    require_once '../../../../../../lib/table/calendarEvent.php';
	if (!empty($currentCalendarEvent->linkedToCalendarEventId)) {
		$linkedCalendarEvent = new calendarEvent($currentCalendarEvent->linkedToCalendarEventId);
	}

	// If it is linked to a singular calendarEvent (no calendarEvent at all since it was deleted), create a new calendarEvent with all the same details as if it was still there

	if ($currentCalendarEvent->frequencyInterval == 'none') {
		require_once '../../../../../../lib/table/calendarEvent.php';
		require_once '../../../../../../lib/table/contact.php';
		require_once '../../../../../../lib/table/property.php';
		$newCalendarEvent = new calendarEvent();

		// In case the contact or property has been deleted since the time of calendarEvent completion

		if (!empty($currentCalendarEvent->linkedToContactId)) {
			$currentContact = new contact($currentCalendarEvent->linkedToContactId);
			if ($currentContact->existed) {
				$newCalendarEvent->linkedToContactId = $currentCalendarEvent->linkedToContactId;
			}
		}

		if (!empty($currentCalendarEvent->linkedToPropertyId)) {
			$currentProperty = new property($currentCalendarEvent->linkedToPropertyId);
			if ($currentProperty->existed) {
				$newCalendarEvent->linkedToPropertyId = $currentCalendarEvent->linkedToPropertyId;
			}
		}

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
		
		if (!$newCalendarEvent->set()) {
			echo 'newCalendarEventSetError';
			exit();
		}

		echo $newCalendarEvent->calendarEventId.':::calendarEvent';

	} else {
		// If it is linked to a recurring calendarEvent remove the instance exception for this completed calendarEvent's instance date
		require_once '../../../../../../lib/database.php';
		$db = new database();
		$select = $db->select('calendarEventInstanceException', 'calendarEventInstanceExceptionId', "WHERE calendarEventId = '$currentCalendarEvent->linkedToCalendarEventId' AND instanceDate = '$currentCalendarEvent->instanceDate'");
		if (!$select || count($select) !== 1) {
			// Not having the same instance date means that it was an instance that was rescheduled, so instead of deleting that instance exception, find it and change the bools from isCompleted to isRescheduled
			$select = $db->select('calendarEventInstanceException', 'calendarEventInstanceExceptionId', "WHERE calendarEventId = '$currentCalendarEvent->linkedToCalendarEventId' AND startDateTime = '$currentCalendarEvent->startDateTime'");
			if (!$select || count($select) !== 1) {
				echo 'noInstanceException';
				exit();
			}

			$instanceExceptionId = $select[0]['calendarEventInstanceExceptionId'];

			require_once '../../../../../../lib/table/calendarEventInstanceException.php';
			$instanceException = new calendarEventInstanceException($instanceExceptionId);
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

			echo $instanceException->calendarEventId.':::instance';

		} else {
			$instanceExceptionId = $select[0]['calendarEventInstanceExceptionId'];

			require_once '../../../../../../lib/table/calendarEventInstanceException.php';
			$instanceException = new calendarEventInstanceException($instanceExceptionId);

			echo $instanceException->calendarEventId.':::calendarEvent';

			if (!$instanceException->delete()) {
				echo 'instanceExceptionDeleteError';
				exit();
			}
		}
		
	}

    // Delete the calendarEvent (will cascade linked instance exceptions and such)
    if (!$currentCalendarEvent->delete()) {
        echo 'deleteError';
		exit();
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['uncompleteCompletedCalendarEventAuthToken'], 'uncompleteCompletedCalendarEvent');
	
	// Success if gotten to bottom of script
	exit();

?>
