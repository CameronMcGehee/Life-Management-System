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

	if (!isset($_POST['estimateId'])) {
		echo 'noId';
		exit();
	}

	// Verify the estimate belongs to the workspace that is signed in
	require_once '../../../../../../lib/table/estimate.php';
	$currentEstimate = new estimate($_POST['estimateId']);
	if ($currentEstimate->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noEstimate';
        exit();
    }

	echo $currentEstimate->estimateId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editEstimate')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// docId

	if ($currentEstimate->dateTimeApproved == NULL) {
		require_once '../../../../../../lib/table/docId.php';
		if (!isset($formData['docId']) || empty($formData['docId']) || !is_numeric($formData['docId'])) {
			echo 'docId';
			exit();
		}
		if ($currentEstimate->existed) {
			$currentDocId = new docId($currentEstimate->docIdId);
		} else {
			$currentDocId = new docId();
			$currentEstimate->docIdId = $currentDocId->docIdId;
		}
		
		require_once '../../../../../../lib/table/workspace.php';
		$currentWorkspace = new workspace($currentEstimate->workspaceId);

		if ((string)$currentWorkspace->docIdIsRandom == '1') {
			$currentDocId->randomId = (int)$formData['docId'];
		} else {
			$currentDocId->incrementalId = (int)$formData['docId'];
		}
		
		// contact
		if (!isset($formData['contact']) || $formData['contact'] == 'none') {
			echo 'contact';
			exit();
		}

		// If contact has been changed then reset the approval status if not approved by the admin
		if ($currentEstimate->contactId != $formData['contact'] && $currentEstimate->approvedByAdminId != NULL) {
			$currentEstimate->approvedByAdminId = NULL;
			$currentEstimate->adminReason = NULL;
			$currentEstimate->dateTimeApproved = NULL;
		}
		// Check if contact exists
		require_once '../../../../../../lib/table/contact.php';
		$currentContact = new contact($formData['contact']);
		if (!$currentContact->existed) {
			echo 'contact';
			exit();
		}

		$currentEstimate->contactId = $currentContact->contactId;

		// Items
		require_once '../../../../../../lib/table/estimateItem.php';
		if (!empty($formData['itemId'])) {
			foreach($formData['itemId'] as $itemNum => $itemId) {
				// check if the id exists, if it doesn't just don't bother since they are messing with the code
				$currentItem = new estimateItem($itemId);
				if ($currentItem->existed && $currentItem->estimateId == $currentEstimate->estimateId) {
					// Update it
					$currentItem->name = $formData['itemName'][$itemNum];
					$currentItem->price = round($formData['itemPrice'][$itemNum], 2);
					$currentItem->quantity = $formData['itemQuantity'][$itemNum];
					$currentItem->tax = $formData['itemTax'][$itemNum];
					
					$currentItem->set();
				}
			}
		}
		
		// comments
		if (!isset($formData['comments'])) {
			echo 'comments';
			exit();
		}
		if (empty($formData['comments'])) {
			$currentEstimate->comments = NULL;
		} else {
			$currentEstimate->comments = $formData['comments'];
		}

		// privateNotes
		if (!isset($formData['privateNotes'])) {
			echo 'privateNotes';
			exit();
		}
		if (empty($formData['privateNotes'])) {
			$currentEstimate->privateNotes = NULL;
		} else {
			$currentEstimate->privateNotes = $formData['privateNotes'];
		}

		// discount
		if (empty($formData['discount']) || !isset($formData['discount']) || !is_numeric($formData['discount'])) {
			$currentEstimate->discount = 0;
		} else {
			$currentEstimate->discount = (float)$formData['discount'];
		}

		$currentEstimate->discountIsPercent = '0';

		if ($currentDocId->set() !== true) {
			echo 'setError';
			exit();
		}
	
		if ($currentEstimate->set() !== true) {
			echo 'setError';
			var_dump($currentEstimate->set());
			exit();
		}
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editEstimate');

	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
