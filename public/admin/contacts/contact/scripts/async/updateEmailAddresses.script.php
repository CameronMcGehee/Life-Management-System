<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['formData'])) {
		echo 'noData';
		exit();
	}
    parse_str($_POST['formData'], $formData);

    if (!isset($_POST['contactId'])) {
		echo 'noId';
		exit();
	}
    require_once '../../../../../../lib/table/contact.php';
	$currentContact = new contact($_POST['contactId']);
    if ($currentContact->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentContact->contactId.'::::';

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['updateEmailAddressesAuthToken']) || !validateAuthToken($formData['updateEmailAddressesAuthToken'], 'updateEmailAddresses')) {
		echo 'tokenInvalid';
		exit();
	}

    if (isset($formData['emailAddresses']) || isset($formData['emailAddressIds'])) {
        if (count($formData['emailAddresses']) != count($formData['emailAddressIds'])) {
            echo 'dataError';
            exit();
        }
    }

    // Gather all the addresses as an array
    $emailsRecieved = array();
    if (isset($formData['emailAddressIds'])) {
        foreach ($formData['emailAddressIds'] as $key => $currentEmailAddressId) {
            if (empty($currentEmailAddressId)) {
                echo 'emailAddress'.$currentEmailAddressId;
            }
            $currentEmailAddress = $formData['emailAddresses'][$key];
            if (empty($formData['emailAddresses'][$key])) {
                echo 'emailAddress'.$currentEmailAddressId;
                exit();
            } else {
                // If enabled, use filter function to make sure email is an actual email
                if ($lifemsConfig['useEmailValidation']) {
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
    }

    // For each email Id already in the system, check if it has been changed in the form. If it has, update it. If it is not present in the form, delete it.
    $dbEmail = '';
    require_once '../../../../../../lib/table/contactEmailAddress.php';
    foreach ($emailsRecieved as $currentRecievedEmail) {
        $dbEmail = new contactEmailAddress($currentRecievedEmail['id']);
        if ($dbEmail->existed && $dbEmail->contactId == $currentContact->contactId) {
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
        // If enabled, use filter function to make sure email is an actual email
        if ($lifemsConfig['useEmailValidation']) {
            if (!filter_var($formData['newEmailAddress'], FILTER_VALIDATE_EMAIL)) {
                echo 'newEmailAddress';
                exit();
            }
        }
        $newEmail = new contactEmailAddress();
        $newEmail->contactId = $currentContact->contactId;
        $newEmail->email = $formData['newEmailAddress'];
        $newEmail->set();
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['updateEmailAddressesAuthToken'], 'updateEmailAddresses');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
