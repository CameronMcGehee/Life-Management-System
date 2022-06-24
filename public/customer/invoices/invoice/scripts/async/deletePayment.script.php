<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['paymentId'])) {
		echo 'noId';
		exit();
	}

	require_once '../../../../../../lib/table/payment.php';
	$currentPayment = new payment($_POST['paymentId']);

	// Verify the invoice payment belongs to the business that is signed in
	if (!$currentPayment->existed || $currentPayment->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noInvoice';
        exit();
    }

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deletePaymentAuthToken']) || !validateAuthToken($_POST['deletePaymentAuthToken'], 'deletePayment')) {
		echo 'tokenInvalid';
		exit();
	}

    if ($currentPayment->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentPayment->paymentId.'::::';

    // Delete the payment
    if ($currentPayment->delete()) {
        echo 'success';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deletePaymentAuthToken'], 'deletePayment');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
