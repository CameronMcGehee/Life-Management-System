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

	// Verify the invoice belongs to the workspace that is signed in
	require_once '../../../../../../lib/table/invoice.php';
	$currentInvoice = new invoice($_POST['invoiceId']);
	if (!$currentInvoice->existed || $currentInvoice->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noInvoice';
        exit();
    }

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['addItemAuthToken']) || !validateAuthToken($_POST['addItemAuthToken'], 'addInvoiceItem')) {
		echo 'tokenInvalid';
		exit();
	}

	require_once '../../../../../../lib/table/invoiceItem.php';
	$newItem = new invoiceItem();
	$newItem->invoiceId = $currentInvoice->invoiceId;

	echo $newItem->invoiceItemId.':::';

	if ($newItem->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['addItemAuthToken'], 'addInvoiceItem');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
