<?php

	require_once '../../../../php/startSession.php';

	// Make sure that an admin is logged in

	if (!isset($_SESSION['ultiscape_adminId']) || !isset($_SESSION['ultiscape_businessId'])) {
		echo 'error (1)';
		exit();
	}

	if (!isset($_POST['jobId'])) {
		echo 'error (2)';
		exit();
	}

	require_once '../../../../../lib/table/job.php';
	$currentJob = new job($_POST['jobId']);

	if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        echo "unauthorized";
		exit();
    }

    // Get all the emails in the system
    $currentJob->pullCrews();

	// Render the list of email inputs and buttons
	require_once '../../../../../lib/table/crew.php';
    foreach ($currentJob->crews as $crewId) {
        $crewInfo = new crew($crewId);
		echo '<p>'.$crewInfo->name.'</p>';
		echo ' <span id="deleteJobCrew:::'.htmlspecialchars($crewId).'" class="extraSmallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../../images/ultiscape/icons/cross.svg"></span>
		';
	}

    // One at the end to add a new email
	echo 'Select...';
	echo '<span id="newCrewsError" class="underInputError" style="display: none;"><br><br>Select a crew member from the list.</span>';

?>
