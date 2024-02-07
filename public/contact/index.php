<?php

    // REDIRECT PAGE - DOES NOT DISPLAY ANYTHING

    // _    _ _ _   _  _____                          _____ __  __  _____ 
    // | |  | | | | (_)/ ____|                        / ____|  \/  |/ ____|
    // | |  | | | |_ _| (___   ___ __ _ _ __   ___   | |    | \  / | (___  
    // | |  | | | __| |\___ \ / __/ _` | '_ \ / _ \  | |    | |\/| |\___ \ 
    // | |__| | | |_| |____) | (_| (_| | |_) |  __/  | |____| |  | |____) |
    //  \____/|_|\__|_|_____/ \___\__,_| .__/ \___|   \_____|_|  |_|_____/ 
    //                                 | |                                 
    //                                 |_|                                      
    //                  Copyright McGehee Enterprises 2021

    // If contact has a login saved on their computer, and that computer's IP address is still the same, and it has been less than 3 days since the last login, log them in as that session

    // WILL DO THIS LATER - NEEDS DATABASE TABLE ADDED

    // If contact is logged in and a workspace is selected, go to the home page of that workspace
    // If not, check if contact is logged in. If not, take them to the contact login page. If they are logged in but no workspace has been selected, take them to the workspace selection page.
    
    require_once '../php/startSession.php';

    if (isset($_SESSION['lifems_contactId'])) {
        header("location: ./home");
    } else {
        if (isset($_GET['workspace'])) {
            header("location: ./login?workspace=".$_GET['workspace']);
        } else {
            header("location: ./login");
        }
    }

?>
