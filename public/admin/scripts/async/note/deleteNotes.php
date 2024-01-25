<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['notes']) || gettype($_POST['notes']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'deleteNotes')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/table/note.php';

    foreach ($_POST['notes'] as $noteId) {
        // Make sure that the noteId exists
        $currentNote = new note($noteId);
        if (!$currentNote->existed) {
            echo 'noNote';
            die();
        }
        // Check if current business has access to that note
        if ($currentNote->businessId !== $_SESSION['ultiscape_businessId']) {
            echo 'unauthorized';
            die();
        }
        // delete the note
        $currentNote->delete();
    }

    echo 'success';

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteNotes');

?>
