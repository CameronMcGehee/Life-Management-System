<?php

	// Start Session
	require_once '../../php/startSession.php';

	// This is the business select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['ultiscape_adminId'])) {
		header("location: ../login");
	}

	require_once '../../../lib/renderer.php';
	$renderer = new renderer();

	// Other required libraries
	require_once '../../../lib/class/admin.php';
	require_once '../../../lib/class/business.php';

	echo $renderer->renderAdminHtmlTop('../../', 'Create Business', 'Create a new business to manage in UltiScape.');
	echo $renderer->renderAdminTopBarDropdownScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
</head>

<body>
	<div class="cmsLoginBodyWrapper">

		<?php 
			$currentAdmin = new admin($_SESSION['ultiscape_adminId']);
			$currentAdmin->pullBusinesses();

			// Render the business selector in the menu bar only if they actually have a business on their account already. The user may be on this page creating their first business.
			if (count($currentAdmin->businesses) == 0) {
				echo $renderer->renderAdminTopBar('../../', true, false, true);
			} else {
				echo $renderer->renderAdminTopBar('../../', true, true, true);
				if (false /*they are not on the paid plan, will be implemented later*/) {
					echo "<p>A popup: You need to upgrade to the paid plan in order to manage more than 1 business with UltiScape.</p>
					";
				}
			}
		?>

		<div class="cmsMainContentWrapper">
			
			<div class="twoColPage-Info-Content maxHeight">
				<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom2em">
					<?php
						if (count($currentAdmin->businesses) == 0) {
							echo "<h1>Let's create your first business!</h1>
							";
						} else {
							echo "<h1>Create a new Business</h1>
							";
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
			echo $renderer->renderAdminFooter('../../', true, true);
		?>

		<?php 
			// echo $renderer->renderMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $renderer->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
	echo $renderer->renderAdminHtmlBottom('../../');
?>
