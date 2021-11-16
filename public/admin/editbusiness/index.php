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
	require_once '../../../lib/timezones/Timezones.php';

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Edit Business', 'Edit your UltiScape business.');
	echo $adminUIRender->renderAdminTopBarDropdownScripts('../../');

?>

	<link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css">
</head>

<body>
	<div class="cmsBodyWrapper">

		<?php 
			$currentBusiness = new business($_SESSION['ultiscape_businessId']);

			echo $adminUIRender->renderAdminTopBar('../../', true, true, true);
		?>

		<?php 
            echo $adminUIRender->renderAdminSideBar('../../');
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<form class="defaultForm maxHeight" action="./" method="POST">
				<div class="twoColPage-Info-Content maxHeight">
					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
					
						<h1>Edit <i><?php echo htmlspecialchars($currentBusiness->adminDisplayName); ?></i></h1>

						<!-- <br> -->

						<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit">Save Changes</button>

					</div>

					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">
						<div class="paddingTopBottom90">
							<h3>General Info</h3>
							<br>
							<div style="border: 1px solid green; padding: 1em; width: 93%;">

								<label for="displayName"><p>Business Name <span style="color: rgb(167, 0, 0);">*</span></p></label>
								<input class="bigInput" type="text" name="displayName" id="displayName" placeholder="Business name..." style="width: 70%;" required value="<?php echo htmlspecialchars($currentBusiness->displayName); ?>">
								<br><br>

								<label for="adminDisplayName"><p>Internal Display Name (What you see in Ultiscape)</p></label>
								<input class="defaultInput" type="text" name="adminDisplayName" id="adminDisplayName" placeholder="Internal display name..." value="<?php echo htmlspecialchars($currentBusiness->adminDisplayName); ?>">
								<br><br><br>

								<div style="border: 1px solid gray; padding: 1em; width: 90%; max-width: 25em; height: 5em;">
									<img src="<?php if ($currentBusiness->fullLogoFile === NULL) {echo "../../images/ultiscape/etc/noLogo.png";} else echo "../../images/ultiscape/uploads/businessFullLogoFile/".htmlspecialchars($currentBusiness->fullLogoFile); ?>" style="height: 100%; float: left;">
									
									<input class="defaultInput" type="checkbox" name="useNewLogo" id="useNewLogo"><label for="useNewLogo"> <p style="display: inline; clear: both;">Upload a new logo</p></label>
									<br><br>

									<label for="fullLogoFile" style="clear: both;"><p>Logo File</p></label>
									<input type="file" name="fullLogoFile" id="fullLogoFile" style="clear: both;">
								</div>

								<br><br>

								<div class="twoCol">

									<div>
										<label for="address1"><p>Address Line 1</p></label>
										<input class="defaultInput" type="text" name="address1" id="address1" placeholder="Address Line 1..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->address1); ?>">
										<br><br>

										<label for="address2"><p>Address Line 2</p></label>
										<input class="defaultInput" type="text" name="address2" id="address2" placeholder="" style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->address2); ?>">
										<br><br>
									</div>

									<div>
										<label for="city"><p>City</p></label>
										<input class="defaultInput" type="text" name="city" id="city" placeholder="City..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->city); ?>">
										<br><br>

										<div class="twoCol">
											<div>
												<label for="state"><p>State</p></label>
												<input class="defaultInput" type="text" name="state" id="state" placeholder="State..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->state); ?>">
											</div>

											<div>
												<label for="zipCode"><p>Zip Code</p></label>
												<input class="defaultInput" type="text" name="zipCode" id="zipCode" placeholder="Zip code..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->zipCode); ?>">
											</div>
										</div>
									</div>
									
								</div>

								<br>

								<label for="phone1"><p>Phone Number</p></label>
								<div class="fourColCompact">
									<div>
										<input class="defaultInput" type="text" name="phonePrefix" id="phonePrefix" placeholder="+1" value="<?php echo htmlspecialchars($currentBusiness->phonePrefix); ?>" style="width: 1.5em;">
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone1" id="phone1" placeholder="555" value="<?php echo htmlspecialchars($currentBusiness->phone1); ?>" style="width: 3em;">
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone2" id="phone2" placeholder="555" value="<?php echo htmlspecialchars($currentBusiness->phone2); ?>" style="width: 3em;">
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone3" id="phone3" placeholder="5555" value="<?php echo htmlspecialchars($currentBusiness->phone3); ?>" style="width: 3em;">
									</div>
								</div>

								<br><br>

								<label for="timeZone"><p>Time Zone</p></label>
								<?php

									$optionsList = new jessedp\Timezones\Timezones();
									echo $optionsList->create('timeZone', htmlspecialchars($currentBusiness->timeZone), array('attr' => array('id' => 'timeZone', 'class' => 'defaultInput'), 'with_regions' => false));

								?>

							</div>
							
							<br><br>

							<h3>Units</h3>
							<br>
							<div style="border: 1px solid green; padding: 1em; width: 93%;">
								<div class="threeCol">
									<div>
										<label for="currencySymbol"><p>Currency Symbol</p></label>
										<input class="defaultInput" type="text" name="currencySymbol" id="currencySymbol" placeholder="$" value="<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>" style="width: 1em;">
									</div>

									<div>
										<label for="areaSymbol"><p>Area Unit</p></label>
										<input class="defaultInput" type="text" name="areaSymbol" id="areaSymbol" placeholder="ft" value="<?php echo htmlspecialchars($currentBusiness->areaSymbol); ?>" style="width: 2em;">
									</div>

									<div>
										<label for="distanceSymbol"><p>Travel Distance Unit</p></label>
										<input class="defaultInput" type="text" name="distanceSymbol" id="distanceSymbol" placeholder="mi" value="<?php echo htmlspecialchars($currentBusiness->distanceSymbol); ?>" style="width: 2em;">
									</div>
								</div>
							</div>
							
							<br><br>

							<h3>Staff</h3>
							<br>
							<div style="border: 1px solid green; padding: 1em; width: 93%;">
								<label for="modStaffExtName"><p>Staff should be called:</p></label>
								<input class="defaultInput" type="text" name="modStaffExtName" id="modStaffExtName" placeholder="Employees" value="<?php if ($currentBusiness->modStaffExtName === NULL) {echo "Staff";} else {echo htmlspecialchars($currentBusiness->modStaffExtName);} ?>">
							</div>
							
							<br><br>

							<h3>Crews</h3>
							<br>
							<div style="border: 1px solid green; padding: 1em; width: 93%;">
							<label for="modCrewsExtName"><p>Crews should be called:</p></label>
								<input class="defaultInput" type="text" name="modCrewsExtName" id="modCrewsExtName" placeholder="Teams" value="<?php if ($currentBusiness->modCrewsExtName === NULL) {echo "Crews";} else {echo htmlspecialchars($currentBusiness->modCrewsExtName);} ?>">
							</div>
							
							<br><br>
							
							<?php
								// Generate an auth token for the form
								require_once '../../../lib/table/authToken.php';
								$token = new authToken();
								$token->authName = 'editBusiness';
								$token->set();
							?>

							<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">
						</div>
					</div>
				</div>
			</form>
			
		</div>
		
		<?php
			echo $adminUIRender->renderAdminFooter('../../');
		?>

		<?php 
			echo $adminUIRender->renderAdminMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../');
	?>
</body>
<?php 
	echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
