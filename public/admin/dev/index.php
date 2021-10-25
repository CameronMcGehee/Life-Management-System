<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/manager/adminLoginManager.php';
    adminLoginManager::cmsVerifyAdminLoginRedirect('./login', './businessselect');

    require_once '../../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../../', 'DEV SPACE', 'DEVELOPMENT TESTING SPACE');

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

        <div class="cmsMainContentWrapper margin2em  styledText textColorThemeGray">
			<?php

                // require_once '../../../lib/class/admin.php';

                // $admin = new admin($_SESSION['ultiscape_adminId']);

                // $admin->getSavedLogins();
                // // $admin->getLoginAttempts();

                // echo '<p>admin: {{{{{      ';
                // var_dump($admin->savedLogins);
                // echo '</p>      }}}}}';

                // // Insert random savedLogin
                // require_once '../../../lib/class/uuid.php';
                // $uuid = new uuid('table', 'adminSavedLogin', 'adminSavedLoginId');
                
                // array_push($admin->savedLogins, $uuid->generatedId);

                // // Set
                // var_dump($admin->set());

                // echo '<p>admin: {{{{{      ';
                // var_dump($admin->savedLogins);
                // echo '</p>      }}}}}';

            ?>
        </div>
        
        <?php 
            echo $renderer->renderAdminFooter();
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
