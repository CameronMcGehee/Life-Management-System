<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./login', './businessselect');

    require_once '../../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'DEV SPACE', 'DEVELOPMENT TESTING SPACE');

    echo $renderer->renderAdminTopBarDropdownScripts('../../');

?>

</head>

<body>
    <div class="cmsBodyWrapper">

        <?php 
            echo $renderer->renderAdminTopBar('../../');
        ?>

        <?php 
            echo $renderer->renderAdminSideBar('../../');
        ?>

        <div class="cmsMainContentWrapper  styledText textColorThemeGray">
            <div class="margin2em">
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
            echo $renderer->renderAdminFooter();
        ?>

        <?php 
            echo $renderer->renderAdminMobileNavBar('../../');
        ?>

    </div>

    <?php
		echo $renderer->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
    echo $renderer->renderAdminHtmlBottom('../../');
?>
