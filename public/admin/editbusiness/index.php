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

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Edit Business', 'Edit your UltiScape business.');
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
					<form class="defaultForm" action="./" method="POST">

						<label for="displayName"><p>Business Name <span style="color: rgb(167, 0, 0);">*</span></p></label>
						<input class="bigInput" type="text" name="displayName" id="displayName" placeholder="Business name..." style="width: 70%;" required>
						<br><br>

						<label for="adminDisplayName"><p>Internal Display Name (What you see in Ultiscape)</p></label>
						<input class="defaultInput" type="text" name="adminDisplayName" id="adminDisplayName" placeholder="Internal display name...">
						<br><br>

						<label for="fullLogoFile"><p>Logo File</p></label>
						<input type="file" name="fullLogoFile" id="fullLogoFile">
						<br><br>

						<div class="twoCol">

							<div>
								<label for="address"><p>Address</p></label>
								<input class="defaultInput" type="text" name="address" id="address" placeholder="Address..." style="width: 90%">
								<br><br>

								<label for="city"><p>City</p></label>
								<input class="defaultInput" type="text" name="city" id="city" placeholder="City..." style="width: 90%">
							</div>

							<div>
								<label for="state"><p>State</p></label>
								<input class="defaultInput" type="text" name="state" id="state" placeholder="State..." style="width: 90%">
								<br><br>

								<label for="zipCode"><p>Zip Code</p></label>
								<input class="defaultInput" type="text" name="zipCode" id="zipCode" placeholder="Zip code..." style="width: 90%">
							</div>
							
						</div>

						<br>

						<label for="phone1"><p>Phone Number</p></label>
						<div class="fourColCompact">
							<div>
								<input class="defaultInput" type="text" name="phonePrefix" id="phonePrefix" placeholder="+1" value="1" style="width: 3em;">
							</div>

							<div>
								<input class="defaultInput" type="text" name="phone1" id="phone1" placeholder="555" style="width: 6em;">
							</div>

							<div>
								<input class="defaultInput" type="text" name="phone2" id="phone2" placeholder="555" style="width: 6em;">
							</div>

							<div>
								<input class="defaultInput" type="text" name="phone3" id="phone3" placeholder="5555" style="width: 6em;">
							</div>
						</div>
						
						<?php
                            // Generate an auth token for the form
                            require_once '../../../lib/table/authToken.php';
							$token = new authToken();
							$token->authName = 'createBusiness';
							$token->set();
                        ?>

                        <input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">

						<br><br>

						<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit">Create!</button>
					</form>

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
