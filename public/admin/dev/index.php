<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'DEV SPACE', 'DEVELOPMENT TESTING SPACE');

    echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

</head>

<body>
    <div class="cmsBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../../');

            require_once '../../../lib/render/ui/popupsHandler.php';

            $popupsHandler = new popupsHandler('mainPopupsHandler', ["popups" => ['devWelcomeInfo'], 'class' => 'styledText spacedText defaultMainShadows', 'style' => 'width: 500em;']);

            $popupsHandler->render();

            echo $popupsHandler->output;
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php

                    // require_once '../../../lib/table/customer.php';
                    // require_once '../../../lib/table/customerEmailAddress.php';
                    // require_once '../../../lib/table/customerPhoneNumber.php';
                    // $lastNames = require_once '../../../lib/arrays/lastNames.php';
                    // $firstNames = require_once '../../../lib/arrays/firstNames.php';

                    // for ($x = 0; $x <= 1000; $x++) {
                    //     $customer = new customer();

                    //     $customer->firstName = $firstNames[rand(0, count($firstNames)-1)];
                    //     $customer->lastName = $lastNames[rand(0, count($lastNames)-1)];
    
                    //     $customer->billAddress1 = '5555 N. Example St.';
                    //     $customer->billAddress2 = NULL;
                    //     $customer->billState = 'NA';
                    //     $customer->billCity = 'Citytown';
                    //     $customer->billZipCode = 55555;
    
                    //     $customer->set();
    
    
                    //     $customerEmailAddress = new customerEmailAddress();
    
                    //     $customerEmailAddress->customerId = $customer->customerId;
                    //     $customerEmailAddress->email = 'email@example.com';
    
                    //     $customerEmailAddress->set();
    
    
                    //     $customerPhoneNumber = new customerPhoneNumber();
    
                    //     $customerPhoneNumber->customerId = $customer->customerId;
                    //     $customerPhoneNumber->phonePrefix = '1';
                    //     $customerPhoneNumber->phone1 = (string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9);
    
                    //     $customerPhoneNumber->set();
                    // }

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/table/admin.php';

                    // $admin = new admin('40261a2e60e2309b');

                    // $admin->firstName = 'Cameron';
                    // $admin->lastName = 'McGehee';

                    // $admin->set();

                    // echo '<p>'.htmlspecialchars($admin->adminId).'</p>';
                    // echo '<p>'.htmlspecialchars($admin->firstName).' '.htmlspecialchars($admin->lastName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/table/business.php';

                    // $business = new business('691661a305e702984');

                    // $business->displayName = "C's Test Business";
                    // $business->adminDisplayName = 'Test Business';

                    // $business->set();

                    // echo '<p>'.htmlspecialchars($business->businessId).'</p>';
                    // echo '<p>'.htmlspecialchars($business->displayName).' '.htmlspecialchars($business->adminDisplayName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/render/customer/customerTable.php';

                    // $customerTable = new customerTable();

                    // $customerTable->rootPathPrefix = '../';

                    // $customerTable->render();

                    // echo $customerTable->output;

                    // // echo '<p>'.htmlspecialchars($business->businessId).'</p>';
                    // // echo '<p>'.htmlspecialchars($business->displayName).' '.htmlspecialchars($business->adminDisplayName).'</p>';


                ?>

                <!-- <a class="hoverTip">Tooltip Text<span>This is the text that will display in the tooltip pop-up.</span></a> -->
            </div>
        </div>
        
        <?php 
            echo $adminUIRender->renderAdminFooter();
        ?>

        <?php 
            echo $adminUIRender->renderAdminMobileNavBar('../../');
        ?>

    </div>

    <?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
    echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
