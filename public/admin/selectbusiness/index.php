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

	$currentAdmin = new admin($_SESSION['ultiscape_adminId']);

	echo $renderer->renderAdminHtmlTop('../../', 'Business Selection', 'Select the business to use in UltiScape.');
	echo $renderer->renderAdminTopBarDropdownScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
</head>

<body>
	<div class="cmsLoginBodyWrapper">

		<?php 
			echo $renderer->renderAdminTopBar('../../', true, false, true);
		?>

		<?php 
			// echo $renderer->renderAdminSideBar();
		?>

		<div class="cmsMainContentWrapper">
			
			<div class="maxHeight xyCenteredFlex flexDirectionColumn margin90">
				<?php

					// Until we make this page look good, just echo out links to switch to the different businesses

					$currentAdmin->pullBusinesses();
					foreach ($currentAdmin->businesses as $businessId) {
						$businessInfo = new business($businessId);
						echo '<p><a href="./scripts/standalone/setBusiness.script.php?id='.$businessId.'">'.htmlspecialchars($businessInfo->adminDisplayName).'</a></p>';
					}

				?>
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
