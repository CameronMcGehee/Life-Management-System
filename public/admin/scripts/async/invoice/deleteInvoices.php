<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['invoices']) || gettype($_POST['invoices']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'deleteInvoices')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/table/invoice.php';

    foreach ($_POST['invoices'] as $invoiceId) {
        // Make sure that the invoiceId exists
        $currentInvoice = new invoice($invoiceId);
        if (!$currentInvoice->existed) {
            echo 'noInvoice';
            die();
        }
        // Check if current business has access to that invoice
        if ($currentInvoice->businessId !== $_SESSION['ultiscape_businessId']) {
            echo 'unauthorized';
            die();
        }
        // delete the invoice
        $currentInvoice->delete();
    }

    echo 'success';

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteInvoices');

?>
