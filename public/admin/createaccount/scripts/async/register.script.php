<?php

	require_once '../../../../php/startSession.php';

	require_once '../../../../../lib/table/admin.php';
	$admin = new admin();

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'adminRegister')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid

	if (!isset($formData['firstName']) || strlen($formData['firstName']) < (int)$lifemsConfig['firstNameMinLength']) {
		echo 'firstName';
		exit();
	}
	$admin->firstName = $formData['firstName'];

	if (!isset($formData['lastName']) || strlen($formData['lastName']) < (int)$lifemsConfig['lastNameMinLength']) {
		echo 'lastName';
		exit();
	}
	$admin->lastName = $formData['lastName'];
	
	if (!isset($formData['email']) || strlen($formData['email']) < (int)$lifemsConfig['emailMinLength']) {
		echo 'email';
		exit();
	}

	// If enabled, use filter function to make sure email is an actual email
	if ($lifemsConfig['useEmailValidation']) {
		if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
			echo 'emailValidity';
			exit();
		}
	}
	$admin->email = $formData['email'];

	if (!isset($formData['username']) || strlen($formData['username']) < (int)$lifemsConfig['usernameMinLength']) {
		echo 'username';
		exit();
	}
	$admin->username = $formData['username'];

	if (!isset($formData['password']) || strlen($formData['password']) < (int)$lifemsConfig['passwordMinLength']) {
		echo 'password';
		exit();
	}
	$admin->password = password_hash($formData['password'], PASSWORD_DEFAULT);

	// if (!isset($formData['nameTitle'])) {
	// 	$nameTitle = '';
	// } elseif ($formData['nameTitle'] == '') {
	// 	if (strlen($formData['nameTitle']) < (int)$lifemsConfig['nameTitleMinLength']) {
	// 		echo 'nameTitle';
	// 		exit();
	// 	}
	// }

	require_once '../../../../../lib/database.php'; $db = new database();

	// Check for the email in the database

	if ($db->select('admin', 'email', "WHERE LOWER(email) = '".strtolower($db->sanitize($formData['email']))."'")) {
		echo 'emailExists';
		exit();
	}

	// Check for the username in the database

	if ($db->select('admin', 'username', "WHERE LOWER(username) = '".strtolower($db->sanitize($formData['username']))."'")) {
		echo 'usernameExists';
		exit();
	}

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['authToken'], 'adminRegister');

	if (!$admin->set()) {
		echo 'setError';
		exit();
	}

	echo 'success';
	exit();

?>
