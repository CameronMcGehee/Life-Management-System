<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['customers']) || gettype($_POST['customers']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'deleteCustomers')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/table/customer.php';

    foreach ($_POST['customers'] as $customerId) {
        // Make sure that the customerId exists
        $currentCustomer = new customer($customerId);
        if (!$currentCustomer->existed) {
            echo 'noCustomer';
            die();
        }
        // Check if current business has access to that customer
        if ($currentCustomer->businessId !== $_SESSION['ultiscape_businessId']) {
            echo 'unauthorized';
            die();
        }
        // delete the customer
        $currentCustomer->delete();
    }

    echo 'success';

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteCustomers');

?>
