<?php

    // Start Session
    require_once '../php/startSession.php';

    // This is the login page so if we are already signed in, just redirect to the business selection page

    if (isset($_SESSION['ultiscape_adminId']) && isset($_SESSION['ultiscape_businessId'])) {
        header("location: ./overview");
    } elseif (isset($_SESSION['ultiscape_adminId'])) {
        header("location: ./businessselect");
    }

    require_once '../../lib/renderer.php';
    $renderer = new renderer();

    echo $renderer->renderAdminHtmlTop('../', 'Login', 'Login to your UltiScape account.');

?>

    <link rel="stylesheet" type="text/css" href="../css/app/admin/adminLoginPage.css">
</head>

<body>
    <div class="cmsLoginBodyWrapper">

        <?php 
            echo $renderer->renderAdminTopBar('../', true, false, false);
        ?>

        <?php 
            // echo $renderer->renderAdminSideBar();
        ?>

        <div class="cmsMainContentWrapper">
            
        <div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90">
                <div class="cmsLoginFormArea centered defaultMainShadows">
                    
                    <h1>Admin Login</h1>
                    
                    <form style="margin-left: 2em; margin-right: 2em;" method="POST" action="./scripts/standalone/login.script">
                        
                        <label for="usernameEmail"><p>Username/Email</p></label>
                        <input class="defaultMainShadows" type="text" name="usernameEmail" id="usernameEmail" placeholder="Username/Email...">
                        
                        <br><br>
                        
                        <label for="password"><p>Password</p></label>
                        <input class="defaultMainShadows" type="password" name="password" id="password" placeholder="Password...">

                        <input type="hidden" name="loginFormAuthToken" id="loginFormAuthToken" value="<?php echo 'ThisWillBeATokenAtSomePoint'; ?>">
                        
                        <br><br>
                        
                        <button class="smallButtonWrapper greenButton xyCenteredFlex centered defaultMainShadows" type="submit">Go!</button>
                    
                    </form>

                </div>
            </div>
        
        </div>
        
        <?php
            echo $renderer->renderAdminFooter('../', true, true);
        ?>

        <?php 
            // echo $renderer->renderMobileNavBar('../');
        ?>

    </div>
</body>
<?php 
    echo $renderer->renderAdminHtmlBottom('../');
?>
