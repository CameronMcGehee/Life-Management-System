<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['instanceId'])) {
		echo 'error (2)';
		exit();
	}

	if (!isset($_POST['customerId'])) {
		echo 'error (3)';
		exit();
	}

	require_once '../../../../../lib/database.php';
	$db = new database();
	require_once '../../../../../lib/table/customer.php';
	require_once '../../../../../lib/table/jobInstanceException.php';
	require_once '../../../../../lib/render/input/propertySelector.php';
	
	$currentCustomer = new customer($_POST['customerId']);
	$currentInstance = new jobInstanceException($_POST['instanceId']);

	if (!$currentCustomer->existed || !$currentInstance->existed) {
		echo 'error (4)';
		exit();
	}

	if ($currentInstance->businessId != $_SESSION['ultiscape_businessId']) {
		echo "unauthorized";
		exit();
	}

	echo '<label for="propertySelector"><p>Property</p></label>';

	$propertySelector = new propertySelector("propertySelector", ["name" => 'property', "queryParams" => "AND customerId = '".$db->sanitize($_POST['customerId'])."'", "selectedId" => $currentInstance->linkedToPropertyId]);
	$propertySelector->render();
	echo $propertySelector->output;

	echo '<span id="customerError" class="underInputError" style="display: none;"><br>Select a property.</span>';

?>