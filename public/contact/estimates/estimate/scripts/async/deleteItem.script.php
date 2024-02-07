<?php

	require_once '../../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['lifems_adminId']) || !isset($_SESSION['lifems_workspaceId'])) {
		echo 'unauthorized';
		exit();
	}

    if (!isset($_POST['itemId'])) {
		echo 'noId';
		exit();
	}

	require_once '../../../../../../lib/table/estimateItem.php';
	$currentItem = new estimateItem($_POST['itemId']);

	// Verify the estimate item belongs to the workspace that is signed in
	if (!$currentItem->existed || $currentItem->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'noEstimate';
        exit();
    }

    // Validate the auth token
	require_once '../../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!isset($_POST['deleteItemAuthToken']) || !validateAuthToken($_POST['deleteItemAuthToken'], 'deleteEstimateItem')) {
		echo 'tokenInvalid';
		exit();
	}

    if ($currentItem->workspaceId != $_SESSION['lifems_workspaceId']) {
        echo 'unauthorized';
        exit();
    }

    echo $currentItem->estimateItemId.'::::';

    // Delete the address
    if ($currentItem->delete()) {
        echo 'success';
    }

	// Use the auth token
	require_once '../../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['deleteItemAuthToken'], 'deleteEstimateItem');
	
	// Success if gotten to bottom of script
	echo 'success';
	exit();

?>
