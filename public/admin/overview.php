<?php

    // Start Session
    require_once '../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./', './');

    require_once '../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../', 'Overview', 'Overview of your UltiScape Business.');

    echo $adminUIRender->renderAdminTopBarDropdownScripts('../');

?>

</head>

<body>
    <div class="cmsBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../');
        ?>

    <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php
                    require_once '../../lib/table/business.php';
                    $business = new business();
                ?>

                <h2>Overview of <span style="font-size: .8em;"><?php echo htmlspecialchars($business->adminDisplayName); ?></span></h2>
                <p>A quick glance at your business.</p>
            </div>

            <br><hr>

            <div style="margin-left: 2em; margin-right: 2em;">
                <h2>Content...</h2>
            </div>
        </div>
        
        <?php 
            echo $adminUIRender->renderAdminFooter('../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminMobileNavBar('../');
        ?>

    </div>

    <?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../');
	?>
</body>
<?php 
    echo $adminUIRender->renderAdminHtmlBottom('../');
?>
