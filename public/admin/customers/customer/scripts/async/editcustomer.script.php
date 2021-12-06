<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	require_once '../../../../../../lib/table/customer.php';
	$currentCustomer = new customer($formData['customerId']);
	if ($currentCustomer->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noCustomer';
        exit();
    }

	echo $currentCustomer->customerId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'editCustomer')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// firstLastName
	if (!isset($formData['firstLastName']) || empty($formData['firstLastName'])) {
		echo 'firstLastName';
		exit();
	}
	// seperate the first and last name if two words, if not two words just put it all as the first name
	$splitName = explode(" ", $formData['firstLastName']);
	if (count($splitName) == 2) {
		$currentCustomer->firstName = $splitName[0];
		$currentCustomer->lastName = $splitName[1];
	} else {
		$currentCustomer->firstName = $formData['firstLastName'];
		$currentCustomer->lastName = NULL;
	}
	
	// billAddress1
	if (!isset($formData['billAddress1'])) {
		echo 'billAddress1';
		exit();
	}
	if (empty($formData['billAddress1'])) {
		$currentCustomer->billAddress1 = NULL;
	} else {
		$currentCustomer->billAddress1 = $formData['billAddress1'];
	}

	// billAddress2
	if (!isset($formData['billAddress2'])) {
		echo 'billAddress2';
		exit();
	}
	if (empty($formData['billAddress2'])) {
		$currentCustomer->billAddress2 = NULL;
	} else {
		$currentCustomer->billAddress2 = $formData['billAddress2'];
	}

	// billCity
	if (!isset($formData['billCity'])) {
		echo 'billCity';
		exit();
	}
	if (empty($formData['billCity'])) {
		$currentCustomer->billCity = NULL;
	} else {
		$currentCustomer->billCity = $formData['billCity'];
	}

	// billState
	if (!isset($formData['billState'])) {
		echo 'billState';
		exit();
	}
	if (empty($formData['billState'])) {
		$currentCustomer->billState = NULL;
	} else {
		$currentCustomer->billState = $formData['billState'];
	}

	// billZipCode
	if (!isset($formData['billZipCode'])) {
		echo 'billZipCode';
		exit();
	}
	if (empty($formData['billZipCode'])) {
		$currentCustomer->billZipCode = NULL;
	} else {
		$currentCustomer->billZipCode = $formData['billZipCode'];
	}

	// overrideAutoApplyCredit
	if (!isset($formData['overrideAutoApplyCredit'])) {
		$currentCustomer->overrideAutoApplyCredit = '0';
	} else {
		$currentCustomer->overrideAutoApplyCredit = '1';
	}

	// overrideCreditAlertIsEnabled
	if (!isset($formData['overrideCreditAlertIsEnabled'])) {
		$currentCustomer->overrideCreditAlertIsEnabled = '0';
	} else {
		$currentCustomer->overrideCreditAlertIsEnabled = '1';
	}

	// overrideCreditAlertAmount
	if (isset($formData['overrideCreditAlertAmountUseDefault'])) {
		$currentCustomer->overrideCreditAlertAmount = NULL;
	} else {
		if (!isset($formData['overrideCreditAlertAmount']) || !is_numeric($formData['overrideCreditAlertAmount'])) {
			echo 'overrideCreditAlertAmount';
			exit();
		}
		if (empty($formData['overrideCreditAlertAmount'])) {
			$currentCustomer->overrideCreditAlertAmount = '0';
		} else {
			$currentCustomer->overrideCreditAlertAmount = (float)number_format($formData['overrideCreditAlertAmount'], 2);
		}
	}

	// overrideBalanceAlertIsEnabled
	if (!isset($formData['overrideBalanceAlertIsEnabled'])) {
		$currentCustomer->overrideBalanceAlertIsEnabled = '0';
	} else {
		$currentCustomer->overrideBalanceAlertIsEnabled = '1';
	}

	// overrideBalanceAlertAmount
	if (isset($formData['overrideBalanceAlertAmountUseDefault'])) {
		$currentCustomer->overrideBalanceAlertAmount = NULL;
	} else {
		
		if (!isset($formData['overrideBalanceAlertAmount']) || !is_numeric($formData['overrideBalanceAlertAmount'])) {
			echo 'overrideBalanceAlertAmount';
			exit();
		}
		if (empty($formData['overrideBalanceAlertAmount'])) {
			$currentCustomer->overrideBalanceAlertAmount = '0';
		} else {
			$currentCustomer->overrideBalanceAlertAmount = (float)number_format($formData['overrideBalanceAlertAmount'], 2);
		}
	}

	// allowCZSignIn
	if (!isset($formData['allowCZSignIn'])) {
		$currentCustomer->allowCZSignIn = '0';
	} else {
		$currentCustomer->allowCZSignIn = '1';
	}

	// password
	if (!isset($formData['password']) || empty($formData['password'])) {
		echo 'password';
		exit();
	}
	$currentCustomer->password = $formData['password'];

	// notes
	if (!isset($formData['notes'])) {
		echo 'notes';
		exit();
	}
	if (empty($formData['notes'])) {
		$currentCustomer->notes = NULL;
	} else {
		$currentCustomer->notes = $formData['notes'];
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['authToken'], 'editCustomer');

	if ($currentCustomer->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
