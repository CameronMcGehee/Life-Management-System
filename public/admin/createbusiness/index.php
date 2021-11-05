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

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Create Business', 'Create a new business to manage in UltiScape.');
	echo $adminUIRender->renderAdminTopBarDropdownScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
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
				if (false /*they are not on the paid plan, will be implemented later*/) {
					echo "<p>A popup: You need to upgrade to the paid plan in order to manage more than 1 business with UltiScape.</p>
					";
				}
			}
		?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			
			<div class="twoColPage-Info-Content maxHeight">
				<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
					<?php
						if (count($currentAdmin->businesses) == 0) {
							echo '<h1>Let\'s create your first business!</h1>
							';
						} else {
							echo '<h1>Create a new Business</h1>
							';
						}
					?>

				</div>

				<div id="twoColContentWrapper" class="padding90">
					<?php
						echo file_get_contents("./includes/createBusinessForm.inc.html");
					?>
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

	<?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
	echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
