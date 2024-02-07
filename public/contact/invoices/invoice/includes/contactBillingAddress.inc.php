<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an contact is logged in

	if (!isset($_SESSION['lifems_contactId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['contactId'])) {
		echo 'error (2)';
		exit();
	}

	require_once '../../../../../lib/table/contact.php';
	$contact = new contact($_POST['contactId']);

	if (!$contact->existed || $contact->workspaceId != $_SESSION['lifems_workspaceId']) {
		exit();
    }

	$billAddress = '';

	$contactName = '<b>';

	if ($contact->lastName != NULL) {
		$contactName .= htmlspecialchars($contact->firstName).' '.htmlspecialchars($contact->lastName);
	} else {
		$contactName .= htmlspecialchars($contact->firstName);
	}

	$contactName .= '</b><br>';

	if (!empty($contact->billAddress1)) {
		$billAddress .= htmlspecialchars($contact->billAddress1);
		if (!empty($contact->billAddress2) || !empty($contact->billState) || !empty($contact->billCity) || !empty($contact->billZipCode)) {
			$billAddress .= '<br>';
		}
	}
	if (!empty($contact->billAddress2)) {
		$billAddress .= htmlspecialchars($contact->billAddress2);
		if (!empty($contact->billCity) || !empty($contact->billState) || !empty($contact->billZipCode)) {
			$billAddress .= '<br>';
		}
	}
	if (!empty($contact->billCity)) {
		$billAddress .= htmlspecialchars($contact->billCity);
		if (!empty($contact->billState)) {
			$billAddress .= ', ';
		}
	}
	if (!empty($contact->billState)) {
		$billAddress .= htmlspecialchars($contact->billState);
	}
	if (!empty($contact->billZipCode)) {
		$billAddress .= ' '.htmlspecialchars($contact->billZipCode).'';
	}

	if ($billAddress == '') {
		$billAddress = '<span style="color: red;">Your billing address is not on file.</span>';
	}

	echo $contactName;
	echo $billAddress

?>
