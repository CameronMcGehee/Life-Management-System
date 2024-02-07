<?php

	// Start Session
	require_once '../../php/startSession.php';

	// This is the login page so if we are already signed in, just redirect to the workspace selection page

	if (isset($_SESSION['lifems_adminId'])) {
		header("location: ../");
	}

	require_once '../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'Login',
		"pageDescription" => 'Login to your LifeMS account.']);

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/adminRegisterPage.css">

	<style>
		#loadingGif {
			margin-left: none;
			margin-right: auto;
		}
		
		/* Only for desktop, make the loading gif go to the right */
		@media only screen and (min-width: 1000px) {
			#loadingGif {
				margin-left: auto;
				margin-right: 0px;
			}
		}
	</style>

	<script src="../../js/etc/animation/shake.js"></script>

	<script src="../../js/etc/form/showFormError.js"></script>
	<script src="../../js/etc/form/clearFormErrors.js"></script>

	<script>

		var formData;
		var formOutput;
		var url = new URL(window.location.href);

		$(document).ready(function() {
			$("#registerForm").submit(function(event) {
				event.preventDefault();
				$('.loadingGif').fadeIn(100);
				formData = $("#registerForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/register.script.php", {
					formData: formData
				}, function () {
					formOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (formOutput) {
						case 'success':
							window.location.replace('../');
							break;
						case 'emailValidity':
							showFormError("#emailError", "#email");
							$("#email").shake(50);
							break;
						case 'emailExists':
							showFormError("#emailExistsError", "#email");
							$("#email").shake(50);
							break;
						case 'usernameExists':
							showFormError("#usernameExistsError", "#username");
							$("#username").shake(50);
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

		<?php
            require_once '../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', 'class' => 'styledText spacedText defaultMainShadows', 'popups' => ['devWelcomeInfo']]);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

		<div class="cmsMainContentWrapper">
			
		<div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90 styledText textColorThemeGray">
				<div class="cmsRegisterFormArea defaultMainShadows" style="margin-left: 2em; margin-right: 2em;">
					
					<h1 class="centered">Create an Account</h1>

					<br><br><br>
					
					<form class="defaultForm" method="POST" action="./" id="registerForm">

						<div class="twoCol">
							<div style="padding-right: .5em;">
								<label for="firstName"><p>First Name</p></label>
								<input class="defaultInput" type="text" name="firstName" id="firstName" placeholder="First Name..." style="width: 100%;">
								<span id="firstNameError" class="underInputError" style="display: none;">Your first name is too short.</span>
							</div>

							<div style="padding-left: .5em;">
								<label for="lastName"><p>Last Name</p></label>
								<input class="defaultInput" type="text" name="lastName" id="lastName" placeholder="Last Name..." style="width: 100%;">
								<span id="lastNameError" class="underInputError" style="display: none;">Your last name is too short.</span>
							</div>
						</div>

						<br>
						
						<label for="email"><p>Email</p></label>
						<input class="defaultInput" type="text" name="email" id="email" placeholder="Email..." style="width: 100%;">
						<span id="emailError" class="underInputError" style="display: none;">Input a valid email address.</span>
						<span id="emailExistsError" class="underInputError" style="display: none;">This email is already in use.</span>

						<br><br>
					
						<label for="username"><p>Username</p></label>
						<input class="defaultInput" type="text" name="username" id="username" placeholder="Username..." style="width: 100%;">
						<span id="usernameError" class="underInputError" style="display: none;">Your username is too short.</span>
						<span id="usernameExistsError" class="underInputError" style="display: none;">This username is already in use.</span>
						
						<br><br>
						
						<label for="password"><p>Password</p></label>
						<input class="defaultInput" type="password" name="password" id="password" placeholder="Password..." style="width: 100%;">
						<span id="passwordError" class="underInputError" style="display: none;">Your password is too short.</span>

						<?php
							// Generate an auth token for the form
							require_once '../../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'adminRegister';
							$token->set();
						?>

						<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">
						
						<br><br>
						
						<button class="smallButtonWrapper greenButton xyCenteredFlex centered defaultMainShadows" type="submit">Start using LifeMS!</button>
					
					</form>

					<br>

					<p class="centered">Already have an account? <a href="../login">Login here!</a></p>

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
