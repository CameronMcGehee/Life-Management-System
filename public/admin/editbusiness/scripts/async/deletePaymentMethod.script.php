<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['paymentMethodId'])) {
		echo 'noId';
		exit();
	}

	require_once '../../../../../lib/table/paymentMethod.php';
	$currentPaymentMethod = new paymentMethod($_POST['paymentMethodId']);

	// Verify the invoice paymentMethod belongs to the business that is signed in
	if (!$currentPaymentMethod->existed || $currentPaymentMethod->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noPaymentMethod';
        exit();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deletePaymentMethodAuthToken']) || !validateAuthToken($_POST['deletePaymentMethodAuthToken'], 'deletePaymentMethod')) {
		echo 'tokenInvalid';
		exit();
	}

    if ($currentPaymentMethod->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentPaymentMethod->paymentMethodId.'::::';

    // Delete the address
    if (!$currentPaymentMethod->delete()) {
        echo 'deleteError';
        exit();
    }

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deletePaymentMethodAuthToken'], 'deletePaymentMethod');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
