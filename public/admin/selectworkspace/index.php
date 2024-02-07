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
	require_once '../../../lib/database.php';
	require_once '../../../lib/table/admin.php';
	require_once '../../../lib/table/workspace.php';

	$currentAdmin = new admin($_SESSION['lifems_adminId']);

	echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'Select Workspace',
		"pageDescription" => 'Select the workspace to use in LifeMS.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../', true, false);

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/adminLoginPage.css">
</head>

<body>
	<div class="appNoSidebarBodyWrapper">

		<?php 
			echo $adminUIRender->renderAdminTopBar('../../', true, false, true);
		?>

		<?php 
			// echo $adminUIRender->renderAdminSideBar();
		?>

		<div class="cmsMainContentWrapper">
			
			<div class="maxHeight yCenteredFlex flexDirectionColumn marginLeftRight90 styledText spacedText">

			<h1>Your Workspaces</h1>

				<?php

					// Until we make this page look good, just echo out links to switch to the different workspaces

					// Get owned workspaces
					$currentAdmin->pullOwnedWorkspaces();
					if (count($currentAdmin->ownedWorkspaces) > 0) {
						foreach ($currentAdmin->ownedWorkspaces as $workspaceId) {
							$workspace = new workspace($workspaceId);
							echo '<h3><a href="./scripts/standalone/setWorkspace.script.php?id='.$workspaceId.'">'.htmlspecialchars($workspace->adminDisplayName).'</a></h3>';
						}
					} else {
						echo "<p>You do not have any workspaces managed with LifeMS. <a href=\"../createworkspace\">Create one!</a></p>";
					}
				
				?>

					<br><br>
					<h1>Shared with You</h1>

				<?php
					
					// Get shared workspaces
					$currentAdmin->pullSharedWorkspaces();
					if (count($currentAdmin->sharedWorkspaces) > 0) {
						foreach ($currentAdmin->sharedWorkspaces as $workspaceId) {
							$workspace = new workspace($workspaceId);
							echo '<h3><a href="./scripts/standalone/setWorkspace.script.php?id='.$workspaceId.'">'.htmlspecialchars($workspace->adminDisplayName).'</a></h3>';
						}
					} else {
						echo "<p>Nobody has shared any workspaces with you to manage. <a href=\"../../staff\">Looking for staff login?</a></p>";
					}

				?>
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
