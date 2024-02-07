<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	if (!isset($_POST['contactId'])) {
		echo 'noId';
		exit();
	}
	require_once '../../../../../../lib/table/contact.php';
	$currentContact = new contact($_POST['contactId']);
	if ($currentContact->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noContact';
        exit();
    }

	echo $currentContact->contactId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editContact')) {
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
		$currentContact->firstName = $splitName[0];
		$currentContact->lastName = $splitName[1];
	} else {
		$currentContact->firstName = $formData['firstLastName'];
		$currentContact->lastName = NULL;
	}
	
	// billAddress1
	if (!isset($formData['billAddress1'])) {
		echo 'billAddress1';
		exit();
	}
	if (empty($formData['billAddress1'])) {
		$currentContact->billAddress1 = NULL;
	} else {
		$currentContact->billAddress1 = $formData['billAddress1'];
	}

	// billAddress2
	if (!isset($formData['billAddress2'])) {
		echo 'billAddress2';
		exit();
	}
	if (empty($formData['billAddress2'])) {
		$currentContact->billAddress2 = NULL;
	} else {
		$currentContact->billAddress2 = $formData['billAddress2'];
	}

	// billCity
	if (!isset($formData['billCity'])) {
		echo 'billCity';
		exit();
	}
	if (empty($formData['billCity'])) {
		$currentContact->billCity = NULL;
	} else {
		$currentContact->billCity = $formData['billCity'];
	}

	// billState
	if (!isset($formData['billState'])) {
		echo 'billState';
		exit();
	}
	if (empty($formData['billState'])) {
		$currentContact->billState = NULL;
	} else {
		$currentContact->billState = $formData['billState'];
	}

	// billZipCode
	if (!isset($formData['billZipCode'])) {
		echo 'billZipCode';
		exit();
	}
	if (empty($formData['billZipCode'])) {
		$currentContact->billZipCode = NULL;
	} else {
		$currentContact->billZipCode = $formData['billZipCode'];
	}

	// overrideAutoApplyCredit
	if (!isset($formData['overrideAutoApplyCredit'])) {
		$currentContact->overrideAutoApplyCredit = '0';
	} else {
		$currentContact->overrideAutoApplyCredit = '1';
	}

	// overrideCreditAlertIsEnabled
	if (!isset($formData['overrideCreditAlertIsEnabled'])) {
		$currentContact->overrideCreditAlertIsEnabled = '0';
	} else {
		$currentContact->overrideCreditAlertIsEnabled = '1';
	}

	// overrideCreditAlertAmount
	if (isset($formData['overrideCreditAlertAmountUseDefault'])) {
		$currentContact->overrideCreditAlertAmount = NULL;
	} else {
		if (!isset($formData['overrideCreditAlertAmount']) || !is_numeric($formData['overrideCreditAlertAmount'])) {
			echo 'overrideCreditAlertAmount';
			exit();
		}
		if (empty($formData['overrideCreditAlertAmount'])) {
			$currentContact->overrideCreditAlertAmount = '0';
		} else {
			$currentContact->overrideCreditAlertAmount = (float)number_format($formData['overrideCreditAlertAmount'], 2);
		}
	}

	// overrideBalanceAlertIsEnabled
	if (!isset($formData['overrideBalanceAlertIsEnabled'])) {
		$currentContact->overrideBalanceAlertIsEnabled = '0';
	} else {
		$currentContact->overrideBalanceAlertIsEnabled = '1';
	}

	// overrideBalanceAlertAmount
	if (isset($formData['overrideBalanceAlertAmountUseDefault'])) {
		$currentContact->overrideBalanceAlertAmount = NULL;
	} else {
		
		if (!isset($formData['overrideBalanceAlertAmount']) || !is_numeric($formData['overrideBalanceAlertAmount'])) {
			echo 'overrideBalanceAlertAmount';
			exit();
		}
		if (empty($formData['overrideBalanceAlertAmount'])) {
			$currentContact->overrideBalanceAlertAmount = '0';
		} else {
			$currentContact->overrideBalanceAlertAmount = (float)number_format($formData['overrideBalanceAlertAmount'], 2);
		}
	}

	// allowCZSignIn
	if (!isset($formData['allowCZSignIn'])) {
		$currentContact->allowCZSignIn = '0';
	} else {
		$currentContact->allowCZSignIn = '1';
	}

	// password
	if (!isset($formData['password']) || empty($formData['password'])) {
		echo 'password';
		exit();
	}
	// Check if the password already exists in this workspace
	require_once '../../../../../../lib/database.php';
	$db = new database();
	$passwordCheck = $db->select('contact', "contactId", "WHERE workspaceId = '".$_SESSION['lifems_workspaceId']."' AND password = '".$db->sanitize($formData['password'])."' LIMIT 1");
	if ($passwordCheck) {
		if ($passwordCheck[0]['contactId'] != $currentContact->contactId) {
			echo 'passwordInUse';
			exit();
		}
	}
	$currentContact->password = $formData['password'];

	// notes
	if (!isset($formData['notes'])) {
		echo 'notes';
		exit();
	}
	if (empty($formData['notes'])) {
		$currentContact->notes = NULL;
	} else {
		$currentContact->notes = $formData['notes'];
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editContact');

	if ($currentContact->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
