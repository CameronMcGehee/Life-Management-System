<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

	require_once '../../../../../lib/table/business.php';
	$business = new business($_SESSION['ultiscape_businessId']);
	if (!$business->existed) {
		echo 'businessDoesntExist';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'editBusiness')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// displayName
	if (!isset($formData['displayName']) || strlen($formData['displayName']) < $ULTISCAPECONFIG['businessNameMinLength'] || strlen($formData['displayName']) > $ULTISCAPECONFIG['businessNameMaxLength']) {
		echo 'displayName';
		exit();
	}
	$business->displayName = $formData['displayName'];

	// adminDisplayName
	if (!isset($formData['adminDisplayName']) || strlen($formData['adminDisplayName']) < $ULTISCAPECONFIG['businessNameMinLength'] || strlen($formData['adminDisplayName']) > $ULTISCAPECONFIG['businessNameMaxLength']) {
		echo 'adminDisplayName';
		exit();
	}
	$business->adminDisplayName = $formData['adminDisplayName'];

	// address1
	if (!isset($formData['address1'])) {
		echo 'address1';
		exit();
	}
	if (empty($formData['address1'])) {
		$business->address1 = NULL;
	} else {
		$business->address1 = $formData['address1'];
	}

	// address2
	if (!isset($formData['address2'])) {
		echo 'address2';
		exit();
	}
	if (empty($formData['address2'])) {
		$business->address2 = NULL;
	} else {
		$business->address2 = $formData['address2'];
	}

	// city
	if (!isset($formData['city'])) {
		echo 'city';
		exit();
	}
	if (empty($formData['city'])) {
		$business->city = NULL;
	} else {
		$business->city = $formData['city'];
	}

	// state
	if (!isset($formData['state'])) {
		echo 'state';
		exit();
	}
	if (empty($formData['state'])) {
		$business->state = NULL;
	} else {
		$business->state = $formData['state'];
	}

	// zipCode
	if (!isset($formData['zipCode'])) {
		echo 'zipCode';
		exit();
	}
	if (empty($formData['zipCode']) || !is_numeric($formData['zipCode'])) {
		$business->zipCode = NULL;
	} else {
		$business->zipCode = (int)$formData['zipCode'];
	}

	// phonePrefix
	if (!isset($formData['phonePrefix'])) {
		echo 'phonePrefix';
		exit();
	}
	if (empty($formData['phonePrefix']) || !is_numeric($formData['phonePrefix'])) {
		$business->phonePrefix = NULL;
	} else {
		$business->phonePrefix = (int)$formData['phonePrefix'];
	}

	// phone1
	if (!isset($formData['phone1'])) {
		echo 'phone1';
		exit();
	}
	if (empty($formData['phone1']) || !is_numeric($formData['phone1'])) {
		$business->phone1 = NULL;
	} else {
		$business->phone1 = (int)$formData['phone1'];
	}

	// phone2
	if (!isset($formData['phone2'])) {
		echo 'phone2';
		exit();
	}
	if (empty($formData['phone2']) || !is_numeric($formData['phone2'])) {
		$business->phone2 = NULL;
	} else {
		$business->phone2 = (int)$formData['phone2'];
	}

	// phone3
	if (!isset($formData['phone3'])) {
		echo 'phone3';
		exit();
	}
	if (empty($formData['phone3']) || !is_numeric($formData['phone3'])) {
		$business->phone3 = NULL;
	} else {
		$business->phone3 = (int)$formData['phone3'];
	}

	// timeZone
	if (!isset($formData['timeZone'])) {
		echo 'timeZone';
		exit();
	}
	if (empty($formData['timeZone'])) {
		$business->timeZone = 'UTC';
	} else {
		$business->timeZone = $formData['timeZone'];
	}

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	if (!useAuthToken($formData['authToken'], 'editBusiness')) {
		echo 'tokenInvalid';
		exit();
	}

	if ($business->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Redirect
	echo 'success';
	exit();

?>
