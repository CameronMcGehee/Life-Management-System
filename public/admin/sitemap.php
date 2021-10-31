<?php

    // Start Session
    require_once '../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./', './');

    require_once '../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../', 'Sitemap', 'Listing of all pages in the UltiScape CMS.');

    echo $renderer->renderAdminTopBarDropdownScripts('../');

?>

<link rel="stylesheet" type="text/css" href="../css/app/admin/sitemap.css">

</head>

<body>
    <div class="cmsBodyWrapper">

        <?php 
            echo $renderer->renderAdminTopBar('../');
        ?>

        <?php 
            echo $renderer->renderAdminSideBar('../');
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <h1>Sitemap</h1>
                <p>Can't find something? Here's a list of all UltiScape pages.</p>
            </div>

            <br><hr>

            <div class="marginLeftRight90 sitemapList">

                    <br>

                    <?php

                        require_once '../../lib/class/business.php';
                        $business = new business();

                        // var_dump($businessManager->getLocation($_SESSION['ultiscape_businessId']));

                    ?>
                
                    <p class="mainPageTitle"><a href="./overview">Overview of <span id="overviewButtonBusinessName" style="text-decoration: underline;"><i><?php echo htmlspecialchars($business->adminDisplayName); ?></i></span></p>

                    <br>
                    
                    <p class="mainPageTitle"><a href="./people">People</a></p>
                        <p class="subPage"><a href="./people/customers">Customers</a></p>
                        <p class="subPage"><a href="./people/staff">Staff</a></p>
                        <p class="subPage"><a href="./people/co-admins">Co-Admins</a></p>
                    <p class="mainPageTitle"><a href="./communications">Communications</a></p>
                        <p class="subPage"><a href="./communications/email-campaigns">Email Campaigns</a></p>
                        <p class="subPage"><a href="./communications/send-email">Send General Email</a></p>
                    <p class="mainPageTitle"><a href="./jobs">Jobs</a></p>
                    <p class="mainPageTitle"><a href="./documents">Documents</a></p>
                    <p class="mainPageTitle"><a href="./inventory">Inventory</a></p>
                

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
