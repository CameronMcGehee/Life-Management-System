<?php

    require_once '../../../../php/startSession.php';

    // Make sure that an admin is logged in

    if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
        echo 'unauthorized';
        exit();
    }

    // Verify that variables are set

    if (!isset($_POST['authToken']) || !isset($_POST['estimates']) || gettype($_POST['estimates']) != 'array') {
        echo 'inputInvalid';
        die();
    }

    // Validate the auth token
	require_once '../../../../../lib/etc/authToken/validateAuthToken.php';
	if (!validateAuthToken($_POST['authToken'], 'deleteEstimates')) {
		echo 'tokenInvalid';
		exit();
	}

    require_once '../../../../../lib/table/estimate.php';

    foreach ($_POST['estimates'] as $estimateId) {
        // Make sure that the estimateId exists
        $currentEstimate = new estimate($estimateId);
        if (!$currentEstimate->existed) {
            echo 'noEstimate';
            die();
        }
        // Check if current business has access to that estimate
        if ($currentEstimate->businessId !== $_SESSION['ultiscape_businessId']) {
            echo 'unauthorized';
            die();
        }

        // Delete any linked approvals
        // require_once '../../../../../lib/database.php';
        // $db = new database();
        // $db->delete('estimateApproval', "WHERE estimateId = '$currentEstimate->estimateId'", 1);

        // delete the estimate
        $currentEstimate->delete();
    }

    echo 'success';

    // Use the auth token
	require_once '../../../../../lib/etc/authToken/useAuthToken.php';
	useAuthToken($_POST['authToken'], 'deleteEstimates');

?>
