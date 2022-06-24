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

	// Upload logo file (NF)

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

	// currencySymbol
	if (!isset($formData['currencySymbol']) || empty($formData['currencySymbol']) || strlen($formData['currencySymbol']) > 1) {
		echo 'currencySymbol';
		exit();
	} else {
		$business->currencySymbol = $formData['currencySymbol'];
	}

	// areaSymbol
	if (!isset($formData['areaSymbol']) || empty($formData['areaSymbol']) || strlen($formData['areaSymbol']) > 4) {
		echo 'areaSymbol';
		exit();
	} else {
		$business->areaSymbol = $formData['areaSymbol'];
	}

	// distanceSymbol
	if (!isset($formData['distanceSymbol']) || empty($formData['distanceSymbol']) || strlen($formData['distanceSymbol']) > 4) {
		echo 'distanceSymbol';
		exit();
	} else {
		$business->distanceSymbol = $formData['distanceSymbol'];
	}

	// creditAlertIsEnabled
	if (!isset($formData['creditAlertIsEnabled'])) {
		$business->creditAlertIsEnabled = '0';
	} else {
		$business->creditAlertIsEnabled = '1';
	}

	// creditAlertAmount
	if (!isset($formData['creditAlertAmount']) || !is_numeric($formData['creditAlertAmount'])) {
		echo 'creditAlertAmount';
		exit();
	}
	if (empty($formData['creditAlertAmount'])) {
		$business->creditAlertAmount = '0';
	} else {
		$business->creditAlertAmount = (float)number_format($formData['creditAlertAmount'], 2);
	}

	// balanceAlertIsEnabled
	if (!isset($formData['balanceAlertIsEnabled'])) {
		$business->balanceAlertIsEnabled = '0';
	} else {
		$business->balanceAlertIsEnabled = '1';
	}

	// balanceAlertAmount
	if (!isset($formData['balanceAlertAmount']) || !is_numeric($formData['balanceAlertAmount'])) {
		echo 'balanceAlertAmount';
		exit();
	}
	if (empty($formData['balanceAlertAmount'])) {
		$business->balanceAlertAmount = '0';
	} else {
		$business->balanceAlertAmount = (float)number_format($formData['balanceAlertAmount'], 2);
	}

	// modPayrSalDefaultType
	if (!isset($formData['modPayrSalDefaultType']) || !in_array($formData['modPayrSalDefaultType'], array('none', 'hourly', 'aPerJob', 'pPerJob'))) {
		echo 'modPayrSalDefaultType';
		exit();
	} else {
		$business->modPayrSalDefaultType = $formData['modPayrSalDefaultType'];
	}

	// modPayrSalBaseHourlyRate
	if (!isset($formData['modPayrSalBaseHourlyRate']) || !is_numeric($formData['modPayrSalBaseHourlyRate'])) {
		echo 'modPayrSalBaseHourlyRate';
		exit();
	}
	if (empty($formData['modPayrSalBaseHourlyRate'])) {
		$business->modPayrSalBaseHourlyRate = '0';
	} else {
		$business->modPayrSalBaseHourlyRate = (float)number_format($formData['modPayrSalBaseHourlyRate'], 2);
	}

	// modPayrSalBasePerJob
	if (!isset($formData['modPayrSalBasePerJob']) || !is_numeric($formData['modPayrSalBasePerJob'])) {
		echo 'modPayrSalBasePerJob';
		exit();
	}
	if (empty($formData['modPayrSalBasePerJob'])) {
		$business->modPayrSalBasePerJob = '0';
	} else {
		$business->modPayrSalBasePerJob = (float)number_format($formData['modPayrSalBasePerJob'], 2);
	}

	// modPayrSalBaseJobPercent
	if (!isset($formData['modPayrSalBaseJobPercent']) || !is_numeric($formData['modPayrSalBaseJobPercent'])) {
		echo 'modPayrSalBaseJobPercent';
		exit();
	}
	if (empty($formData['modPayrSalBaseJobPercent'])) {
		$business->modPayrSalBaseJobPercent = '0';
	} else {
		$business->modPayrSalBaseJobPercent = (float)number_format($formData['modPayrSalBaseJobPercent'], 2);
	}

	// docIdMin
	if (!isset($formData['docIdMin']) || !is_numeric($formData['docIdMin'])) {
		echo 'docIdMin';
		exit();
	}
	if (empty($formData['docIdMin'])) {
		$business->docIdMin = '0';
	} else {
		$business->docIdMin = (int)$formData['docIdMin'];
	}

	// docIdIsRandom
	if (!isset($formData['docIdIsRandom'])) {
		$business->docIdIsRandom = '0';
	} else {
		$business->docIdIsRandom = '1';
	}

	// invoiceTerm
	if (!isset($formData['invoiceTerm'])) {
		echo 'invoiceTerm';
		exit();
	}
	if (empty($formData['invoiceTerm']) || (int)$formData['invoiceTerm'] < 1) {
		$business->invoiceTerm = NULL;
	} else {
		$business->invoiceTerm = (int)$formData['invoiceTerm'];
	}

	// autoApplyCredit
	if (!isset($formData['autoApplyCredit'])) {
		$business->autoApplyCredit = '0';
	} else {
		$business->autoApplyCredit = '1';
	}

	// Payment Methods
	require_once '../../../../../lib/table/paymentMethod.php';
	if (!empty($formData['paymentMethodId'])) {
		foreach($formData['paymentMethodId'] as $methodNum => $paymentMethodId) {
			// check if the id exists, if it doesn't just don't bother since they are messing with the code
			$currentPaymentMethod = new paymentMethod($paymentMethodId);
			if ($currentPaymentMethod->existed && $currentPaymentMethod->businessId == $business->businessId) {
				// Update it
				$currentPaymentMethod->name = $formData['paymentMethodName'][$methodNum];
				$currentPaymentMethod->amountCut = round($formData['paymentMethodAmountCut'][$methodNum], 2);
				$currentPaymentMethod->percentCut = $formData['paymentMethodPercentCut'][$methodNum];
				$currentPaymentMethod->notes = $formData['paymentMethodNotes'][$methodNum];
				
				$currentPaymentMethod->set();
			}
		}
	}

	// estimateValidity
	if (!isset($formData['estimateValidity'])) {
		echo 'estimateValidity';
		exit();
	}
	if (empty($formData['estimateValidity']) || (int)$formData['estimateValidity'] < 1) {
		$business->estimateValidity = NULL;
	} else {
		$business->estimateValidity = (int)$formData['estimateValidity'];
	}

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['authToken'], 'editBusiness');

	if ($business->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Redirect
	echo 'success';
	exit();

?>
