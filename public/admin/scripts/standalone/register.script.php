<?php

	require_once '../../../php/startSession.php';

	// Error checking
	// Input is set and valid
	
	if (!isset($_POST['email']) || strlen($_POST['email']) < (int)$ULTISCAPECONFIG['adminEmailMinLength']) {
		header("location: ../../register?e=emailInput");
		exit();
	}

	// If enabled, use filter function to make sure email is an actual email
	if ($ULTISCAPECONFIG['adminUseEmailValidation']) {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			header("location: ../../register?e=emailInputValidity");
			exit();
		}
	}

	if (!isset($_POST['firstName']) || strlen($_POST['firstName']) < (int)$ULTISCAPECONFIG['adminFirstNameMinLength']) {
		header("location: ../../register?e=firstNameInput");
		exit();
	}
	if (!isset($_POST['lastName']) || strlen($_POST['lastName']) < (int)$ULTISCAPECONFIG['adminLastNameMinLength']) {
		header("location: ../../register?e=lastNameInput");
		exit();
	}

	if (!isset($_POST['username']) || strlen($_POST['username']) < (int)$ULTISCAPECONFIG['adminUsernameMinLength']) {
		header("location: ../../register?e=usernameInput");
		exit();
	}

	if (!isset($_POST['password']) || strlen($_POST['password']) < (int)$ULTISCAPECONFIG['adminPasswordMinLength']) {
		header("location: ../../register?e=passwordInput");
		exit();
	}

	if (!isset($_POST['nameTitle'])) {
		$nameTitle = '';
	} elseif ($_POST['nameTitle'] == '') {
		if (strlen($_POST['nameTitle']) < (int)$ULTISCAPECONFIG['adminnameTitleMinLength']) {
			header("location: ../../register?e=nameTitleInput");
			exit();
		}
	}

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	require_once '../../../../lib/app/adminLoginManager.php';
	$adminLoginManager = new adminLoginManager();

	if ($adminLoginManager->usernameExists($_POST['usernameEmail'])) {
		header("location: ../../register?e=usernameExists");
		exit();
	} elseif ($adminLoginManager->emailExists($_POST['email'])) {
		header("location: ../../register?e=emailExists");
		exit();
	} elseif ($adminLoginManager->addAdmin(true)) { // NOT YET A FUNCTION
		header("location: ../../login"); //?username=".$createdAdminId);
		exit();
	}

	header("location: ../../");
	exit();

?>
