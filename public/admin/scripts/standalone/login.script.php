<?php

    require_once '../../../php/startSession.php';
    require_once '../../../../lib/manager/adminLoginManager.php';

    $adminLoginManager = new adminLoginManager();

    $result = 'incomplete';

    // Check if input is set and valid
    if ( (!isset($_POST['usernameEmail']) || strlen($_POST['usernameEmail']) <= 5) || (!isset($_POST['password']) || strlen($_POST['password']) <= 5) ) {
        $result = 'inputInvalid';
    }

    if ($result == 'incomplete') {
        $usernameCheck = $adminLoginManager->usernameExists($_POST['usernameEmail'], true); // These return the id so we know what admin they belong to
        $emailCheck = $adminLoginManager->emailExists($_POST['usernameEmail'], true);

        // Check if email or username exists
        if (!$usernameCheck && !$emailCheck) {
            $result = 'noEmailUsername';
        }
    }

    if ($result == 'incomplete') {
        if ($usernameCheck) {
            $adminIdToCheck = $usernameCheck;
        } else {
            $adminIdToCheck = $emailCheck;
        }

        //Check if the username/email and password match

        if (password_verify($_POST['password'], $adminLoginManager->getPassword($adminIdToCheck))) {
            // Login the user
            $adminLoginManager->login($adminIdToCheck);
            $result = 'success';

            // if remember me is checked, save the login and set a cookie
            if (false) { // Will do later
                // Set a saved login
            }
        } else {
            $result = 'password';
        }
    }
    
    // Log the attempt
    require_once '../../../../lib/table/adminLoginAttempt.php';
    require_once '../../../../lib/etc/getClientIpAddress.php';
    
    $attempt = new adminLoginAttempt();
    if (isset($adminIdToCheck)) {
        $attempt->adminId = $adminIdToCheck;
    }
    $attempt->clientIp = getClientIpAddress();
    $attempt->result = $result;
    var_dump($attempt->set());

    // Redirect
    if ($result == 'success') {
        header("location: ../../");
        exit();
    } else {
        header("location: ../../login?e=".urlencode($result));
        exit();
    }

?>
