<?php

    require_once '../../../../php/startSession.php';

    $result = 'incomplete'; // Incomplete means there has not yet been an error so the script will continue.

    // Check if input is set and valid
    if ( (!isset($_POST['usernameEmail']) || strlen($_POST['usernameEmail']) < 6) || (!isset($_POST['password']) || strlen($_POST['password']) < 6) || (!isset($_POST['authToken']) || strlen($_POST['authToken']) < 17)) {
        $result = 'inputInvalid';
    }

    if ($result == 'incomplete') {
        // Verify the auth token
        require_once '../../../../../lib/etc/authToken/useAuthToken.php';
        if (!useAuthToken($_POST['authToken'], 'adminLogin')) {
            $result = 'tokenInvalid';
        } else {
            // Since this script is run relatively often, purge old authTokens to keep the table small whenever the token is valid
            require_once '../../../../../lib/etc/authToken/purgeAuthTokens.php';
            purgeAuthTokens($ULTISCAPECONFIG['authTokenDefaultPurge']);
        }
    }

    if ($result == 'incomplete') {
        // If the username or email exists, get the adminId associated with them
        require_once '../../../../../lib/database.php';
        $db = new database();
        $usernameCheck = $db->select('admin', 'adminId', "WHERE LOWER(username) = '".strtolower($db->sanitize($_POST['usernameEmail']))."' LIMIT 1");
        $emailCheck = $db->select('admin', 'adminId', "WHERE LOWER(email) = '".strtolower($db->sanitize($_POST['usernameEmail']))."' LIMIT 1");

        // Check if email or username exists
        if (!$usernameCheck && !$emailCheck) {
            $result = 'noEmailUsername';
        }
    }

    if ($result == 'incomplete') {
        if ($usernameCheck) {
            $matchedAdminId = $usernameCheck[0]['adminId'];
        } else {
            $matchedAdminId = $emailCheck[0]['adminId'];
        }

        // Get the admin as an object
        require_once '../../../../../lib/table/admin.php';
        $matchedAdmin = new admin($matchedAdminId);
        if (!$matchedAdmin->existed) {
            $result = 'noEmailUsername';
        }
    }

    if ($result == 'incomplete') {
        //Check if the username/email and password match
        if (password_verify($_POST['password'], $matchedAdmin->password)) {
            // Login the user
            $_SESSION['ultiscape_adminId'] = $matchedAdminId;
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
    require_once '../../../../../lib/table/adminLoginAttempt.php';
    $attempt = new adminLoginAttempt();
    if (isset($matchedAdminId)) {
        $attempt->adminId = $matchedAdminId;
    }
    $attempt->result = $result;
    $attempt->set();

    // Redirect
    if ($result == 'success') {
        header("location: ../../../");
        exit();
    } else {
        header("location: ../../?e=".urlencode($result));
        exit();
    }

?>
