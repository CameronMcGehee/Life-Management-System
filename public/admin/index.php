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

    // If user has a login saved on their computer, and that computer's IP address is still the same, and it has been less than 3 days since the last login, log them in as that session

    // WILL DO THIS LATER - NEEDS DATABASE TABLE ADDED

    // If user is logged in and a business is selected, go to the overview page of that business
    // If not, check if user is logged in. If not, take them to the user login page. If they are logged in but no business has been selected, take them to the business selection page.
    
    require_once '../php/startSession.php';

    if (isset($_SESSION['ultiscape_adminId']) && isset($_SESSION['ultiscape_businessId'])) {
        header("location: ./overview");
    } elseif (isset($_SESSION['ultiscape_adminId'])) {
        header("location: ./selectbusiness");
    } else {
        header("location: ./login");
    }

?>
