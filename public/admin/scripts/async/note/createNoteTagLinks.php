<?php

    // This script takes an array as input: [['noteId1', 'noteTagId1'], ['noteId2', 'noteTagId2'], etc.]. If not in this format, error will be thrown.

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['noteTagLinks']) || gettype($_POST['noteTagLinks']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    foreach ($_POST['noteTagLinks'] as $tagLink) {
        if (empty($tagLink[0]) || empty($tagLink[1])) {
            echo 'inputInvalid';
            die();
        }
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'createNoteTagLinks')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/database.php';

    foreach ($_POST['noteTagLinks'] as $tagLink) {
        // Make sure that the bridge entry doesn't already exist
        $db = new database();
        $select = $db->select('noteNoteTagBridge', 'noteNoteTagId', "WHERE noteId = '".$db->sanitize($tagLink[0])."' AND noteTagId = '".$db->sanitize($tagLink[1])."'");
        if ($select) {
            echo 'alreadyExists';
            die();
        }
        // create the link
        if ($db->insert('noteNoteTagBridge', ['workspaceId' => $_SESSION['lifems_workspaceId'], 'noteId' => $db->sanitize($tagLink[0]), 'noteTagId' => $db->sanitize($tagLink[1]))) {
            echo 'success';
        } else {
            echo 'insertError';
            die();
        }
    }

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'createNoteTagLinks');

?>
