<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../', '../');

    require_once '../../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'People');

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

        <div class="cmsMainContentWrapper styledText textColorThemePeople">
            <div class="marginLeftRight90">
                <h1>People</h1>
                <p>People are the basis for all transactions in your business. Make sure to keep updated information on all of them.</p>
            </div>

            <br><hr>

            <div class="marginLeftRight90">

                <h2>Customers</h2>
                <!-- <p>Customers can have invoices, estimates, properties, jobs, and more.</p> -->
                <h2>Staff</h2>
                <!-- <p>Staff can have assigned jobs, be members of crews, and can sign into their own section of UltiScape to view their jobs.</p> -->
                <h2>Co-Admins</h2>
                <!-- <p>Co-Admins can manage the UltiScape CMS just like you can, but you still maintain ownership and can control what they can change.</p> -->
                
            </div>
        </div>
        
        <?php 
            echo $renderer->renderAdminFooter('../../');
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
