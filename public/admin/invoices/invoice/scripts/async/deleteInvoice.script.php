<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['invoiceId'])) {
		echo 'noId';
		exit();
	}

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteInvoiceAuthToken']) || !validateAuthToken($_POST['deleteInvoiceAuthToken'], 'deleteInvoice')) {
		echo 'tokenInvalid';
		exit();
	}

	//Verify the invoice belongs to the business that is signed in
    require_once '../../../../../../lib/table/invoice.php';
	$currentInvoice = new invoice($_POST['invoiceId']);
    if (!$currentInvoice->existed || $currentInvoice->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noInvoice';
        exit();
    }

	// Delete any linked payments
    require_once '../../../../../../lib/database.php';
	$db = new database();
	$db->delete('payment', "WHERE linkedToInvoiceId = '$currentInvoice->invoiceId'", 1);

    // Delete the invoice (will cascade linked instance exceptions and such)
    if (!$currentInvoice->delete()) {
        echo 'deleteError';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteInvoiceAuthToken'], 'deleteInvoice');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
