<?php

    function adminHeaderRedirect(string $loginLocation = './', string $workspaceSelectLocation = './') {
        if (!isset($_SESSION['lifems_adminId'])) {
            header("location: ".$loginLocation."login");
        } elseif (!isset($_SESSION['lifems_workspaceId'])) {
            header("location: ".$workspaceSelectLocation."selectworkspace");
        }
    }

?>
