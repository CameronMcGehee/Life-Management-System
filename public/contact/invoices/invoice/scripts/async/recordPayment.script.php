<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['invoiceId'])) {
		echo 'noId';
		exit();
	}

	if (!isset($_POST['amount']) || !is_numeric($_POST['amount']) || (float)$_POST['amount'] < 0.01) {
		echo 'recordPaymentAmount';
		exit();
	}

	if (!isset($_POST['method'])) {
		echo 'recordPaymentMethod';
		exit();
	}

	if (!isset($_POST['notes'])) {
		echo 'recordPaymentNotes';
		exit();
	}

	if (!isset($_POST['excessType']) || !in_array($_POST['excessType'], ['tip', 'credit'])) {
		echo 'recordPaymentExcessType';
		exit();
	}
	
	// Verify the invoice belongs to the workspace that is signed in
	require_once '../../../../../../lib/table/invoice.php';
	$currentInvoice = new invoice($_POST['invoiceId']);
	if (!$currentInvoice->existed || $currentInvoice->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noInvoice';
        exit();
    }

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['recordPaymentAuthToken']) || !validateAuthToken($_POST['recordPaymentAuthToken'], 'recordPayment')) {
		echo 'tokenInvalid';
		exit();
	}

	require_once '../../../../../../lib/table/payment.php';
	$newPayment = new payment();
	$newPayment->linkedToInvoiceId = $currentInvoice->invoiceId;
	$newPayment->contactId = $currentInvoice->contactId;
	$newPayment->amount = (float)$_POST['amount'];
	if (empty($_POST['notes'])) {
		$newPayment->notes = NULL;
	} else {
		$newPayment->notes = $_POST['notes'];
	}
	if ($_POST['excessType'] == 'credit') {
		$newPayment->excessWasAddedToCredit = '1';
	} else {
		$newPayment->excessWasAddedToCredit = '0';
	}

	// Get the payment method details
	require_once '../../../../../../lib/table/paymentMethod.php';
	$currentMethod = new paymentMethod($_POST['method']);
	if (!$currentMethod->existed || $currentMethod->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noMethod';
        exit();
    }
	$newPayment->linkedToPaymentMethodId = $currentMethod->paymentMethodId;
	$newPayment->methodName = $currentMethod->name;
	$newPayment->methodPercentCut = $currentMethod->percentCut;
	$newPayment->methodAmountCut = $currentMethod->amountCut;

	if ($newPayment->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['recordPaymentAuthToken'], 'recordPayment');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
