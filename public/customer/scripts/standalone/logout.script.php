<?php

    require_once '../../../php/startSession.php';
    session_unset();

    $CurPageURL = '';

    if (isset($_COOKIE['ultiscape_lastBusinessId'])) {
        $CurPageURL = "?business=".$_COOKIE['ultiscape_lastBusinessId'];
    }

    header("location: ../../login".$CurPageURL);
    exit();

?>
