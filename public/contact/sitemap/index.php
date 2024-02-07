<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'Sitemap', 'Listing of all pages in the LifeMS CMS.');

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
                <p>Can't find something? Here's a list of all LifeMS pages.</p>
            </div>

            <br>
            
            <hr>

            <div class="sitemapList" style="margin-left: 2em; margin-right: 2em;">

                    <br>

                    <?php

                        require_once '../../../lib/table/workspace.php';
                        $workspace = new workspace($_SESSION['lifems_workspaceId']);

                    ?>
                
                    <p class="mainPageTitle"><a href="../workspaceoverview">Overview of <span id="overviewButtonWorkspaceName" style="text-decoration: underline;"><i><?php echo htmlspecialchars($workspace->adminDisplayName); ?></i></span></p>
                    
                    <p class="mainPageTitle"><a href="../people">People</a></p>
                        <p class="subPage"><a href="../people/contacts">Contacts</a></p>
                        <p class="subPage"><a href="../people/staff">Staff</a></p>
                        <p class="subPage"><a href="../people/co-admins">Co-Admins</a></p>
                    <p class="mainPageTitle"><a href="../communications">Communications</a></p>
                        <p class="subPage"><a href="../communications/email-campaigns">Email Campaigns</a></p>
                        <p class="subPage"><a href="../communications/send-email">Send General Email</a></p>
                    <p class="mainPageTitle"><a href="../calendarEvents">CalendarEvents</a></p>
                    <p class="mainPageTitle"><a href="../documents">Documents</a></p>
                    <p class="mainPageTitle"><a href="../inventory">Inventory</a></p>
                

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
