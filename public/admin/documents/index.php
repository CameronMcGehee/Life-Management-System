<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/app/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('../login', '../businessselect');

    require_once '../../../lib/app/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'Documents', 'Create, edit, and view invoices, esimates, and document uploads.');

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

        <div class="cmsMainContentWrapper styledText textColorThemeDocuments">
            <div class="margin2em">
                <h1>Documents</h1>
                <p>Create, edit, and view invoices, esimates, and document uploads.</p>
                
                <h2>Recent Invoices</h2>
                <h2>Recent Esimates</h2>
                <h2>Recent Document Uploads</h2>
                
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