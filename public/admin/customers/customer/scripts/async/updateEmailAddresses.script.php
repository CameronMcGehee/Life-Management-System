<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
    parse_str($_POST['formData'], $formData);

    require_once '../../../../../../lib/table/customer.php';
	$currentCustomer = new customer($formData['customerId']);
    if (!$currentCustomer->existed || $currentCustomer->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noCustomer';
        exit();
    }

    echo $currentCustomer->customerId.':::';

    if (isset($formData['emailAddresses']) || isset($formData['emailAddressIds'])) {
        if (count($formData['emailAddresses']) != count($formData['emailAddressIds'])) {
            echo 'dataError';
            exit();
        }
    }

    // Gather all the addresses as an array
    $emailsRecieved = array();
    foreach ($formData['emailAddressIds'] as $key => $currentEmailAddressId) {
        if (empty($currentEmailAddressId)) {
            echo 'emailAddress'.$currentEmailAddressId;
        }
        if (empty($formData['emailAddresses'][$key])) {
            $currentEmailAddress = '';
        } else {
            $currentEmailAddress = $formData['emailAddresses'][$key];
            // If enabled, use filter function to make sure email is an actual email
            if ($ULTISCAPECONFIG['useEmailValidation']) {
                if (!filter_var($currentEmailAddress, FILTER_VALIDATE_EMAIL)) {
                    echo 'emailAddress'.$currentEmailAddressId;
                    exit();
                }
            }
        }

        if (empty($formData['emailAddressDescriptions'][$key])) {
            $currentEmailDescription = '';
        } else {
            $currentEmailDescription = $formData['emailAddressDescriptions'][$key];
        }

        array_push($emailsRecieved, array('id' => $currentEmailAddressId, 'address' => $currentEmailAddress, 'description' => $currentEmailDescription));
    }

	if (!isset($formData['customerId'])) {
		echo 'noId';
		exit();
	}

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($formData['authToken'], 'editCustomer')) {
		echo 'tokenInvalid';
		exit();
	}

    // Get all the emails in the system
    $currentCustomer->pullEmailAddresses();
    $existingEmails = $currentCustomer->emailAddresses;

    // For each email Id already in the system, check if it has been changed in the form. If it has, update it. If it is not present in the form, delete it.
    $dbEmail = '';
    require_once '../../../../../../lib/table/customerEmailAddress.php';
    foreach ($emailsRecieved as $currentRecievedEmail) {
        $dbEmail = new customerEmailAddress($currentRecievedEmail['id']);
        if ($dbEmail->existed && $dbEmail->customerId == $currentCustomer->customerId) {
            $dbEmail->email = $currentRecievedEmail['address'];
            $dbEmail->description = $currentRecievedEmail['description'];
            $dbEmail->set();
        } else {
            echo 'emailAddress'.$currentRecievedEmail['id'];
            exit();
        }
    }

    // If there has been an email added, add it to the database
    if (!empty($formData['newEmailAddress'])) {
        $newEmail = new customerEmailAddress();
        $newEmail->customerId = $currentCustomer->customerId;
        $newEmail->email = $formData['newEmailAddress'];
        $newEmail->set();
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['authToken'], 'editCustomer');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
