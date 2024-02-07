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

	if (!isset($_POST['contactId'])) {
		echo 'error (3)';
		exit();
	}

	require_once '../../../../../lib/database.php';
	$db = new database();
	require_once '../../../../../lib/table/contact.php';
	require_once '../../../../../lib/table/calendarEvent.php';
	require_once '../../../../../lib/render/input/propertySelector.php';
	
	$currentContact = new contact($_POST['contactId']);
	$currentCalendarEvent = new calendarEvent($_POST['calendarEventId']);

	if (!$currentContact->existed || !$currentCalendarEvent->existed) {
		echo 'error (4)';
		exit();
	}

	if ($currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
		echo "unauthorized";
		exit();
	}

	echo '<label for="propertySelector"><p>Property</p></label>';

	$propertySelector = new propertySelector("propertySelector", ["name" => 'property', "queryParams" => "AND contactId = '".$db->sanitize($_POST['contactId'])."'", "selectedId" => $currentCalendarEvent->linkedToPropertyId]);
	$propertySelector->render();
	echo $propertySelector->output;

	echo '<span id="contactError" class="underInputError" style="display: none;"><br>Select a property.</span>';

?>