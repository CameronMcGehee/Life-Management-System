<?php

	require_once '../../../php/startSession.php';

	// Error checking
	// Input is set and valid
	
	if (!isset($_POST['email']) || strlen($_POST['email']) < (int)$ULTISCAPECONFIG['emailMinLength']) {
		header("location: ../../register?e=emailInput");
		exit();
	}

	// If enabled, use filter function to make sure email is an actual email
	if ($ULTISCAPECONFIG['useEmailValidation']) {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			header("location: ../../register?e=emailInputValidity");
			exit();
		}
	}

	if (!isset($_POST['firstName']) || strlen($_POST['firstName']) < (int)$ULTISCAPECONFIG['firstNameMinLength']) {
		header("location: ../../register?e=firstNameInput");
		exit();
	}
	if (!isset($_POST['lastName']) || strlen($_POST['lastName']) < (int)$ULTISCAPECONFIG['lastNameMinLength']) {
		header("location: ../../register?e=lastNameInput");
		exit();
	}

	if (!isset($_POST['username']) || strlen($_POST['username']) < (int)$ULTISCAPECONFIG['usernameMinLength']) {
		header("location: ../../register?e=usernameInput");
		exit();
	}

	if (!isset($_POST['password']) || strlen($_POST['password']) < (int)$ULTISCAPECONFIG['passwordMinLength']) {
		header("location: ../../register?e=passwordInput");
		exit();
	}

	if (!isset($_POST['nameTitle'])) {
		$nameTitle = '';
	} elseif ($_POST['nameTitle'] == '') {
		if (strlen($_POST['nameTitle']) < (int)$ULTISCAPECONFIG['nameTitleMinLength']) {
			header("location: ../../register?e=nameTitleInput");
			exit();
		}
	}

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (/* Username already in use */ true) {
		header("location: ../../register?e=usernameExists");
		exit();
	} elseif (/* Email already in use */ true) {
		header("location: ../../register?e=emailExists");
		exit();
	} else {
		// Add the admin to the database
		require_once '../../../../lib/table/admin.php';
		$newAdmin = new admin();
		$newAdmin->firstName = $_POST['firstName'];
		$newAdmin->lastName = $_POST['lastName'];
		$newAdmin->username = $_POST['username'];
		$newAdmin->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		if ($newAdmin->set()) {
			header("location: ../../login?username=".$newAdmin->adminId);
			exit();
		} else {
			header("location: ../../register?e=unknown");
			exit();
		}
	}

	header("location: ../../");
	exit();

?>
