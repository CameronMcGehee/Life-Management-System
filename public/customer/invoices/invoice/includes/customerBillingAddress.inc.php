<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an customer is logged in

	if (!isset($_SESSION['ultiscape_customerId']) || !isset($_SESSION['ultiscape_businessId'])) {
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

	$customerName = '<b>';

	if ($customer->lastName != NULL) {
		$customerName .= htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->lastName);
	} else {
		$customerName .= htmlspecialchars($customer->firstName);
	}

	$customerName .= '</b><br>';

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
		$billAddress = '<span style="color: red;">Your billing address is not on file.</span>';
	}

	echo $customerName;
	echo $billAddress

?>
