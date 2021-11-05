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

	$currentAdmin = new admin($_SESSION['ultiscape_adminId']);

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Business Selection', 'Select the business to use in UltiScape.');
	echo $adminUIRender->renderAdminTopBarDropdownScripts('../../', true, false);

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
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
			
			<div class="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90">
				<?php

					// Until we make this page look good, just echo out links to switch to the different businesses

					$currentAdmin->pullBusinesses();
					foreach ($currentAdmin->businesses as $businessId) {
						$businessInfo = new business($businessId);
						echo '<h2><a href="./scripts/standalone/setBusiness.script.php?id='.$businessId.'">'.htmlspecialchars($businessInfo->adminDisplayName).'</a></h2>';
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
