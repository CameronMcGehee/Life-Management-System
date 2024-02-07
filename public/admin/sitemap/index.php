<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'Sitemap',
		"pageDescription" => 'Listing of all pages in the LifeMS.']);

    echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

<link rel="stylesheet" type="text/css" href="../../css/app/sitemap.css">

</head>

<body>
    <div class="adminBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../../');
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <h1>Sitemap</h1>
            </div>

            <br>
            
            <hr>

            <div class="sitemapList" style="margin-left: 2em; margin-right: 2em;">

                    <br>

                    <?php

                        require_once '../../../lib/table/workspace.php';
                        $workspace = new workspace($_SESSION['lifems_workspaceId']);

                    ?>

                    <p class="mainPageTitle"><a href="../notes">Notes</a></p>
                    <p class="mainPageTitle"><a href="../excalidraw">Excalidraw</a></p>

                    <br>
                    <hr>
                
                    <h3>Current workspace entity: <span id="overviewButtonWorkspaceName" style="text-decoration: underline;"><i><?php echo htmlspecialchars($workspace->adminDisplayName); ?></i></span> <a href="../selectworkspace">(Change)</a></h3>
                        <p class="subPage"><a href="../workspaceoverview">Workspace Overview</a></p>
                        <p class="subPage"><a href="../contacts">Contacts</a></p>
                        <p class="subPage"><a href="../invoices">Invoices</a></p>
                        <p class="subPage"><a href="../estimates">Estimates</a></p>
                        <p class="subPage"><a href="../calendarEvents">CalendarEvents</a></p>
                        <p class="subPage"><a href="../inventory">Inventory</a></p>
                

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
