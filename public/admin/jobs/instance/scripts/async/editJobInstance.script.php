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

	if (!isset($_POST['instanceId'])) {
		echo 'noId';
		exit();
	}
	require_once '../../../../../../lib/table/jobInstanceException.php';
	$currentInstance = new jobInstanceException($_POST['instanceId']);
	if ($currentInstance->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noJob';
        exit();
    }

	echo $currentInstance->jobInstanceExceptionId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editJobInstanceException')) {
		echo 'tokenInvalid';
		exit();
	}

	// Make sure the instance date is a valid instance date
	require_once '../../../../../../lib/etc/time/validateDate.php';
	if (!validateDate($formData['instanceDate'], 'Y-m-d')) {
		echo 'instanceDate';
		exit();
	}

	function setValuesFromForm($formData) {
		// name
		global $formData;
		global $currentInstance;
		if (!isset($formData['name']) || empty($formData['name'])) {
			echo 'name';
			exit();
		} else {
			$currentInstance->name = $formData['name'];
		}

		// customer
		if (!isset($formData['customer']) || empty($formData['customer'])) {
			echo 'customer';
			exit();
		} else {
			require_once '../../../../../../lib/table/customer.php';
			$currentCustomer = new customer($formData['customer']);
			if ($currentCustomer->existed) {
				$currentInstance->linkedToCustomerId = $formData['customer'];
			} else {
				$currentInstance->linkedToCustomerId = 'NULL';
			}
		}

		// property
		if (!isset($formData['property']) || empty($formData['property'])) {
			$currentInstance->linkedToPropertyId = 'NULL';
		} else {
			require_once '../../../../../../lib/table/property.php';
			$currentProperty = new property($formData['property']);
			if ($currentProperty->existed) {
				$currentInstance->linkedToPropertyId = $formData['property'];
			} else {
				$currentInstance->linkedToPropertyId = 'NULL';
			}
		}

		// description
		if (!isset($formData['description'])) {
			echo 'description';
			exit();
		}
		if (empty($formData['description'])) {
			$currentInstance->description = NULL;
		} else {
			$currentInstance->description = $formData['description'];
		}

		// privateNotes
		if (!isset($formData['privateNotes'])) {
			echo 'privateNotes';
			exit();
		}
		if (empty($formData['privateNotes'])) {
			$currentInstance->privateNotes = NULL;
		} else {
			$currentInstance->privateNotes = $formData['privateNotes'];
		}

		// price
		if (!isset($formData['price']) || empty($formData['price']) || !is_numeric($formData['price']) || $formData['price'] < .01) {
			if ($formData['price'] != '') {
				echo 'price';
				exit();
			} else {
				$currentInstance->price = NULL;
			}
		} else {
			$currentInstance->price = round((float)$formData['price'], 2);
		}

		// estHours
		if (!isset($formData['estHours']) || empty($formData['estHours']) || !is_numeric($formData['estHours'])) {
			$currentInstance->estHours = 0;
		} else {
			$currentInstance->estHours = (float)$formData['estHours'];
		}

		// isPrepaid
		if (!isset($formData['isPrepaid'])) {
			$currentInstance->isPrepaid = '0';
		} else {
			$currentInstance->isPrepaid = '1';
		}

		require_once '../../../../../../lib/etc/time/validateDate.php';

		//startDate
		if (!isset($formData['startDate']) || empty($formData['startDate']) || !validateDate($formData['startDate'], 'Y-m-d')) {
			echo 'startDate';
			exit();
		} else {
			$startDate = $formData['startDate'];
		}

		//startTime
		if (!isset($formData['startTime']) || empty($formData['startTime']) || !validateDate($formData['startTime'], 'H:i')) {
			$currentInstance->startDateTime = $startDate.' 00:00:00';
		} else {
			$currentInstance->startDateTime = $startDate.' '.$formData['startTime'].':00';
		}

		//endDate
		if (!isset($formData['endDate']) || empty($formData['endDate']) || !validateDate($formData['endDate'], 'Y-m-d')) {
			$endDate = NULL;
		} else {
			$endDate = $formData['endDate'];
		}

		//endTime
		if (!isset($formData['endTime']) || empty($formData['endTime']) || !validateDate($formData['endTime'], 'H:i')) {
			if ($endDate == NULL) {
				$currentInstance->endDateTime = NULL;
			} else {
				$currentInstance->endDateTime = $endDate.' 00:00:00';
			}
		} else {
			$currentInstance->endDateTime = $endDate.' '.$formData['endTime'].':00';
		}
	}

	setValuesFromForm($formData);

	if ($currentInstance->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editJobInstanceException');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
