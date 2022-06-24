<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['customerId'])) {
		echo 'error (2)';
		exit();
	}

	require_once '../../../../../lib/table/customer.php';
	$customer = new customer($_POST['customerId']);

	if (!$customer->existed || $customer->businessId != $_SESSION['ultiscape_businessId']) {
		exit();
    }

	$billAddress = '';

	if (!empty($customer->billAddress1)) {
		$billAddress .= htmlspecialchars($customer->billAddress1);
		if (!empty($customer->billAddress2) || !empty($customer->billState) || !empty($customer->billCity) || !empty($customer->billZipCode)) {
			$billAddress .= '<br>';
		}
	}
	if (!empty($customer->billAddress2)) {
		$billAddress .= htmlspecialchars($customer->billAddress2);
		if (!empty($customer->billCity) || !empty($customer->billState) || !empty($customer->billZipCode)) {
			$billAddress .= '<br>';
		}
	}
	if (!empty($customer->billCity)) {
		$billAddress .= htmlspecialchars($customer->billCity);
		if (!empty($customer->billState)) {
			$billAddress .= ', ';
		}
	}
	if (!empty($customer->billState)) {
		$billAddress .= htmlspecialchars($customer->billState);
	}
	if (!empty($customer->billZipCode)) {
		$billAddress .= ' '.htmlspecialchars($customer->billZipCode).'';
	}

	if ($billAddress == '') {
		$billAddress = '<span style="color: red;">Billing address not on file.</span>';
	}

	echo $billAddress

?>
