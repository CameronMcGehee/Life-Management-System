<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'Inventory', 'Manage your business\'s inventory.');

    echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

</head>

<body>
    <div class="adminBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../../');
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeInventory">
            <div style="margin-left: 2em; margin-right: 2em;">
                <h1>Inventory</h1>
                <p>Manage your business's inventory.</p>
                
                <h2>Equipment</h2>
                <h2>Chemicals</h2>
                
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
