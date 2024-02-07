<?php

    require_once '../../../../php/startSession.php';

    $matchedAdminId = NULL;

    function logAttempt($result, $matchedAdmin) {
        // Log the attempt
        require_once '../../../../../lib/table/adminLoginAttempt.php';
        $attempt = new adminLoginAttempt();
        if (isset($matchedAdmin) && !empty($matchedAdmin)) {
            $attempt->adminId = $matchedAdmin;
        }
        $attempt->result = $result;
        $attempt->set();
    }

    if (!isset($_POST['formData'])) {
        logAttempt('noData', $matchedAdminId);
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'adminLogin')) {
        logAttempt('tokenInvalid', $matchedAdminId);
		echo 'tokenInvalid';
		exit();
	} else {
        // Since this script is run relatively often, purge old authTokens to keep the table small whenever the token is valid
        require_once '../../../../../lib/etc/authToken/purgeAuthTokens.php';
        purgeAuthTokens($lifemsConfig['authTokenDefaultPurge']);
    }

    // Check if input is set and valid

	if (!isset($formData['usernameEmail']) || empty($formData['usernameEmail'])) {
        logAttempt('usernameEmail', $matchedAdminId);
		echo 'usernameEmail';
		exit();
	}

	if (!isset($formData['password']) || empty($formData['password'])) {
        logAttempt('password', $matchedAdminId);
		echo 'password';
		exit();
	}

    // If the username or email exists, get the adminId associated with them
    require_once '../../../../../lib/database.php';
    $db = new database();
    $usernameCheck = $db->select('admin', 'adminId', "WHERE LOWER(username) = '".strtolower($db->sanitize($formData['usernameEmail']))."' LIMIT 1");
    $emailCheck = $db->select('admin', 'adminId', "WHERE LOWER(email) = '".strtolower($db->sanitize($formData['usernameEmail']))."' LIMIT 1");

    // Check if email or username exists
    if (!$usernameCheck && !$emailCheck) {
        logAttempt('noUsernameEmail', $matchedAdminId);
        echo 'noUsernameEmail';
        exit();
    }

    if ($usernameCheck) {
        $matchedAdminId = $usernameCheck[0]['adminId'];
    } else {
        $matchedAdminId = $emailCheck[0]['adminId'];
    }

    // Get the admin as an object
    require_once '../../../../../lib/table/admin.php';
    $matchedAdmin = new admin($matchedAdminId);
    if (!$matchedAdmin->existed) {
        logAttempt('noUsernameEmail', $matchedAdminId);
        echo 'noUsernameEmail';
        exit();
    }

    //Check if the username/email and password match
    if (password_verify($formData['password'], $matchedAdmin->password)) {
        // Login the user
        $_SESSION['lifems_adminId'] = $matchedAdminId;

        logAttempt('success', $matchedAdminId);

        // Use the auth token
        require_once '../../../../../lib/etc/authToken/useAuthToken.php';
        useAuthToken($formData['authToken'], 'adminLogin');

        echo 'success';
        exit();

        // if remember me is checked, save the login and set a cookie
        if (false) { // Will do later
            // Set a saved login
        }
    } else {
        logAttempt('noMatch', $matchedAdminId);
        echo 'noMatch';
        exit();
    }

?>
