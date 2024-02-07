<?php

    if (!isset($_GET['id']) || $_GET['id'] == '' || empty($_GET['id'])) {
        exit("Required variable not supplied. Cannot proceed.");
    }

    require_once '../../../../php/startSession.php';

    require_once '../../../../../lib/table/workspace.php';

    // Make object from the provided id
    $workspace = new workspace($_GET['id']);

    // Check if the workspace exists. If it doesn't, redirect to the workspace selection page.
    if (!$workspace->existed) {
        header("location: ../../");
        exit();
    }

    // Make sure that the current admin is an admin of the workspace or owns the workspace
    if ($workspace->ownerAdminId != $_SESSION['lifems_adminId']) {
        $workspace->pullAdmins();
        if (!in_array($_SESSION['lifems_adminId'], $workspace->admins)) {
            header("location: ../../");
            exit();
        }
    }

    // Set the selected workspace to the workspaceId and redirect to the overview page
    $_SESSION['lifems_workspaceId'] = $workspace->workspaceId;
    header("location: ../../../workspaceoverview");
    exit();

?>
