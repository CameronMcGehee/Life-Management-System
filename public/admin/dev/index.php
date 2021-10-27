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

                    $customer = new customer();

                    echo '<p>Customer: ';
                    print_r($customer);
                    echo '</p>';

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
