<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['calendarEventId'])) {
		echo 'error (2)';
		exit();
	}

	require_once '../../../../../lib/table/calendarEvent.php';
	$currentCalendarEvent = new calendarEvent($_POST['calendarEventId']);

	if ($currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo "unauthorized";
		exit();
    }

    // Get all the emails in the system
    $currentCalendarEvent->pullCrews();

	// Render the list of email inputs and buttons
	require_once '../../../../../lib/table/crew.php';
    foreach ($currentCalendarEvent->crews as $crewId) {
        $crewInfo = new crew($crewId);
		echo '<p>'.$crewInfo->name.'</p>';
		echo ' <span id="deleteCalendarEventCrew:::'.htmlspecialchars($crewId).'" class="extraSmallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../../images/lifems/icons/cross.svg"></span>
		';
	}

    // One at the end to add a new email
	echo 'Select...';
	echo '<span id="newCrewsError" class="underInputError" style="display: none;"><br><br>Select a crew member from the list.</span>';

?>
