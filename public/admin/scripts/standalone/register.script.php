<?php

    require_once '../../../php/startSession.php';

    // Error checking
    // Input is set and valid
    
    if (!isset($_POST['firstName']) || strlen($_POST['firstName']) < (int)$ULTISCAPECONFIG['adminFirstNameMinLength']) {
        header("location: ../../login?e=firstNameInput");
        exit();
    }
    if (!isset($_POST['lastName']) || strlen($_POST['lastName']) < (int)$ULTISCAPECONFIG['adminLastNameMinLength']) {
        header("location: ../../login?e=lastNameInput");
        exit();
    }

    if (!isset($_POST['username']) || strlen($_POST['username']) < (int)$ULTISCAPECONFIG['adminUsernameMinLength']) {
        header("location: ../../login?e=usernameInput");
        exit();
    }

    if (!isset($_POST['password']) || strlen($_POST['password']) < (int)$ULTISCAPECONFIG['adminPasswordMinLength']) {
        header("location: ../../login?e=passwordInput");
        exit();
    }

    if (!isset($_POST['nameTitle'])) {
        $nameTitle = '';
    } elseif ($_POST['nameTitle'] == '') {
        if (strlen($_POST['nameTitle']) < (int)$ULTISCAPECONFIG['adminnameTitleMinLength']) {
            header("location: ../../login?e=nameTitleInput");
            exit();
        }
    }

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    require_once '../../../../lib/app/adminLoginManager.php';
    $adminLoginManager = new adminLoginManager();

    $usernameCheck = $adminLoginManager->usernameExists($_POST['usernameEmail'], true);
    $emailCheck = $adminLoginManager->emailExists($_POST['usernameEmail'], true);

    // If username exists and input is valid, check if the username/email and password match

    if (password_verify($_POST['password'], $adminLoginManager->getPassword($adminIdToCheck))) {
        $adminLoginManager->login($adminIdToCheck);
        $adminLoginManager->setBusiness('33kkgghhbb98fhdex');
    } else {
        header("location: ../../login"); //?username=".$createdAdminId);
        exit();
    }

    header("location: ../../");
    exit();

?>
