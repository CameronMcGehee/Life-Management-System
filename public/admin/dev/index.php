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
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php

                    // require_once '../../../lib/table/customer.php';

                    // $customer = new customer();

                    // $customer->firstName = 'Susybell';
                    // $customer->lastName = 'Whistlebritches';

                    // $customer->billAddress1 = '3505 N. Quebec St.';
                    // $customer->billAddress2 = 'PO Box somewhere';
                    // $customer->billState = 'Virginia';
                    // $customer->billCity = 'Arlington';
                    // $customer->billZipCode = NULL;


                    // $customer->set();

                    // echo '<p>'.htmlspecialchars($customer->customerId).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->lastName).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->billAddress1).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->billAddress2).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->billState).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->billCity).'</p>';
                    // echo '<p>'.htmlspecialchars($customer->billZipCode).'</p>';

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

                ?>
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
