<?php

    // This script takes an array as input: [['noteId1', 'noteTagId1'], ['noteId2', 'noteTagId2'], etc.]. If not in this format, error will be thrown.

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
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
	if (!validateAuthToken($_POST['authToken'], 'deleteNoteTagLinks')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/database.php';

    foreach ($_POST['noteTagLinks'] as $tagLink) {
        // Make sure that the bridge entry exists
        $db = new database();
        $select = $db->select('noteNoteTagBridge', 'noteNoteTagId', "WHERE noteId = '".$db->sanitize($tagLink[0])."' AND noteTagId = '".$db->sanitize($tagLink[1])."'");
        if (!$select) {
            echo 'noLink';
            die();
        } else if (count($select) !== 1) {
            echo 'unknown: '.$db->getLastError();
            die();
        }
        // delete the link
        if ($db->delete('noteNoteTagBridge', "WHERE businessId = '".$_SESSION['ultiscape_businessId']."' AND noteId = '".$db->sanitize($tagLink[0])."' AND noteTagId = '".$db->sanitize($tagLink[1])."'", 1)) {
            echo 'success';
        } else {
            echo 'deleteError';
            die();
        }
    }

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteNoteTagLinks');

?>
