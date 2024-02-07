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

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteCalendarEventInstanceAuthToken']) || !validateAuthToken($_POST['deleteCalendarEventInstanceAuthToken'], 'deleteCalendarEventInstance')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the calendarEvent belongs to the workspace that is signed in
    require_once '../../../../../../lib/table/calendarEvent.php';
	$currentCalendarEvent = new calendarEvent($_POST['calendarEventId']);
    if (!$currentCalendarEvent->existed || $currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noCalendarEvent';
        exit();
    }

    // Delete the calendarEvent (will cascade linked instance exceptions and such)
    if (!$currentCalendarEvent->delete()) {
        echo 'deleteError';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteCalendarEventInstanceAuthToken'], 'deleteCalendarEventInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
