<?php

	require_once '../../../../php/startSession.php';
	require_once '../../../../../lib/table/note.php';
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	require_once '../../../../../lib/table/workspace.php';

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	if (!isset($_POST['noteId'])) {
		echo 'noId';
		exit();
	}

	$currentNote = new note($_POST['noteId']);
	if (!$currentNote->existed) {
		var_dump($_POST['noteId']);
		echo 'idNotFound';
		exit();
	}

	// Make sure that local access to this note has been granted
	if (!isset($_SESSION['lifems_noteAccess'])) {
		echo 'unauthorized';
		exit();
	} else if (gettype($_SESSION['lifems_noteAccess']) !== 'array') {
		echo 'unauthorized';
		exit();
	} else if (!in_array([$currentNote->noteId, 'edit', $currentNote->editPass], $_SESSION['lifems_noteAccess'])) {
		echo 'unauthorized';
		exit();
	}

	echo $currentNote->noteId.':::';

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editNote')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
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

	// Use the auth token
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
