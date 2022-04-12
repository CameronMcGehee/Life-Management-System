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

	if (!isset($_POST['invoiceId'])) {
		echo 'noId';
		exit();
	}

	// Verify the invoice belongs to the business that is signed in
	require_once '../../../../../../lib/table/invoice.php';
	$currentInvoice = new invoice($_POST['invoiceId']);
	if ($currentInvoice->businessId != $_SESSION['ultiscape_businessId']) {
        echo 'noInvoice';
        exit();
    }

	echo $currentInvoice->invoiceId.':::';

	// Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($formData['mainAuthToken']) || !validateAuthToken($formData['mainAuthToken'], 'editInvoice')) {
		echo 'tokenInvalid';
		exit();
	}

	// Check if input is set and valid
	// docId
	require_once '../../../../../../lib/table/docId.php';
	if (!isset($formData['docId']) || empty($formData['docId']) || !is_numeric($formData['docId'])) {
		echo 'docId';
		exit();
	}
	if ($currentInvoice->existed) {
		$currentDocId = new docId($currentInvoice->docIdId);
	} else {
		$currentDocId = new docId();
		$currentInvoice->docIdId = $currentDocId->docIdId;
	}
	
	require_once '../../../../../../lib/table/business.php';
	$currentBusiness = new business($currentInvoice->businessId);

	if ((string)$currentBusiness->docIdIsRandom == '1') {
		$currentDocId->randomId = (int)$formData['docId'];
	} else {
		$currentDocId->incrementalId = (int)$formData['docId'];
	}

	if ($currentDocId->set() !== true) {
		echo 'setError';
		exit();
	}
	
	// customer
	if (!isset($formData['customer']) || $formData['customer'] == 'none') {
		echo 'customer';
		exit();
	}
	// Check if customer exists
	require_once '../../../../../../lib/table/customer.php';
	$currentCustomer = new customer($formData['customer']);
	if (!$currentCustomer->existed) {
		echo 'customer';
		exit();
	}

	$currentInvoice->customerId = $currentCustomer->customerId;

	// Items
	require_once '../../../../../../lib/table/invoiceItem.php';
	if (!empty($formData['itemId'])) {
		foreach($formData['itemId'] as $itemNum => $itemId) {
			// check if the id exists, if it doesn't just don't bother since they are messing with the code
			$currentItem = new invoiceItem($itemId);
			if ($currentItem->existed && $currentItem->invoiceId == $currentInvoice->invoiceId) {
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
		$currentInvoice->comments = NULL;
	} else {
		$currentInvoice->comments = $formData['comments'];
	}

	// privateNotes
	if (!isset($formData['privateNotes'])) {
		echo 'privateNotes';
		exit();
	}
	if (empty($formData['privateNotes'])) {
		$currentInvoice->privateNotes = NULL;
	} else {
		$currentInvoice->privateNotes = $formData['privateNotes'];
	}

	// discount
	if (empty($formData['discount']) || !isset($formData['discount']) || !is_numeric($formData['discount'])) {
		$currentInvoice->discount = 0;
	} else {
		$currentInvoice->discount = (float)$formData['discount'];
	}

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($formData['mainAuthToken'], 'editInvoice');

	if ($currentInvoice->set() !== true) {
		echo 'setError';
		var_dump($currentInvoice->set());
		exit();
	}
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
