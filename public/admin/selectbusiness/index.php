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
	require_once '../../../lib/database.php';
	require_once '../../../lib/table/admin.php';
	require_once '../../../lib/table/business.php';

	$currentAdmin = new admin($_SESSION['ultiscape_adminId']);

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Business Selection', 'Select the business to use in UltiScape.');
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

			<h1>Your Businesses</h1>

				<?php

					// Until we make this page look good, just echo out links to switch to the different businesses

					// Get owned businesses
					$currentAdmin->pullOwnedBusinesses();
					if (count($currentAdmin->ownedBusinesses) > 0) {
						foreach ($currentAdmin->ownedBusinesses as $businessId) {
							$business = new business($businessId);
							echo '<h3><a href="./scripts/standalone/setBusiness.script.php?id='.$businessId.'">'.htmlspecialchars($business->adminDisplayName).'</a></h3>';
						}
					} else {
						echo "<p>You do not have any businesses managed with UltiScape. <a href=\"../createbusiness\">Create one!</a></p>";
					}
				
				?>

					<br><br>
					<h1>Shared with You</h1>

				<?php
					
					// Get shared businesses
					$currentAdmin->pullSharedBusinesses();
					if (count($currentAdmin->sharedBusinesses) > 0) {
						foreach ($currentAdmin->sharedBusinesses as $businessId) {
							$business = new business($businessId);
							echo '<h3><a href="./scripts/standalone/setBusiness.script.php?id='.$businessId.'">'.htmlspecialchars($business->adminDisplayName).'</a></h3>';
						}
					} else {
						echo "<p>Nobody has shared any businesses with you to manage. <a href=\"../../staff\">Looking for staff login?</a></p>";
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
