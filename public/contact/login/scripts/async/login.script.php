<?php

    require_once '../../../../php/startSession.php';

    $matchedContactId = NULL;

    require_once '../../../../../lib/database.php';
    require_once '../../../../../lib/table/workspace.php';

    function logAttempt($result, $matchedContact) {
        // Log the attempt
        require_once '../../../../../lib/table/contactLoginAttempt.php';
        $attempt = new contactLoginAttempt();
        if (isset($matchedContact) && !empty($matchedContact)) {
            $attempt->contactId = $matchedContact;
        }
        $attempt->result = $result;
        $attempt->set();
    }

    if (!isset($_POST['formData'])) {
        logAttempt('noData', $matchedContactId);
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'contactLogin')) {
        logAttempt('tokenInvalid', $matchedContactId);
		echo 'tokenInvalid';
		exit();
	} else {
        // Since this script is run relatively often, purge old authTokens to keep the table small whenever the token is valid
        require_once '../../../../../lib/etc/authToken/purgeAuthTokens.php';
        purgeAuthTokens($lifemsConfig['authTokenDefaultPurge']);
    }

    // Check if input is set and valid

	if (!isset($_POST['workspaceId']) || empty($_POST['workspaceId'])) {
        logAttempt('workspaceId', $matchedContactId);
		echo 'workspaceId';
		exit();
	}

    $currentWorkspace = new workspace($_POST['workspaceId']);
    if (!$currentWorkspace->existed) {
        logAttempt('workspaceId', $matchedContactId);
		echo 'workspaceId';
		exit();
    }

	if (!isset($formData['password']) || empty($formData['password'])) {
        logAttempt('password', $matchedContactId);
		echo 'password';
		exit();
	}

    $db = new database();
    $passwordSan = $db->sanitize($formData['password']);

    $currentWorkspace->pullContacts("AND password = '$passwordSan'");

    if (count($currentWorkspace->contacts) !== 1) {
        logAttempt('noContact', $matchedContactId);
        echo 'noContact';
        exit();
    }

    // Get the contact as an object
    require_once '../../../../../lib/table/contact.php';
    $matchedContact = new contact($currentWorkspace->contacts[0]);
    if (!$matchedContact->existed || $matchedContact->workspaceId != $currentWorkspace->workspaceId) {
        logAttempt('noContact', $matchedContactId);
        echo 'noContact';
        exit();
    }

    $matchedContactId = $matchedContact->contactId;

    //Check if the username/email and password match
    // Login the user
    $_SESSION['lifems_contactId'] = $matchedContactId;
    $_SESSION['lifems_workspaceId'] = $matchedContact->workspaceId;

    setcookie('lifems_lastWorkspaceId', $matchedContact->workspaceId, time() + (86400 * 30), "/");

    logAttempt('success', $matchedContactId);

    // Use the auth token
    require_once '../../../../../lib/etc/authToken/useAuthToken.php';
    useAuthToken($formData['authToken'], 'contactLogin');

    echo 'success';
    exit();

    // if remember me is checked, save the login and set a cookie
    if (false) { // Will do later
        // Set a saved login
    }

?>
