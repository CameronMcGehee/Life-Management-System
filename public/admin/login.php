<?php

    // Start Session
    require_once '../php/startSession.php';

    // This is the login page so if we are already signed in, just redirect to the business selection page

    if (isset($_SESSION['ultiscape_adminId']) && isset($_SESSION['ultiscape_businessId'])) {
        header("location: ./overview");
    } elseif (isset($_SESSION['ultiscape_adminId'])) {
        header("location: ./selectbusiness");
    }

    require_once '../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../', 'Login', 'Login to your UltiScape account.');

?>

    <link rel="stylesheet" type="text/css" href="../css/app/admin/adminLoginPage.css">
</head>

<body>
    <div class="appNoSidebarBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../', true, false, false);
        ?>

        <div class="cmsMainContentWrapper">
            
        <div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90">
                <div class="cmsLoginFormArea centered defaultMainShadows">
                    
                    <h1>Admin Login</h1>
                    
                    <form class="defaultForm" style="margin-left: 2em; margin-right: 2em;" method="POST" action="./scripts/standalone/login.script">
                        
                        <label for="usernameEmail"><p>Username/Email</p></label>
                        <input class="defaultInput" type="text" name="usernameEmail" id="usernameEmail" placeholder="Username/Email...">
                        
                        <br><br>
                        
                        <label for="password"><p>Password</p></label>
                        <input class="defaultInput" type="password" name="password" id="password" placeholder="Password...">

                        <?php
                            // Generate an auth token for the form
                            require_once '../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'adminLogin';
							$token->set();
                        ?>

                        <input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">
                        
                        <br><br>
                        
                        <button class="smallButtonWrapper greenButton xyCenteredFlex centered defaultMainShadows" type="submit">Go!</button>
                    
                    </form>

                </div>
            </div>
        
        </div>
        
        <?php
            echo $adminUIRender->renderAdminFooter('../', true, true);
        ?>

        <?php 
            // echo $adminUIRender->renderMobileNavBar('../');
        ?>

    </div>
</body>
<?php 
    echo $adminUIRender->renderAdminHtmlBottom('../');
?>
