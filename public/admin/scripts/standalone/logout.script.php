<?php

    require_once '../../../php/startSession.php';
    session_unset();
    header("location: ../../login");

?>
