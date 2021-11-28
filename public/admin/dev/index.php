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

                    require_once '../../../lib/table/customer.php';

                    $customer = new customer('357461a2cd78d9d08');

                    // $customer->firstName = 'Susybell';
                    // $customer->lastName = 'Whistlebritches';

                    // $customer->billAddress1 = '3505 N. Quebec St.';
                    // $customer->billAddress2 = 'PO Box somewhere';
                    // $customer->billState = 'Virginia';
                    // $customer->billCity = 'Arlington';
                    // $customer->billZipCode = 22207;


                    // $customer->set();

                    echo '<p>'.htmlspecialchars($customer->customerId).'</p>';
                    echo '<p>'.htmlspecialchars($customer->firstName).' '.htmlspecialchars($customer->firstName).'</p>';
                    echo '<p>'.htmlspecialchars($customer->billAddress1).'</p>';
                    echo '<p>'.htmlspecialchars($customer->billAddress2).'</p>';
                    echo '<p>'.htmlspecialchars($customer->billState).'</p>';
                    echo '<p>'.htmlspecialchars($customer->billCity).'</p>';
                    echo '<p>'.htmlspecialchars($customer->billZipCode).'</p>';

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
