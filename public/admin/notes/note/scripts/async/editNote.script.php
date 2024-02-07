<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	if (!isset($_POST['noteId'])) {
		echo 'noId';
		exit();
	}

	// Verify the note belongs to the workspace that is signed in
	require_once '../../../../../../lib/table/note.php';
	$currentNote = new note($_POST['noteId']);
	if ($currentNote->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noNote';
        exit();
    }

	echo $currentNote->noteId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editNote')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	require_once '../../../../../../lib/table/workspace.php';
	$currentWorkspace = new workspace($currentNote->workspaceId);

	// title
	if (!isset($formData['title']) || empty($formData['title']) || strlen(strval($formData['title'])) < 1) {
		echo 'title';
		exit();
	} else {
		$currentNote->title = $formData['title'];
	}

	// bodyMarkdown
	if (!isset($formData['bodyMarkdown']) || empty($formData['bodyMarkdown']) || strlen(strval($formData['bodyMarkdown'])) < 1) {
		$currentNote->bodyMarkdown = '';
		$currentNote->bodyHtml = '';
	} else {
		$currentNote->bodyMarkdown = $formData['bodyMarkdown'];
		$currentNote->bodyHtml = $currentNote->convertMarkdownToHtml();
	}

	// SHARING SETTINGS
	// viewprivacy
	switch ($formData['editViewPrivacy']) {
		case 'private':
			$currentNote->viewPrivacy = 'private';
			break;
		case 'password':
			$currentNote->viewPrivacy = 'password';
			break;
		case 'link':
			$currentNote->viewPrivacy = 'link';
			break;
		default:
			$currentNote->viewPrivacy = 'private';
			break;
	}

	// viewPass
	if (!isset($formData['editViewPass']) || empty($formData['editViewPass']) || strlen(strval($formData['editViewPass'])) < 1) {
		$currentNote->viewPass = NULL;
	} else {
		$currentNote->viewPass = $formData['editViewPass'];
	}

	// editPrivacy
	switch ($formData['editEditPrivacy']) {
		case 'private':
			$currentNote->editPrivacy = 'private';
			break;
		case 'password':
			$currentNote->editPrivacy = 'password';
			break;
		case 'link':
			$currentNote->editPrivacy = 'link';
			break;
		default:
			$currentNote->editPrivacy = 'private';
			break;
	}

	// editPass
	if (!isset($formData['editEditPass']) || empty($formData['editEditPass']) || strlen(strval($formData['editEditPass'])) < 1) {
		$currentNote->editPass = NULL;
	} else {
		$currentNote->editPass = $formData['editEditPass'];
	}
	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editNote');

	// Update the lastUpdate field
	$currentDateTime = new DateTime();
	$currentNote->lastUpdate = $currentDateTime->format('Y-m-d H:i:s');

	if ($currentNote->set() !== true) {
		echo 'setError';
		var_dump($currentNote->set());
		exit();
	}
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
