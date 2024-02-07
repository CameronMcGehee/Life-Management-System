<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	require_once '../../../../../lib/table/workspace.php';
	$workspace = new workspace($_SESSION['lifems_workspaceId']);
	if (!$workspace->existed) {
		echo 'workspaceDoesntExist';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'editWorkspace')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// displayName
	if (!isset($formData['displayName']) || strlen($formData['displayName']) < $lifemsConfig['workspaceNameMinLength'] || strlen($formData['displayName']) > $lifemsConfig['workspaceNameMaxLength']) {
		echo 'displayName';
		exit();
	}
	$workspace->displayName = $formData['displayName'];

	// adminDisplayName
	if (!isset($formData['adminDisplayName']) || strlen($formData['adminDisplayName']) < $lifemsConfig['workspaceNameMinLength'] || strlen($formData['adminDisplayName']) > $lifemsConfig['workspaceNameMaxLength']) {
		echo 'adminDisplayName';
		exit();
	}
	$workspace->adminDisplayName = $formData['adminDisplayName'];

	// Upload logo file (NF)

	// address1
	if (!isset($formData['address1'])) {
		echo 'address1';
		exit();
	}
	if (empty($formData['address1'])) {
		$workspace->address1 = NULL;
	} else {
		$workspace->address1 = $formData['address1'];
	}

	// address2
	if (!isset($formData['address2'])) {
		echo 'address2';
		exit();
	}
	if (empty($formData['address2'])) {
		$workspace->address2 = NULL;
	} else {
		$workspace->address2 = $formData['address2'];
	}

	// city
	if (!isset($formData['city'])) {
		echo 'city';
		exit();
	}
	if (empty($formData['city'])) {
		$workspace->city = NULL;
	} else {
		$workspace->city = $formData['city'];
	}

	// state
	if (!isset($formData['state'])) {
		echo 'state';
		exit();
	}
	if (empty($formData['state'])) {
		$workspace->state = NULL;
	} else {
		$workspace->state = $formData['state'];
	}

	// zipCode
	if (!isset($formData['zipCode'])) {
		echo 'zipCode';
		exit();
	}
	if (empty($formData['zipCode']) || !is_numeric($formData['zipCode'])) {
		$workspace->zipCode = NULL;
	} else {
		$workspace->zipCode = (int)$formData['zipCode'];
	}

	// phonePrefix
	if (!isset($formData['phonePrefix'])) {
		echo 'phonePrefix';
		exit();
	}
	if (empty($formData['phonePrefix']) || !is_numeric($formData['phonePrefix'])) {
		$workspace->phonePrefix = NULL;
	} else {
		$workspace->phonePrefix = (int)$formData['phonePrefix'];
	}

	// phone1
	if (!isset($formData['phone1'])) {
		echo 'phone1';
		exit();
	}
	if (empty($formData['phone1']) || !is_numeric($formData['phone1'])) {
		$workspace->phone1 = NULL;
	} else {
		$workspace->phone1 = (int)$formData['phone1'];
	}

	// phone2
	if (!isset($formData['phone2'])) {
		echo 'phone2';
		exit();
	}
	if (empty($formData['phone2']) || !is_numeric($formData['phone2'])) {
		$workspace->phone2 = NULL;
	} else {
		$workspace->phone2 = (int)$formData['phone2'];
	}

	// phone3
	if (!isset($formData['phone3'])) {
		echo 'phone3';
		exit();
	}
	if (empty($formData['phone3']) || !is_numeric($formData['phone3'])) {
		$workspace->phone3 = NULL;
	} else {
		$workspace->phone3 = (int)$formData['phone3'];
	}

	// timeZone
	if (!isset($formData['timeZone'])) {
		echo 'timeZone';
		exit();
	}
	if (empty($formData['timeZone'])) {
		$workspace->timeZone = 'UTC';
	} else {
		$workspace->timeZone = $formData['timeZone'];
	}

	// currencySymbol
	if (!isset($formData['currencySymbol']) || empty($formData['currencySymbol']) || strlen($formData['currencySymbol']) > 1) {
		echo 'currencySymbol';
		exit();
	} else {
		$workspace->currencySymbol = $formData['currencySymbol'];
	}

	// areaSymbol
	if (!isset($formData['areaSymbol']) || empty($formData['areaSymbol']) || strlen($formData['areaSymbol']) > 4) {
		echo 'areaSymbol';
		exit();
	} else {
		$workspace->areaSymbol = $formData['areaSymbol'];
	}

	// distanceSymbol
	if (!isset($formData['distanceSymbol']) || empty($formData['distanceSymbol']) || strlen($formData['distanceSymbol']) > 4) {
		echo 'distanceSymbol';
		exit();
	} else {
		$workspace->distanceSymbol = $formData['distanceSymbol'];
	}

	// creditAlertIsEnabled
	if (!isset($formData['creditAlertIsEnabled'])) {
		$workspace->creditAlertIsEnabled = '0';
	} else {
		$workspace->creditAlertIsEnabled = '1';
	}

	// creditAlertAmount
	if (!isset($formData['creditAlertAmount']) || !is_numeric($formData['creditAlertAmount'])) {
		echo 'creditAlertAmount';
		exit();
	}
	if (empty($formData['creditAlertAmount'])) {
		$workspace->creditAlertAmount = '0';
	} else {
		$workspace->creditAlertAmount = (float)number_format($formData['creditAlertAmount'], 2);
	}

	// balanceAlertIsEnabled
	if (!isset($formData['balanceAlertIsEnabled'])) {
		$workspace->balanceAlertIsEnabled = '0';
	} else {
		$workspace->balanceAlertIsEnabled = '1';
	}

	// balanceAlertAmount
	if (!isset($formData['balanceAlertAmount']) || !is_numeric($formData['balanceAlertAmount'])) {
		echo 'balanceAlertAmount';
		exit();
	}
	if (empty($formData['balanceAlertAmount'])) {
		$workspace->balanceAlertAmount = '0';
	} else {
		$workspace->balanceAlertAmount = (float)number_format($formData['balanceAlertAmount'], 2);
	}

	// modPayrSalDefaultType
	if (!isset($formData['modPayrSalDefaultType']) || !in_array($formData['modPayrSalDefaultType'], array('none', 'hourly', 'aPerCalendarEvent', 'pPerCalendarEvent'))) {
		echo 'modPayrSalDefaultType';
		exit();
	} else {
		$workspace->modPayrSalDefaultType = $formData['modPayrSalDefaultType'];
	}

	// modPayrSalBaseHourlyRate
	if (!isset($formData['modPayrSalBaseHourlyRate']) || !is_numeric($formData['modPayrSalBaseHourlyRate'])) {
		echo 'modPayrSalBaseHourlyRate';
		exit();
	}
	if (empty($formData['modPayrSalBaseHourlyRate'])) {
		$workspace->modPayrSalBaseHourlyRate = '0';
	} else {
		$workspace->modPayrSalBaseHourlyRate = (float)number_format($formData['modPayrSalBaseHourlyRate'], 2);
	}

	// modPayrSalBasePerCalendarEvent
	if (!isset($formData['modPayrSalBasePerCalendarEvent']) || !is_numeric($formData['modPayrSalBasePerCalendarEvent'])) {
		echo 'modPayrSalBasePerCalendarEvent';
		exit();
	}
	if (empty($formData['modPayrSalBasePerCalendarEvent'])) {
		$workspace->modPayrSalBasePerCalendarEvent = '0';
	} else {
		$workspace->modPayrSalBasePerCalendarEvent = (float)number_format($formData['modPayrSalBasePerCalendarEvent'], 2);
	}

	// modPayrSalBaseCalendarEventPercent
	if (!isset($formData['modPayrSalBaseCalendarEventPercent']) || !is_numeric($formData['modPayrSalBaseCalendarEventPercent'])) {
		echo 'modPayrSalBaseCalendarEventPercent';
		exit();
	}
	if (empty($formData['modPayrSalBaseCalendarEventPercent'])) {
		$workspace->modPayrSalBaseCalendarEventPercent = '0';
	} else {
		$workspace->modPayrSalBaseCalendarEventPercent = (float)number_format($formData['modPayrSalBaseCalendarEventPercent'], 2);
	}

	// docIdMin
	if (!isset($formData['docIdMin']) || !is_numeric($formData['docIdMin'])) {
		echo 'docIdMin';
		exit();
	}
	if (empty($formData['docIdMin'])) {
		$workspace->docIdMin = '0';
	} else {
		$workspace->docIdMin = (int)$formData['docIdMin'];
	}

	// docIdIsRandom
	if (!isset($formData['docIdIsRandom'])) {
		$workspace->docIdIsRandom = '0';
	} else {
		$workspace->docIdIsRandom = '1';
	}

	// invoiceTerm
	if (!isset($formData['invoiceTerm'])) {
		echo 'invoiceTerm';
		exit();
	}
	if (empty($formData['invoiceTerm']) || (int)$formData['invoiceTerm'] < 1) {
		$workspace->invoiceTerm = NULL;
	} else {
		$workspace->invoiceTerm = (int)$formData['invoiceTerm'];
	}

	// autoApplyCredit
	if (!isset($formData['autoApplyCredit'])) {
		$workspace->autoApplyCredit = '0';
	} else {
		$workspace->autoApplyCredit = '1';
	}

	// Payment Methods
	require_once '../../../../../lib/table/paymentMethod.php';
	if (!empty($formData['paymentMethodId'])) {
		foreach($formData['paymentMethodId'] as $methodNum => $paymentMethodId) {
			// check if the id exists, if it doesn't just don't bother since they are messing with the code
			$currentPaymentMethod = new paymentMethod($paymentMethodId);
			if ($currentPaymentMethod->existed && $currentPaymentMethod->workspaceId == $workspace->workspaceId) {
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
		$workspace->estimateValidity = NULL;
	} else {
		$workspace->estimateValidity = (int)$formData['estimateValidity'];
	}

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['authToken'], 'editWorkspace');

	if ($workspace->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Redirect
	echo 'success';
	exit();

?>
