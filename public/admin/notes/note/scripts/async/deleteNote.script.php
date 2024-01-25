<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['noteId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteNoteAuthToken']) || !validateAuthToken($_POST['deleteNoteAuthToken'], 'deleteNote')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the note belongs to the business that is signed in
    require_once '../../../../../../lib/table/note.php';
	$currentNote = new note($_POST['noteId']);
    if (!$currentNote->existed || $currentNote->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noNote';
        exit();
    }

    // Delete the note
    if (!$currentNote->delete()) {
        echo 'deleteError';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteNoteAuthToken'], 'deleteNote');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
