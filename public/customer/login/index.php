<?php

    // Start Session
    require_once '../../php/startSession.php';

    // This is the login page so if we are already signed in, just redirect to the home page
    if (isset($_SESSION['ultiscape_customerId']) && isset($_SESSION['ultiscape_businessId'])) {
        header("location: ../home");
        exit();
    }

    // If the businessId is not set, go to error page
    if (!isset($_GET['business'])) {
        header("location: ./nobusiness");
        exit();
    }

    // Make sure the business exists
	require_once '../../../lib/table/business.php';
    $currentBusiness = new business($_GET['business']);
    if (!$currentBusiness->existed) {
        header("location: ./nobusiness");
        exit();
    }

    require_once '../../../lib/customerUIRender.php';
    $customerUIRender = new customerUIRender();

    echo $customerUIRender->renderCustomerHtmlTop('../../', 'Login', 'Login to your UltiScape account.');

?>

    <link rel="stylesheet" type="text/css" href="../../css/app/customerLoginPage.css">

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
                    businessId: '<?php echo htmlspecialchars($_GET['business']); ?>'
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
            echo $customerUIRender->renderCustomerTopBar('../../', true, false, false);
        ?>

        <div class="cmsMainContentWrapper">
            
        <div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90 styledText textColorThemeGray">
                <div class="cmsLoginFormArea defaultMainShadows">
                    
                    <h1 class="centered">Customer Login</h1>
                    
                    <form class="defaultForm" id="loginForm" style="margin-left: 2em; margin-right: 2em;" method="POST" action="./">
                        
                        <label for="password"><p>Password</p></label>
                        <input class="defaultInput" type="password" name="password" id="password" placeholder="Password...">
                        <span id="passwordError" class="underInputError" style="display: none;">Enter your password.</span>
                        <span id="noCustomerError" class="underInputError" style="display: none;">We couldn't find a match.</span>

                        <?php
                            // Generate an auth token for the form
                            require_once '../../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'customerLogin';
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
            echo $customerUIRender->renderCustomerFooter('../../', true, true);
        ?>

        <?php 
            // echo $customerUIRender->renderMobileNavBar('../../');
        ?>

    </div>
</body>
<?php 
    echo $customerUIRender->renderCustomerHtmlBottom('../../');
?>
