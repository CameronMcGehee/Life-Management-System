<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['customerId'])) {
		echo 'error (2)';
		exit();
	}

	require_once '../../../../../lib/table/customer.php';
	$currentCustomer = new customer($_POST['customerId']);

    // Get all the emails in the system

    $currentCustomer->pullEmailAddresses();

	// Render the list of email inputs and buttons
	require_once '../../../../../lib/table/customerEmailAddress.php';
    foreach($currentCustomer->emailAddresses as $key => $emailAddressId) {
        $emailInfo = new customerEmailAddress($emailAddressId);
        echo '<input type="hidden" name="emailAddressIds[]" value="'.htmlspecialchars($emailAddressId).'"><input class="almostInvisibleInputNoHover" style="font-size: 1.2em; width: 70%; display: inline;" type="text" name="emailAddresses[]" id="emailAddress:::'.htmlspecialchars($emailAddressId).'" value="'.htmlspecialchars($emailInfo->email).'"> <input class="almostInvisibleInputNoHover" style="font-size: 1.2em; width: 22%; display: inline;" type="text" name="emailAddressDescriptions[]" id="emailAddressDescription:::'.htmlspecialchars($emailAddressId).'" placeholder="Note" value="'.htmlspecialchars($emailInfo->description).'"><br><br>';
    }

    // One at the end to add a new email
	echo '<span style="border-radius: .3em; padding: .6em;" class="defaultMainShadows"><input class="invisibleInputNoHover" style="font-size: 1.2em; width: 90%;" type="text" name="newEmailAddress" id="newEmailAddress" placeholder="Add email..."></span>';

?>
