<?php

    // Start Session
    require_once '../../php/startSession.php';

    // This is the login page so if we are already signed in, just redirect to the home page
    if (isset($_SESSION['lifems_contactId']) && isset($_SESSION['lifems_workspaceId'])) {
        header("location: ../home");
        exit();
    }

    // If the workspaceId is not set, go to error page
    if (!isset($_GET['workspace'])) {
        header("location: ./noworkspace");
        exit();
    }

    // Make sure the workspace exists
	require_once '../../../lib/table/workspace.php';
    $currentWorkspace = new workspace($_GET['workspace']);
    if (!$currentWorkspace->existed) {
        header("location: ./noworkspace");
        exit();
    }

    require_once '../../../lib/contactUIRender.php';
    $contactUIRender = new contactUIRender();

    echo $contactUIRender->renderContactHtmlTop('../../', 'Login', 'Login to your LifeMS account.');

?>

    <link rel="stylesheet" type="text/css" href="../../css/app/contactLoginPage.css">

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
					formData: formData,
                    workspaceId: '<?php echo htmlspecialchars($_GET['workspace']); ?>'
				}, function () {
					formOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (formOutput) {
						case 'success':
                            <?php

                                // If the refUrl is set and contains the same host url in it, then go to that url. Otherwise, just go to the home page

                                $redirectUrl = '../home';

                                if (!empty($_GET['refUrl'])) {
                                    if (strpos($_GET['refUrl'], $_SERVER['HTTP_HOST']) < 15 && strpos($_GET['refUrl'], $_SERVER['HTTP_HOST']) != false) {
                                        $redirectUrl = $_GET['refUrl'];
                                    }
                                }

                            ?>
							window.location.replace('<?php echo $redirectUrl; ?>');
							break;
						default:
							showFormError("#"+formOutput+"Error", "#password");
							$("#password").shake(50);
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
            echo $contactUIRender->renderContactTopBar('../../', true, false, false);
        ?>

        <div class="cmsMainContentWrapper">
            
        <div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90 styledText textColorThemeGray">
                <div class="cmsLoginFormArea defaultMainShadows">
                    
                    <h1 class="centered">Contact Login</h1>
                    
                    <form class="defaultForm" id="loginForm" style="margin-left: 2em; margin-right: 2em;" method="POST" action="./">
                        
                        <label for="password"><p>Password</p></label>
                        <input class="defaultInput" type="password" name="password" id="password" placeholder="Password...">
                        <span id="passwordError" class="underInputError" style="display: none;">Enter your password.</span>
                        <span id="noContactError" class="underInputError" style="display: none;">We couldn't find a match.</span>

                        <?php
                            // Generate an auth token for the form
                            require_once '../../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'contactLogin';
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
            echo $contactUIRender->renderContactFooter('../../', true, true);
        ?>

        <?php 
            // echo $contactUIRender->renderMobileNavBar('../../');
        ?>

    </div>
</body>
<?php 
    echo $contactUIRender->renderContactHtmlBottom('../../');
?>
