<?php

    require_once '../../../../php/startSession.php';

    $matchedCustomerId = NULL;

    require_once '../../../../../lib/database.php';
    require_once '../../../../../lib/table/business.php';

    function logAttempt($result, $matchedCustomer) {
        // Log the attempt
        require_once '../../../../../lib/table/customerLoginAttempt.php';
        $attempt = new customerLoginAttempt();
        if (isset($matchedCustomer) && !empty($matchedCustomer)) {
            $attempt->customerId = $matchedCustomer;
        }
        $attempt->result = $result;
        $attempt->set();
    }

    if (!isset($_POST['formData'])) {
        logAttempt('noData', $matchedCustomerId);
		echo 'noData';
		exit();
	}
	parse_str($_POST['formData'], $formData);

	// Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'customerLogin')) {
        logAttempt('tokenInvalid', $matchedCustomerId);
		echo 'tokenInvalid';
		exit();
	} else {
        // Since this script is run relatively often, purge old authTokens to keep the table small whenever the token is valid
        require_once '../../../../../lib/etc/authToken/purgeAuthTokens.php';
        purgeAuthTokens($ULTISCAPECONFIG['authTokenDefaultPurge']);
    }

    // Check if input is set and valid

	if (!isset($_POST['businessId']) || empty($_POST['businessId'])) {
        logAttempt('businessId', $matchedCustomerId);
		echo 'businessId';
		exit();
	}

    $currentBusiness = new business($_POST['businessId']);
    if (!$currentBusiness->existed) {
        logAttempt('businessId', $matchedCustomerId);
		echo 'businessId';
		exit();
    }

	if (!isset($formData['password']) || empty($formData['password'])) {
        logAttempt('password', $matchedCustomerId);
		echo 'password';
		exit();
	}

    $db = new database();
    $passwordSan = $db->sanitize($formData['password']);

    $currentBusiness->pullCustomers("AND password = '$passwordSan'");

    if (count($currentBusiness->customers) !== 1) {
        logAttempt('noCustomer', $matchedCustomerId);
        echo 'noCustomer';
        exit();
    }

    // Get the customer as an object
    require_once '../../../../../lib/table/customer.php';
    $matchedCustomer = new customer($currentBusiness->customers[0]);
    if (!$matchedCustomer->existed || $matchedCustomer->businessId != $currentBusiness->businessId) {
        logAttempt('noCustomer', $matchedCustomerId);
        echo 'noCustomer';
        exit();
    }

    $matchedCustomerId = $matchedCustomer->customerId;

    //Check if the username/email and password match
    // Login the user
    $_SESSION['ultiscape_customerId'] = $matchedCustomerId;
    $_SESSION['ultiscape_businessId'] = $matchedCustomer->businessId;

    setcookie('ultiscape_lastBusinessId', $matchedCustomer->businessId, time() + (86400 * 30), "/");

    logAttempt('success', $matchedCustomerId);

    // Use the auth token
    require_once '../../../../../lib/etc/authToken/useAuthToken.php';
    useAuthToken($formData['authToken'], 'customerLogin');

    echo 'success';
    exit();

    // if remember me is checked, save the login and set a cookie
    if (false) { // Will do later
        // Set a saved login
    }

?>
