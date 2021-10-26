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

        <div class="cmsMainContentWrapper  styledText textColorThemeGray">
            <div class="margin2em">
                <?php

                    // require_once '../../../lib/class/admin.php';

                    // $admin = new admin($_SESSION['ultiscape_adminId']);

                    // $admin->getLoginAttempts();

                    // echo '<p>Login Attempts: ';
                    // var_dump($admin->loginAttempts);
                    // echo '</p>';

                    // Insert random savedLogin
                    // require_once '../../../lib/class/uuid.php';
                    // $uuid = new uuid('table', 'adminLoginAttempt', 'adminLoginAttemptId');
                    
                    // array_push($admin->loginAttempts, array('adminLoginAttemptId' => $uuid->generatedId, 'clientIp' => 'localhost', 'enteredUsername' => 'something', 'result' => 's', 'dateTimeAdded' => date('Y-m-d H:i:s')));
                    // $uuid->regenerate();
                    // array_push($admin->loginAttempts, array('adminLoginAttemptId' => $uuid->generatedId, 'clientIp' => 'localhost', 'enteredUsername' => 'something', 'result' => 's', 'dateTimeAdded' => date('Y-m-d H:i:s')));
                    // $uuid->regenerate();
                    // array_push($admin->loginAttempts, array('adminLoginAttemptId' => $uuid->generatedId, 'clientIp' => 'localhost', 'enteredUsername' => 'something', 'result' => 's', 'dateTimeAdded' => date('Y-m-d H:i:s')));
                    // $uuid->regenerate();
                    // array_push($admin->loginAttempts, array('adminLoginAttemptId' => $uuid->generatedId, 'clientIp' => 'localhost', 'enteredUsername' => 'something', 'result' => 's', 'dateTimeAdded' => date('Y-m-d H:i:s')));
                    // $uuid->regenerate();
                    // array_push($admin->loginAttempts, array('adminLoginAttemptId' => $uuid->generatedId, 'clientIp' => 'localhost', 'enteredUsername' => 'something', 'result' => 's', 'dateTimeAdded' => date('Y-m-d H:i:s')));

                    // // Set
                    // $admin->set();

                    // echo '<p>Login Attempts after insertion: ';
                    // var_dump($admin->loginAttempts);
                    // echo '</p>';

                ?>
            </div>
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
