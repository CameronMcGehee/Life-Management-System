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

	echo $renderer->renderAdminHtmlTop('../../', 'Create Business', 'Create a new business to manage in UltiScape.');
	echo $renderer->renderAdminTopBarDropdownScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
</head>

<body>
	<div class="cmsLoginBodyWrapper">

		<?php 
			echo $renderer->renderAdminTopBar('../../', true, true, true);
		?>

		<?php 
			// echo $renderer->renderAdminSideBar();
		?>

		<div class="cmsMainContentWrapper">
			
			<div class="maxHeight xCenteredFlex flexDirectionColumn margin90">
				<?php

					// Until we make this page look good, just echo out links to switch to the different businesses

					$currentAdmin->pullBusinesses();
					if (count($currentAdmin->businesses) == 0) {
						echo "<h1>Let's create your first business!</h1>";

						echo file_get_contents("./includes/createBusinessForm.inc.php");
					} elseif (false /*they are not on the paid plan*/) {
						echo "<p>A popup: You need to upgrade to the paid plan in order to manage more than 1 business with UltiScape.</p>";
					} else {
						echo "<h1>Create a new Business</h1>";

						echo file_get_contents("./includes/createBusinessForm.inc.html");
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
