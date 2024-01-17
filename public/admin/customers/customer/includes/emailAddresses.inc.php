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

	if ($currentCustomer->businessId != $_SESSION['ultiscape_businessId']) {
        echo "unauthorized";
		exit();
    }

    // Get all the emails in the system
    $currentCustomer->pullEmailAddresses('ORDER BY dateTimeAdded ASC');

	// Render the list of email inputs and buttons
	require_once '../../../../../lib/table/customerEmailAddress.php';
    foreach ($currentCustomer->emailAddresses as $emailAddressId) {
        $emailInfo = new customerEmailAddress($emailAddressId);
        echo '<input type="hidden" name="emailAddressIds[]" value="'.htmlspecialchars(strval($emailAddressId)).'">
		';
		echo '<input placeholder="Email..." class="almostInvisibleInputNoHover" style="font-size: 1.2em; width: 65%; display: inline;" type="text" name="emailAddresses[]" id="emailAddress'.htmlspecialchars(strval($emailAddressId)).'" value="'.htmlspecialchars(strval($emailInfo->email)).'">
		';
		echo ' <input class="almostInvisibleInputNoHover" style="font-size: 1.2em; width: 22%; display: inline;" type="text" name="emailAddressDescriptions[]" id="emailAddressDescription'.htmlspecialchars(strval($emailAddressId)).'" placeholder="Note" value="'.htmlspecialchars(strval($emailInfo->description)).'">
		';
		echo ' <span id="deleteEmailAddress:::'.htmlspecialchars(strval($emailAddressId)).'" class="extraSmallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../../images/ultiscape/icons/trash.svg"></span>
		';
		echo '<span id="emailAddress'.htmlspecialchars(strval($emailAddressId)).'Error" class="underInputError" style="display: none;"><br><br>Enter a valid address.</span><br><br>
		';
	}

    // One at the end to add a new email
	echo '<span style="border-radius: .3em; padding: .6em;" class="defaultMainShadows"><input class="invisibleInputNoHover" style="font-size: 1.2em; width: 90%;" type="text" name="newEmailAddress" id="newEmailAddress" placeholder="Add email..."></span>';
	echo '<span id="newEmailAddressError" class="underInputError" style="display: none;"><br><br>Enter a valid address.</span>';

?>
