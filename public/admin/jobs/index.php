<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../', '../');

    require_once '../../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'Jobs', 'Create, edit, and view your scheduled jobs.');

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

        <div class="cmsMainContentWrapper styledText textColorThemeJobs">
            <div class="margin2em">
                <h1>Jobs</h1>
                <p>Create, edit, and view your scheduled jobs.</p>
                
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
