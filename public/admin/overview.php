<?php

    // Start Session
    require_once '../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../lib/app/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./login', './businessselect');

    require_once '../../lib/app/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../', 'Overview', 'Overview of your UltiScape Business.');

    echo $renderer->renderAdminTopBarDropdownScripts('../');

?>

</head>

<body>
    <div class="cmsBodyWrapper">

        <?php 
            echo $renderer->renderAdminTopBar('../');
        ?>

        <?php 
            echo $renderer->renderAdminSideBar('../');
        ?>

    <div class="cmsMainContentWrapper styledText textColorThemeGray">
            <div class="margin2em">
                <?php
                    require_once '../../lib/app/businessManager.php';
                    $businessManager = new businessManager();
                ?>

                <h2>Overview of <span style="font-size: .8em;"><?php echo strip_tags($businessManager->getAdminDisplayName($_SESSION['ultiscape_businessId'])); ?></span></h2>
                <p>A quick glance at your business.</p>
            </div>

            <br><hr>

            <div class="margin2em">
                <h2>Content...</h2>
            </div>
        </div>
        
        <?php 
            echo $renderer->renderAdminFooter('../');
        ?>

        <?php 
            echo $renderer->renderAdminMobileNavBar('../');
        ?>

    </div>

    <?php
		echo $renderer->renderAdminTopBarDropdowns('../');
	?>
</body>
<?php 
    echo $renderer->renderAdminHtmlBottom('../');
?>