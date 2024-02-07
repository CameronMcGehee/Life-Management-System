<?php

    require_once '../../../php/startSession.php';
    session_unset();

    $CurPageURL = '';

    if (isset($_COOKIE['lifems_lastWorkspaceId'])) {
        $CurPageURL = "?workspace=".$_COOKIE['lifems_lastWorkspaceId'];
    }

    header("location: ../../login".$CurPageURL);
    exit();

?>
