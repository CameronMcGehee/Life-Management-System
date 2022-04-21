<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['businessId'])) {
		echo 'noId';
		exit();
	}

	// Verify the business is the business that is signed in
	require_once '../../../../../lib/table/business.php';
	$currentBusiness = new business($_POST['businessId']);
	if (!$currentBusiness->existed || $currentBusiness->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noBusiness';
        exit();
    }

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['addPaymentMethodAuthToken']) || !validateAuthToken($_POST['addPaymentMethodAuthToken'], 'addPaymentMethod')) {
		echo 'tokenInvalid';
		exit();
	}

	require_once '../../../../../lib/table/paymentMethod.php';
	$newPaymentMethod = new paymentMethod();

	echo $newPaymentMethod->paymentMethodId.':::';

	if ($newPaymentMethod->set() !== true) {
		echo 'setError';
		exit();
	}

	// Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['addPaymentMethodAuthToken'], 'addPaymentMethod');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
