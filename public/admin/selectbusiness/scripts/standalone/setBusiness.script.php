<?php

    if (!isset($_GET['id']) || $_GET['id'] == '' || empty($_GET['id'])) {
        exit("Required variable not supplied. Cannot proceed.");
    }

    require_once '../../../../php/startSession.php';

    require_once '../../../../../lib/class/business.php';

    // Make object from the provided id
    $business = new business($_GET['id']);

    // Check if the business exists. If it doesn't, redirect to the business selection page.
    if (!$business->existed) {
        header("location: ../../");
        exit();
    }

    // Since it does exist, set the selected business to the businessId and redirect to the overview page

    $_SESSION['ultiscape_businessId'] = $business->businessId;
    header("location: ../../../overview");
    exit();

?>