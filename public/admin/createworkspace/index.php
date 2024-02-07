<?php

	// Start Session
	require_once '../../php/startSession.php';

	// This is the workspace select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['lifems_adminId'])) {
		header("location: ../login");
	}

	require_once '../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../lib/table/admin.php';
	require_once '../../../lib/table/workspace.php';

	echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'Create Workspace',
		"pageDescription" => 'Create a new workspace to manage in LifeMS.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/adminLoginPage.css">
</head>

<body>
	<div class="appNoSidebarBodyWrapper">

		<?php 
			$currentAdmin = new admin($_SESSION['lifems_adminId']);
			$currentAdmin->pullWorkspaces();

			// Render the workspace selector in the menu bar only if they actually have a workspace on their account already. The user may be on this page creating their first workspace.
			if (count($currentAdmin->workspaces) == 0) {
				echo $adminUIRender->renderAdminTopBar('../../', true, false, true);
			} else {
				echo $adminUIRender->renderAdminTopBar('../../', true, true, true);
			}
		?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			
			<div class="xyCenteredFlex centered flexDirectionColumn maxHeight maxWidth">
					
				<?php
					if (count($currentAdmin->workspaces) == 0) {
						echo '<h1>Let\'s create your first workspace!</h1>
						';
					} else {
						echo '<h1>Create a new Workspace</h1>
						';
					}
				?>

					<br>
					<br>

				<form class="defaultForm maxWidth" action="./scripts/standalone/createworkspace.script.php" method="POST">

					<input class="bigInput" type="text" name="workspaceName" id="workspaceName" placeholder="Workspace name..." style="width: 70%;" required>

					<br>
					<br>
					<br>

					<input type="checkbox" name="takeToEditPage" id="takeToEditPage"><label for="takeToEditPage"> <p style="display: inline;">Input more details after creation</p></label>
					
					<br>
					<?php
						// Generate an auth token for the form
						require_once '../../../lib/table/authToken.php';
						$token = new authToken();
						$token->authName = 'createWorkspace';
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
