<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['contacts']) || gettype($_POST['contacts']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'deleteContacts')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/table/contact.php';

    foreach ($_POST['contacts'] as $contactId) {
        // Make sure that the contactId exists
        $currentContact = new contact($contactId);
        if (!$currentContact->existed) {
            echo 'noContact';
            die();
        }
        // Check if current workspace has access to that contact
        if ($currentContact->workspaceId !== $_SESSION['lifems_workspaceId']) {
            echo 'unauthorized';
            die();
        }
        // delete the contact
        $currentContact->delete();
    }

    echo 'success';

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteContacts');

?>
