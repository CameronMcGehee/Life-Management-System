<?php

    function contactHeaderRedirect(string $loginLocation = './') {
        if (!isset($_SESSION['lifems_contactId'])) {

            unset($_SESSION['lifems_workspaceId']);
            // Get the current url, then redirect with that ref url

            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  
            $CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];  

            if (isset($_COOKIE['lifems_lastWorkspaceId'])) {
                $CurPageURL .= "&workspace=".$_COOKIE['lifems_lastWorkspaceId'];
            }

            header("location: ".$loginLocation."login?refUrl=".$CurPageURL);
        } else if (isset($_SESSION['lifems_workspaceId'])) {
            // If the contactId and workspaceId are set, check if the contact actually belongs to that workspace. If they don't, unset the variables and then redirect

            
        }
    }

?>
