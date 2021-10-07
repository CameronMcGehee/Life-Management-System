<?php

    // Start Session
    require_once '../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../lib/app/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./login', './businessselect');

    require_once '../../lib/app/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../', 'Edit Profile', 'Edit your UltiScape Profile.');

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

        <div class="cmsMainContentWrapper margin2em  styledText textColorThemeGray">
			<h1>Edit Profile</h1>
        </div>
        
        <?php 
            echo $renderer->renderAdminFooter();
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