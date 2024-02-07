<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

	if (!isset($_POST['workspaceId'])) {
		echo 'noId';
		exit();
	}

	// Verify the workspace is the workspace that is signed in
	require_once '../../../../../lib/table/workspace.php';
	$currentWorkspace = new workspace($_POST['workspaceId']);
	if (!$currentWorkspace->existed || $currentWorkspace->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noWorkspace';
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
