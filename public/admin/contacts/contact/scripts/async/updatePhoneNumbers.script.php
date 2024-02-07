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
	if (!isset($formData['updatePhoneNumbersAuthToken']) || !validateAuthToken($formData['updatePhoneNumbersAuthToken'], 'updatePhoneNumbers')) {
		echo 'tokenInvalid';
		exit();
	}

    if (isset($formData['phoneNumbers']) || isset($formData['phoneNumberIds'])) {
        if (count($formData['phoneNumbers']) != count($formData['phoneNumberIds'])) {
            echo 'dataError';
            exit();
        }
    }

    // Gather all the addresses as an array
    $phonesReceived = array();
    if (isset($formData['phoneNumberIds'])) {
        foreach ($formData['phoneNumberIds'] as $key => $currentPhoneNumberId) {
            
            // id
            if (empty($currentPhoneNumberId)) {
                echo 'phoneNumber'.$currentPhoneNumberId;
            }

            // prefix
            $currentPhonePrefix = $formData['phoneNumberPrefixes'][$key];
            if (empty($currentPhonePrefix) || !is_numeric($currentPhonePrefix)) {
                $currentPhonePrefix = '1';
            } else {
                $currentPhonePrefix = (int)$formData['phoneNumberPrefixes'][$key];
            }

            // actual number
            $currentPhoneNumber = $formData['phoneNumbers'][$key];
            if (empty($currentPhoneNumber) || !is_numeric($currentPhoneNumber)) {
                echo 'phoneNumber'.$currentPhoneNumberId;
                exit();
            } else {
                $currentPhoneNumber = (int)$formData['phoneNumbers'][$key];
            }
    
            // description
            $currentPhoneDescription = $formData['phoneNumberDescriptions'][$key];
            if (empty($currentPhoneDescription)) {
                $currentPhoneDescription = '';
            }
    
            array_push($phonesReceived, array('id' => $currentPhoneNumberId, 'prefix' => $currentPhonePrefix, 'number' => $currentPhoneNumber, 'description' => $currentPhoneDescription));
        }
    }

    // For each phone number Id already in the system, check if it has been changed in the form. If it has, update it. If it is not present in the form, delete it.
    $dbPhone = '';
    require_once '../../../../../../lib/table/contactPhoneNumber.php';
    foreach ($phonesReceived as $currentReceivedPhoneNumber) {
        $dbPhone = new contactPhoneNumber($currentReceivedPhoneNumber['id']);
        if ($dbPhone->existed && $dbPhone->contactId == $currentContact->contactId) {
            $dbPhone->phonePrefix = $currentReceivedPhoneNumber['prefix'];
            $dbPhone->phone1 = $currentReceivedPhoneNumber['number'];
            $dbPhone->description = $currentReceivedPhoneNumber['description'];
            $dbPhone->set();
        } else {
            echo 'phoneNumber'.$currentReceivedPhoneNumber['id'];
            exit();
        }
    }

    // If there has been an email added, add it to the database
    if (!empty($formData['newPhoneNumber'])) {
        if (!is_numeric($formData['newPhoneNumber'])) {
            echo 'newPhoneNumber';
            exit();
        }
        // prefix
        $newPhonePrefix = $formData['newPhoneNumberPrefix'];
        if (empty($newPhonePrefix) || !is_numeric($newPhonePrefix)) {
            $newPhonePrefix = '1';
        }
        $newEmail = new contactPhoneNumber();
        $newEmail->contactId = $currentContact->contactId;
        $newEmail->phonePrefix = (int)$newPhonePrefix;
        $newEmail->phone1 = (int)$formData['newPhoneNumber'];
        $newEmail->set();
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['updatePhoneNumbersAuthToken'], 'updatePhoneNumbers');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
