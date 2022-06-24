<?php

    function customerHeaderRedirect(string $loginLocation = './') {
        if (!isset($_SESSION['ultiscape_customerId'])) {

            unset($_SESSION['ultiscape_businessId']);
            // Get the current url, then redirect with that ref url

            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
            $CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  

            if (isset($_COOKIE['ultiscape_lastBusinessId'])) {
                $CurPageURL .= "&business=".$_COOKIE['ultiscape_lastBusinessId'];
            }

            header("location: ".$loginLocation."login?refUrl=".$CurPageURL);
        } else if (isset($_SESSION['ultiscape_businessId'])) {
            // If the customerId and businessId are set, check if the customer actually belongs to that business. If they don't, unset the variables and then redirect

            
        }
    }

?>
