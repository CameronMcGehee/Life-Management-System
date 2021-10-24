<?php

    require_once '../../../php/startSession.php';
    require_once '../../../../lib/manager/adminLoginManager.php';

        $adminLoginManager = new adminLoginManager();

        $adminLoginManager->logout();

    header("location: ../../login");
    exit();

?>
