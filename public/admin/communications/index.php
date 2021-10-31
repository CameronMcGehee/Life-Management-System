<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../', '../');

    require_once '../../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'Communications', 'Manage Email messages and campaigns between you, your customers and your staff. SMS support coming soon!');

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

        <div class="cmsMainContentWrapper styledText textColorThemeCommunications">
            <div style="margin-left: 2em; margin-right: 2em;">
                <h1>Communcations</h1>
                <p>Manage Email messages and campaigns between you, your customers and your staff.</p>

                <h2>Campaigns</h2>
                <h2>Recent Email Sends</h2>
                
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
