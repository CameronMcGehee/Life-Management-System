<?php

    if (!isset($_GET['id']) || $_GET['id'] == '' || empty($_GET['id'])) {
        exit("Required variable not supplied. Cannot proceed.");
    }

    require_once '../../../../php/startSession.php';

    require_once '../../../../../lib/table/business.php';

    // Make object from the provided id
    $business = new business($_GET['id']);

    // Check if the business exists. If it doesn't, redirect to the business selection page.
    if (!$business->existed) {
        header("location: ../../");
        exit();
    }

    // Make sure that the current admin is an admin of the business or owns the business
    if ($business->ownerAdminId != $_SESSION['ultiscape_adminId']) {
        $business->pullAdmins();
        if (!in_array($_SESSION['ultiscape_adminId'], $business->admins)) {
            header("location: ../../");
            exit();
        }
    }

    // Set the selected business to the businessId and redirect to the overview page
    $_SESSION['ultiscape_businessId'] = $business->businessId;
    header("location: ../../../businessoverview");
    exit();

?>
