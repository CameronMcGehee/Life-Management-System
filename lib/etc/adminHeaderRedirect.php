<?php

    function adminHeaderRedirect(string $loginLocation = './', string $businessSelectLocation = './') {
        if (!isset($_SESSION['ultiscape_adminId']) && !isset($_SESSION['ultiscape_businessId'])) {
            header("location: ".$loginLocation."login");
        } elseif (isset($_SESSION['ultiscape_adminId']) && !isset($_SESSION['ultiscape_businessId'])) {
            header("location: ".$businessSelectLocation."selectbusiness");
        }
    }

?>
