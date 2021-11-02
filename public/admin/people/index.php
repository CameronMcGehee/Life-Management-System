<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'People');

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

        <div class="cmsMainContentWrapper styledText spacedText textColorThemePeople">
            <div style="margin-left: 2em; margin-right: 2em;">
                <h1>People</h1>
                <p>People are the basis for all transactions in your business. Make sure to keep updated information on all of them.</p>
            </div>

            <br><hr>

            <div style="margin-left: 2em; margin-right: 2em;">

                <h2>Customers</h2>
                <!-- <p>Customers can have invoices, estimates, properties, jobs, and more.</p> -->
                <h2>Staff</h2>
                <!-- <p>Staff can have assigned jobs, be members of crews, and can sign into their own section of UltiScape to view their jobs.</p> -->
                <h2>Co-Admins</h2>
                <!-- <p>Co-Admins can manage the UltiScape CMS just like you can, but you still maintain ownership and can control what they can change.</p> -->
                
            </div>
        </div>
        
        <?php 
            echo $adminUIRender->renderAdminFooter('../../');
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
