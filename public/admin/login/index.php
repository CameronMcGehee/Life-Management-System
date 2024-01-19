<?php

    // Start Session
    require_once '../../php/startSession.php';

    // This is the login page so if we are already signed in, just redirect to the business selection page

    if (isset($_SESSION['ultiscape_adminId']) && isset($_SESSION['ultiscape_businessId'])) {
        header("location: ../overview");
    } elseif (isset($_SESSION['ultiscape_adminId'])) {
        header("location: ../selectbusiness");
    }

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', 'Login', 'Login to your LMS account.');

?>

    <link rel="stylesheet" type="text/css" href="../../css/app/adminLoginPage.css">

    <script src="../../js/etc/animation/shake.js"></script>

	<script src="../../js/etc/form/showFormError.js"></script>
	<script src="../../js/etc/form/clearFormErrors.js"></script>

	<script>

		var formData;
		var formOutput;
		var url = new URL(window.location.href);

		$(document).ready(function() {
			$("#loginForm").submit(function(event) {
				event.preventDefault();
				$('.loadingGif').fadeIn(100);
				formData = $("#loginForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/login.script.php", {
					formData: formData
				}, function () {
					formOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (formOutput) {
						case 'success':
							window.location.replace('../');
							break;
                        case 'noUsernameEmail':
                            showFormError("#noUsernameEmailError", '#usernameEmail');
							$("#usernameEmail").shake(50);
							break;
						case 'noMatch':
							showFormError("#noMatchError", '#password');
							$("#password").shake(50);
							break;
						default:
							showFormError("#"+formOutput+"Error", "#"+formOutput);
							$("#"+formOutput).shake(50);
							break;
					}

					$('.loadingGif').fadeOut(100);
				});
			});
		});
	</script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
    <div class="appNoSidebarBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../', true, false, false);
        ?>

        <div class="cmsMainContentWrapper">
            
        <div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90 styledText textColorThemeGray">
                <div class="cmsLoginFormArea defaultMainShadows">
                    
                    <h1 class="centered">Admin Login</h1>
                    
                    <form class="defaultForm" id="loginForm" style="margin-left: 2em; margin-right: 2em;" method="POST" action="./">
                        
                        <label for="usernameEmail"><p>Username/Email</p></label>
                        <input class="defaultInput" type="text" name="usernameEmail" id="usernameEmail" placeholder="Username/Email...">
                        <span id="usernameEmailError" class="underInputError" style="display: none;">Enter a username or email.</span>
                        <span id="noUsernameEmailError" class="underInputError" style="display: none;">There is no account with this username or email.</span>
                        
                        <br><br>
                        
                        <label for="password"><p>Password</p></label>
                        <input class="defaultInput" type="password" name="password" id="password" placeholder="Password...">
                        <span id="passwordError" class="underInputError" style="display: none;">Enter your password.</span>
                        <span id="noMatchError" class="underInputError" style="display: none;">Password is incorrect.</span>

                        <?php
                            // Generate an auth token for the form
                            require_once '../../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'adminLogin';
							$token->set();
                        ?>

                        <input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">
                        
                        <br><br>
                        
                        <button class="smallButtonWrapper greenButton xyCenteredFlex centered defaultMainShadows" type="submit">Go!</button>
                    
                    </form>

                    <br>

					<p class="centered">Don't have an account? <a href="../createaccount">Create one here!</a></p>

                </div>
            </div>
        
        </div>
        
        <?php
            echo $adminUIRender->renderAdminFooter('../../', true, true);
        ?>

        <?php 
            // echo $adminUIRender->renderMobileNavBar('../../');
        ?>

    </div>
</body>
<?php 
    echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
