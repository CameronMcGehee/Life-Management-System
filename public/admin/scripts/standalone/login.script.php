<?php

    require_once '../../../php/startSession.php';
    require_once '../../../../lib/manager/adminLoginManager.php';

    $adminLoginManager = new adminLoginManager();

    // Check if input is set and valid
    if ( (!isset($_POST['usernameEmail']) || strlen($_POST['usernameEmail']) <= 5) || (!isset($_POST['password']) || strlen($_POST['password']) <= 5) ) {
        header("location: ../../login?e=input");
        exit();
    }

    $usernameCheck = $adminLoginManager->usernameExists($_POST['usernameEmail'], true); // These return the id so we know what admin they belong to
    $emailCheck = $adminLoginManager->emailExists($_POST['usernameEmail'], true);
    
    // Chec if email or username exists
    if (!$usernameCheck && !$emailCheck) {
        header("location: ../../login?e=noEmailUsername");
        exit();
    }

    if ($usernameCheck) {
        $adminIdToCheck = $usernameCheck;
    } else {
        $adminIdToCheck = $emailCheck;
    }

    //Check if the username/email and password match

    if (password_verify($_POST['password'], $adminLoginManager->getPassword($adminIdToCheck))) {
        $adminLoginManager->login($adminIdToCheck);
    } else {
        header("location: ../../login?e=password");
        exit();
    }

    header("location: ../../");
    exit();

?>
