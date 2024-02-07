<?php

    require_once '../../../../php/startSession.php';

    $result = 'incomplete'; // Incomplete means there has not yet been an error so the script will continue.

    // Make sure that an admin is logged in

    if (!isset($_SESSION['lifems_adminId'])) {
        header("location: ../../../login");
        exit();
    }

    // Check if input is set and valid
    if ( (!isset($_POST['workspaceName']) || strlen($_POST['workspaceName']) < $lifemsConfig['workspaceNameMinLength']) || (!isset($_POST['authToken']) || strlen($_POST['authToken']) < 17)) {
        $result = 'inputInvalid';
    }

    if ($result == 'incomplete') {
        // Verify the auth token
        require_once '../../../../../lib/etc/authToken/useAuthToken.php';
        if (!useAuthToken($_POST['authToken'], 'createWorkspace')) {
            $result = 'tokenInvalid';
        }
    }

    if ($result == 'incomplete') {
        // Create the workspace
        require_once '../../../../../lib/table/workspace.php';
        $newWorkspace = new workspace();
        $newWorkspace->displayName = $_POST['workspaceName'];
        $newWorkspace->adminDisplayName = $_POST['workspaceName'];
        
        if ($newWorkspace->set() !== true) {
            $result = 'setError';
        }
    }

    if ($result == 'incomplete') {
        require_once '../../../../../lib/database.php'; $db = new database();
        // Link the current admin to the new workspace
        if ($db->insert("adminWorkspaceBridge", array("workspaceId" => $newWorkspace->workspaceId, "adminId" => $_SESSION['lifems_adminId'], "adminIsOwner" => 1, "dateTimeAdded" => date('Y-m-d H:i:s')))) {
            // Set the current workspace to the new one
            $_SESSION['lifems_workspaceId'] = $newWorkspace->workspaceId;
        } else {
            $result = 'linkError';
            $newWorkspace->delete();
        }
    }

    if ($result == 'incomplete') {
        require_once '../../../../../lib/table/paymentMethod.php';
        // Add default payment methods
        $paymentMethod = new paymentMethod();
        $paymentMethod->name = 'Cash';
        $paymentMethod->set();

        $paymentMethod = new paymentMethod();
        $paymentMethod->name = 'Check';
        $paymentMethod->set();

        $paymentMethod = new paymentMethod();
        $paymentMethod->name = 'PayPal';
        $paymentMethod->set();

        $result = 'success';
    }
    
    // Redirect
    if ($result == 'success') {
        if (isset($_POST['takeToEditPage'])) {
            header("location: ../../../workspacesettings?popup=workspaceCreated&workspaceCreatedworkspaceName=".$newWorkspace->displayName);
            exit();
        } else {
            header("location: ../../../workspaceoverview?popup=workspaceCreated&workspaceCreatedworkspaceName=".$newWorkspace->displayName);
            exit();
        }
    } else {
        header("location: ../../../createworkspace?e=".urlencode($result));
        exit();
    }

?>
