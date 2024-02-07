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
	if (!isset($_POST['cancelCalendarEventInstanceAuthToken']) || !validateAuthToken($_POST['cancelCalendarEventInstanceAuthToken'], 'cancelCalendarEventInstance')) {
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

	echo $currentInstance->calendarEventInstanceExceptionId.':::';

	// Set the instance to isCancelled

	$currentInstance->isCompleted = false;
	$currentInstance->isRescheduled = false;
	$currentInstance->isCancelled = true;

	if (!$currentInstance->set()) {
		echo 'currentInstanceSetError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['cancelCalendarEventInstanceAuthToken'], 'cancelCalendarEventInstance');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
