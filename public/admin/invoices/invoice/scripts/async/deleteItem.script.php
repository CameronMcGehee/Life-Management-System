<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['itemId'])) {
		echo 'noId';
		exit();
	}

	require_once '../../../../../../lib/table/invoiceItem.php';
	$currentItem = new invoiceItem($_POST['itemId']);

	// Verify the invoice item belongs to the business that is signed in
	if (!$currentItem->existed || $currentItem->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noInvoice';
        exit();
    }

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteItemAuthToken']) || !validateAuthToken($_POST['deleteItemAuthToken'], 'deleteInvoiceItem')) {
		echo 'tokenInvalid';
		exit();
	}

    if ($currentItem->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentItem->invoiceItemId.'::::';

    // Delete the address
    if ($currentItem->delete()) {
        echo 'success';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteItemAuthToken'], 'deleteInvoiceItem');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
