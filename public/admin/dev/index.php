<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'DEV SPACE', 'DEVELOPMENT TESTING SPACE');

    echo $adminUIRender->renderAdminTopBarDropdownScripts('../../');

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

        <div class="cmsMainContentWrapper  styledText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php

                    require_once '../../../lib/class/customer.php';
                    require_once '../../../lib/class/admin.php';
                    require_once '../../../lib/class/business.php';

                    // $customer = new customer();

                    // $customer->surname = "Mrs.";
                    // $customer->firstName = 'Lois';
                    // $customer->lastName = "McGehee";
                    // $customer->billAddress1 = "3505 N. Quebec St.";
                    // $customer->billAddress2 = NULL;
                    // $customer->billCity = 'Arlington';
                    // $customer->billState = 'Virginia';
                    // $customer->billZipCode = '22207';
                    // $customer->allowCZSignIn = '1';
                    // $customer->password = 'password123';
                    // $customer->discountPercent = '50';

                    // echo '<p>Customer: ';
                    // print_r($customer);
                    // echo '</p>';

                    $business = new business($_SESSION['ultiscape_businessId']);
                    var_dump($business->pullAdmins());
                    echo '<p>Business: ';
                    var_dump($business->admins);
                    echo '</p>';

                    // $admin = new admin();
                    // echo '<p>Admin: ';
                    // print_r($admin);
                    // echo '</p>';

                    // Remove all saved logins

                    // foreach ($customer->savedLogins as $savedLogin => $attributes) {
                    //     $customer->removeSavedLogin($savedLogin);
                    // }

                    // Set
                    // var_dump($customer->set());

                    // $customer->pullSavedLogins();
                    // echo '<p>Login Attempts after insertion: ';
                    // var_dump($customer->savedLogins);
                    // echo '</p>';

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
