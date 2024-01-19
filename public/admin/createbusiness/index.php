<?php

	// Start Session
	require_once '../../php/startSession.php';

	// This is the business select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['ultiscape_adminId'])) {
		header("location: ../login");
	}

	require_once '../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../lib/table/admin.php';
	require_once '../../../lib/table/business.php';

	echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'Create Business',
		"pageDescription" => 'Create a new business to manage in LifeMS.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/adminLoginPage.css">
</head>

<body>
	<div class="appNoSidebarBodyWrapper">

		<?php 
			$currentAdmin = new admin($_SESSION['ultiscape_adminId']);
			$currentAdmin->pullBusinesses();

			// Render the business selector in the menu bar only if they actually have a business on their account already. The user may be on this page creating their first business.
			if (count($currentAdmin->businesses) == 0) {
				echo $adminUIRender->renderAdminTopBar('../../', true, false, true);
			} else {
				echo $adminUIRender->renderAdminTopBar('../../', true, true, true);
			}
		?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			
			<div class="xyCenteredFlex centered flexDirectionColumn maxHeight maxWidth">
					
				<?php
					if (count($currentAdmin->businesses) == 0) {
						echo '<h1>Let\'s create your first business!</h1>
						';
					} else {
						echo '<h1>Create a new Business</h1>
						';
					}
				?>

					<br>
					<br>

				<form class="defaultForm maxWidth" action="./scripts/standalone/createbusiness.script.php" method="POST">

					<input class="bigInput" type="text" name="businessName" id="businessName" placeholder="Business name..." style="width: 70%;" required>

					<br>
					<br>
					<br>

					<input type="checkbox" name="takeToEditPage" id="takeToEditPage"><label for="takeToEditPage"> <p style="display: inline;">Input more details after creation</p></label>
					
					<br>
					<?php
						// Generate an auth token for the form
						require_once '../../../lib/table/authToken.php';
						$token = new authToken();
						$token->authName = 'createBusiness';
						$token->set();
					?>

					<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">

					<br>

					<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit">Create!</button>
				</form>

			</div>
			
		</div>
		
		<?php
			echo $adminUIRender->renderAdminFooter('../../', true, true);
		?>

		<?php 
			// echo $adminUIRender->renderMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
	echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
