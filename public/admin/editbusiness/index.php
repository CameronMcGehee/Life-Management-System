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
	require_once '../../../lib/table/business.php'; $currentBusiness = new business($_SESSION['ultiscape_businessId']);
	require_once '../../../lib/table/paymentMethod.php';
	require_once '../../../lib/timezones/Timezones.php';

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Edit '.htmlspecialchars($currentBusiness->adminDisplayName), 'Edit your UltiScape business.');
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

		// Generate the auth tokens for the form
		require_once '../../../lib/table/authToken.php';

		$mainAuthToken = new authToken();
		$mainAuthToken->authName = 'editBusiness';
		$mainAuthToken->set();

		$deletePaymentMethodAuthToken = new authToken();
		$deletePaymentMethodAuthToken->authName = 'deletePaymentMethod';
		$deletePaymentMethodAuthToken->set();

		$addPaymentMethodAuthToken = new authToken();
		$addPaymentMethodAuthToken->authName = 'addPaymentMethod';
		$addPaymentMethodAuthToken->set();

?>

	<style>
		#loadingGif {
			margin-left: none;
			margin-right: auto;
		}
		
		/* Only for desktop, make the loading gif go to the right */
		@media only screen and (min-width: 1000px) {
			#loadingGif {
				margin-left: auto;
				margin-right: 0px;
			}
		}
	</style>

	<!-- <link rel="stylesheet" type="text/css" href="../../css/app/admin/adminLoginPage.css"> -->

	<script src="../../js/etc/animation/shake.js"></script>

	<script src="../../js/etc/form/showFormError.js"></script>
	<script src="../../js/etc/form/clearFormErrors.js"></script>

	<script>

		var formData;
		var formOutput;
		var url = new URL(window.location.href);

		function setUnsaved() {
			$(".changesMessage").each(function () {
				$(this).html('<span style="color: red;">You have unsaved changes.</span>');
				$(this).shake(50);
			});
		}

		function setSaved() {
			$(".changesMessage").each(function () {
				$(this).html('<span style="color: green;">Up to date ✔</span>');
			});
		}

		$(function() {

			function registerPaymentMethodDeleteButtonClicks() {
				$("span[id*='deletePaymentMethod:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deletePaymentMethod.script.php", {
							paymentMethodId: currentId,
							deletePaymentMethodAuthToken: '<?php echo $deletePaymentMethodAuthToken->authTokenId; ?>'
						}, function () {
							// find the closest <tr> to the delete button and remove it.
							$(el).closest('tr').remove();
						});
					});
				});
			}

			registerPaymentMethodDeleteButtonClicks();

			$("#addPaymentMethod").click(function(event) {

				$('.addPaymentMethodLoadingGif').fadeIn(100);

				// set a new paymentMethod with script, and then add it to the list with it's Id

				setTimeout(() => {
					$("#scriptLoader").load("./scripts/async/addPaymentMethod.script.php", {
						businessId: '<?php echo $currentBusiness->businessId; ?>',
						addPaymentMethodAuthToken: '<?php echo $addPaymentMethodAuthToken->authTokenId; ?>'
					}, function () {
						scriptOutput = $("#scriptLoader").html().split(":::");
						paymentMethodId = scriptOutput[0];
						formState = scriptOutput[1];

						switch (formState) {
							case 'success':

								// Append paymentMethod to list
								$("#paymentMethods").append('<tr><td><input type="hidden" name="paymentMethodId[]" value="' + paymentMethodId + '"><input class="invisibleInput" style="height: 1.3em; width: 5em; max-width: 30vw; font-size: 1.3em;" type="text" name="paymentMethodName[]" value="" placeholder="Name..."> <br><br><span id="deletePaymentMethod:::' + paymentMethodId + '" class="smallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../images/ultiscape/icons/trash.svg"></span></td><td class="tg-0lax"><div class="twoCol" style="grid-template-columns: 30% 70%"><div><p>Amount Cut</p><input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="0.01" name="paymentMethodAmountCut[]" value="0" min="0" style="width: 5em;" value="25"><p>Percent Cut</p><input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step=".001" name="paymentMethodPercentCut[]" value="0" min="0" max="100" style="width: 5em;" value="1"><label for="paymentMethodTax">%</label></div><div><p>Notes to Customer (included on payment screen)</p><textarea class="invisibleInput" style="height: 1.3em; width: 15em; max-width: 30vw; font-size: 1.3em; resize: none; height: 4em;" type="text" name="paymentMethodNotes[]"></textarea></div></div></td></tr>');

								// Make sure the new inputs update the changes tracker
								$("#editBusinessForm :input").change(function () {
									setUnsaved();
								});
								registerPaymentMethodDeleteButtonClicks()

								break;
							default:
								break;
						}

						$('.addPaymentMethodLoadingGif').fadeOut(100);
					});
				}, 300);
				
				
			});

			// if ($.isNumeric(url.searchParams.get('fsl'))) {
			// 	$("#twoColContentWrapper").scrollTop(url.searchParams.get('fsl'));
			// }
			// if ($.isNumeric(url.searchParams.get('wsl'))) {
			// 	$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			// }

			$("#editBusinessForm").submit(function(event) {
				event.preventDefault();
				$('.loadingGif').fadeIn(100);
				formData = $("#editBusinessForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editbusiness.script.php", {
					formData: formData
				}, function () {
					formOutput = $("#scriptLoader").html();
					clearFormErrors();

					if (formOutput == 'success') {

						// Get the current scroll position of the form to scroll back to it after the reload
						// url.searchParams.set('fsl', $("#twoColContentWrapper").scrollTop());
						// url.searchParams.set('wsl', $(".cmsMainContentWrapper").scrollTop());
						// window.location.replace(url.href);

						setSaved();
					} else {
						showFormError("#"+formOutput+"Error", "#"+formOutput);
						$("#"+formOutput).shake(50);
					}

					$('.loadingGif').fadeOut(100);
				});
			});

			$("#editBusinessForm input, #editBusinessForm select").change(function () {
				setUnsaved();
			});

		});
	</script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
	<div class="cmsBodyWrapper">

		<?php 
			echo $adminUIRender->renderAdminTopBar('../../', true, true, true);
		?>

		<?php 
            echo $adminUIRender->renderAdminSideBar('../../');
        ?>

		<?php
            require_once '../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', 'class' => 'styledText spacedText defaultMainShadows']);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white;">
				<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit" onclick="$('#editBusinessForm').submit()">Save Changes</button>
				<br>

				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
			</div>
			<form class="defaultForm maxHeight" action="./" method="POST" id="editBusinessForm">

				<div class="twoColPage-Info-Content maxHeight">
					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
					
						<h1>Edit <i><?php echo htmlspecialchars($currentBusiness->adminDisplayName); ?></i></h1>

						<!-- <br> -->

						<span class="desktopOnlyBlock">
							<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit">Save Changes</button>
							<br><br>
							<img style="display: none; width: 3em;" src="../../images/ultiscape/etc/loading.gif" class="loadingGif">
							
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
						</span>

					</div>

					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">
						<div class="paddingTopBottom90">
							<h3>General Info</h3>
							<div class="defaultInputGroup">

								<label for="displayName"><p>Business Name <span style="color: rgb(167, 0, 0);">*</span></p></label>
								<input class="bigInput" type="text" name="displayName" id="displayName" placeholder="Business name..." style="width: 70%;" required value="<?php echo htmlspecialchars($currentBusiness->displayName); ?>">
								<span id="displayNameError" class="underInputError" style="display: none;"><br>Business name bust be between <?php echo $ULTISCAPECONFIG['businessNameMinLength']; ?> and <?php echo $ULTISCAPECONFIG['businessNameMaxLength']; ?> characters.</span>
								<br><br>

								<label for="adminDisplayName"><p>Internal Display Name (What you see in Ultiscape)</p></label>
								<input class="defaultInput" type="text" name="adminDisplayName" id="adminDisplayName" placeholder="Internal display name..." value="<?php echo htmlspecialchars($currentBusiness->adminDisplayName); ?>">
								<span id="adminDisplayNameError" class="underInputError" style="display: none;"><br>Business name bust be between <?php echo $ULTISCAPECONFIG['businessNameMinLength']; ?> and <?php echo $ULTISCAPECONFIG['businessNameMaxLength']; ?> characters.</span>
								<!-- <br><br><br> -->

								<!-- <div style="border: 1px solid gray; padding: 1em; width: 90%; max-width: 25em; height: 5em;">
									<img src="<?php // if ($currentBusiness->fullLogoFile === NULL) {echo "../../images/ultiscape/etc/noLogo.png";} else echo "../../images/ultiscape/uploads/businessFullLogoFile/".htmlspecialchars($currentBusiness->fullLogoFile); ?>" style="height: 100%; float: left;">
									
									<input class="defaultInput" type="checkbox" name="useNewLogo" id="useNewLogo"><label for="useNewLogo"> <p style="display: inline; clear: both;">Upload a new logo</p></label>
									<br><br>

									<label for="fullLogoFile" style="clear: both;"><p>Logo File</p></label>
									<input type="file" name="fullLogoFile" id="fullLogoFile" style="clear: both;">
									<span id="fullLogoFileError" class="underInputError" style="display: none;"><br>There was an error uploading this logo file.</span>
								</div> -->

								<br><br>

								<div class="twoCol">

									<div>
										<label for="address1"><p>Address Line 1</p></label>
										<input class="defaultInput" type="text" name="address1" id="address1" placeholder="Address Line 1..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->address1); ?>">
										<span id="address1Error" class="underInputError" style="display: none;"><br>Input a valid address.</span>
										<br><br>

										<label for="address2"><p>Address Line 2</p></label>
										<input class="defaultInput" type="text" name="address2" id="address2" placeholder="" style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->address2); ?>">
										<span id="address2Error" class="underInputError" style="display: none;"><br>Input a valid address.</span>
										<br><br>
									</div>

									<div>
										<label for="city"><p>City</p></label>
										<input class="defaultInput" type="text" name="city" id="city" placeholder="City..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->city); ?>">
										<span id="cityError" class="underInputError" style="display: none;"><br>Input a valid city.</span>
										<br><br>

										<div class="twoCol">
											<div>
												<label for="state"><p>State</p></label>
												<input class="defaultInput" type="text" name="state" id="state" placeholder="State..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->state); ?>">
												<span id="stateError" class="underInputError" style="display: none;"><br>Input a valid state.</span>
											</div>

											<div>
												<label for="zipCode"><p>Zip Code</p></label>
												<input class="defaultInput" type="number" name="zipCode" id="zipCode" placeholder="Zip code..." style="width: 90%" value="<?php echo htmlspecialchars($currentBusiness->zipCode); ?>">
												<span id="zipCodeError" class="underInputError" style="display: none;"><br>Input a number.</span>
											</div>
										</div>
									</div>
									
								</div>

								<br>

								<label for="phone1"><p>Phone Number</p></label>
								<div class="fourColCompact">
									<div>
										<input class="defaultInput" type="text" name="phonePrefix" id="phonePrefix" placeholder="+1" value="<?php echo htmlspecialchars($currentBusiness->phonePrefix); ?>" style="width: 1.5em;">
										<span id="phonePrefixError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone1" id="phone1" placeholder="555" value="<?php echo htmlspecialchars($currentBusiness->phone1); ?>" style="width: 3em;">
										<span id="phone1Error" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone2" id="phone2" placeholder="555" value="<?php echo htmlspecialchars($currentBusiness->phone2); ?>" style="width: 3em;">
										<span id="phone2Error" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>

									<div>
										<input class="defaultInput" type="text" name="phone3" id="phone3" placeholder="5555" value="<?php echo htmlspecialchars($currentBusiness->phone3); ?>" style="width: 3em;">
										<span id="phone3Error" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
								</div>

								<br><br>

								<label for="timeZone"><p>Time Zone</p></label>
								<?php

									$optionsList = new jessedp\Timezones\Timezones();
									echo $optionsList->create('timeZone', htmlspecialchars($currentBusiness->timeZone), array('attr' => array('id' => 'timeZone', 'class' => 'defaultInput', 'style' => 'max-width: 100%'), 'with_regions' => false));

								?>

							</div>
							
							<br><br>

							<h3>Units</h3>
							<div class="defaultInputGroup">
								<div class="threeCol">
									<div>
										<label for="currencySymbol"><p>Currency Symbol</p></label>
										<input class="defaultInput" type="text" name="currencySymbol" id="currencySymbol" placeholder="$" value="<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>" style="width: 1em;">
										<span id="currencySymbolError" class="underInputError" style="display: none;"><br>Must be 1 character.</span>
									</div>

									<div>
										<label for="areaSymbol"><p>Area Unit (²)</p></label>
										<input class="defaultInput" type="text" name="areaSymbol" id="areaSymbol" placeholder="ft" value="<?php echo htmlspecialchars($currentBusiness->areaSymbol); ?>" style="width: 2em;">
										<span id="areaSymbolError" class="underInputError" style="display: none;"><br>Must be 1-4 characters.</span>
									</div>

									<div>
										<label for="distanceSymbol"><p>Travel Distance Unit</p></label>
										<input class="defaultInput" type="text" name="distanceSymbol" id="distanceSymbol" placeholder="mi" value="<?php echo htmlspecialchars($currentBusiness->distanceSymbol); ?>" style="width: 2em;">
										<span id="distanceSymbolError" class="underInputError" style="display: none;"><br>Must be 1-4 characters.</span>
									</div>
								</div>
							</div>

							<br><br>

							<h3>Customers</h3>
							<div class="defaultInputGroup">
								<div class="twoCol">
									<div>
										<input class="defaultInput" type="checkbox" name="creditAlertIsEnabled" id="creditAlertIsEnabled" <?php if ($currentBusiness->creditAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="creditAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when credit is less than or equal to</p></label>
										<br>
										<input class="defaultInput" type="number" name="creditAlertAmount" id="creditAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentBusiness->creditAlertAmount, 2)); ?>" style="width: 5em;">
										<span id="creditAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
									<div>
										<input class="defaultInput" type="checkbox" name="balanceAlertIsEnabled" id="balanceAlertIsEnabled" <?php if ($currentBusiness->balanceAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="balanceAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when balance is greater than or equal to</p></label>
										<br>
										<input class="defaultInput" type="number" name="balanceAlertAmount" id="balanceAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentBusiness->balanceAlertAmount, 2)); ?>" style="width: 5em;">
										<span id="balanceAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
								</div>
							</div>
							
							<br><br>

							<h3>Payroll</h3>
							<div class="defaultInputGroup">
								<label for="modPayrSalDefaultType"><p>Default Salary Type</p></label>

								<select class="defaultInput" name="modPayrSalDefaultType" id="modPayrSalDefaultType">
									<option value="none"<?php if ($currentBusiness->modPayrSalDefaultType == 'none') {echo ' selected="selected"';} ?>>None</option>
									<option value="hourly"<?php if ($currentBusiness->modPayrSalDefaultType == 'hourly') {echo ' selected="selected"';} ?>>Hourly (Based on Time Logs)</option>
									<option value="aPerJob"<?php if ($currentBusiness->modPayrSalDefaultType == 'aPerJob') {echo ' selected="selected"';} ?>>Fixed amount per job completed</option>
									<option value="pPerJob"<?php if ($currentBusiness->modPayrSalDefaultType == 'pPerJob') {echo ' selected="selected"';} ?>>% of job price per job completed</option>
								</select>
								<span id="modPayrSalDefaultTypeError" class="underInputError" style="display: none;"><br>Select an option from the menu.</span>

								<br><br>

								<div class="threeCol">
									<div>
										<label for="modPayrSalBaseHourlyRate"><p>Default Hourly Rate (<?php echo htmlspecialchars($currentBusiness->currencySymbol) ?>)</p></label>
										<input class="defaultInput" type="number" min="0" max="99999" step="0.01" name="modPayrSalBaseHourlyRate" id="modPayrSalBaseHourlyRate" placeholder="$0.00" value="<?php echo htmlspecialchars($currentBusiness->modPayrSalBaseHourlyRate); ?>" style="width: 4em;">
										<span id="modPayrSalBaseHourlyRateError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>

									<div>
										<label for="modPayrSalBasePerJob"><p>Default Per Job Amount (<?php echo htmlspecialchars($currentBusiness->currencySymbol) ?>)</p></label>
										<input class="defaultInput" type="number" min="0" max="99999" step="0.01" name="modPayrSalBasePerJob" id="modPayrSalBasePerJob" placeholder="$0.00" value="<?php echo htmlspecialchars($currentBusiness->modPayrSalBasePerJob); ?>" style="width: 4em;">
										<span id="modPayrSalBasePerJobError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>

									<div>
									<label for="modPayrSalBaseJobPercent"><p>Default Job Percent (%)</p></label>
										<input class="defaultInput" type="number" min="0" max="100" step="1" name="modPayrSalBaseJobPercent" id="modPayrSalBaseJobPercent" placeholder="0%" value="<?php echo htmlspecialchars($currentBusiness->modPayrSalBaseJobPercent); ?>" style="width: 3em;">
										<span id="modPayrSalBaseJobPercentError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
								</div>

								<!-- <input class="defaultInput" type="checkbox" name="modPayrSatLinkedToDue" id="modPayrSatLinkedToDue" <?php // if ($currentBusiness->modPayrSatLinkedToDue == '1') {echo 'checked="checked"';} ?>><label for="modPayrSatLinkedToDue"> <p style="display: inline; clear: both;">Check to ensure that <i>Payroll Satisfactions</i> (Payments to your staff to satisfy <i>Payment Dues</i>) MUST be linked to a <i>Payment Due</i>.</p></label> -->
							</div>
							
							<br><br>

							<h3>Documents</h3>
							<div class="defaultInputGroup">
								<div class="twoCol" style="grid-template-columns: 25% 75%;">
									<div>
										<label for="docIdMin"><p>Minimum Document ID</p></label>
										<input class="defaultInput" type="number" min="0" step="1" name="docIdMin" id="docIdMin" placeholder="1" value="<?php echo htmlspecialchars($currentBusiness->docIdMin); ?>" style="width: 4em;">
										<span id="docIdMinError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
									<div>
										<p>Specifies the minimum number that the incremental IDs (for Invoices, Estimates, etc.) are <i>allowed</i> to start at.</p>
									</div>
								</div>

								<br><br>

								<input class="defaultInput" type="checkbox" name="docIdIsRandom" id="docIdIsRandom" <?php if ($currentBusiness->docIdIsRandom == '1') {echo 'checked="checked"';} ?>><label for="docIdIsRandom"> <p style="display: inline; clear: both;">Use random (not incremental) document IDs</p></label>
							</div>
							
							<br><br>

							<h3>Invoices</h3>
							<div class="defaultInputGroup">
								<div class="twoCol" style="grid-template-columns: 25% 75%;">
									<div>
										<label for="invoiceTerm"><p>Default Invoice Term (Days)</p></label>
										<input class="defaultInput" type="number" min="0" step="1" name="invoiceTerm" id="invoiceTerm" placeholder="None" value="<?php echo htmlspecialchars($currentBusiness->invoiceTerm); ?>" style="width: 4em;">
										<span id="invoiceTermError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
									<div>
										<p>How long a customer has to pay an invoice. Set to nothing for no invoice term enforcement.</p>
									</div>
								</div>

								<br><br>

								<input class="defaultInput" type="checkbox" name="autoApplyCredit" id="autoApplyCredit" <?php if ($currentBusiness->autoApplyCredit == '1') {echo 'checked="checked"';} ?>><label for="autoApplyCredit"> <p style="display: inline; clear: both;">By default, automatically apply available customer credit to new invoices</p></label>
								<br><br>
								
								<a name="paymentmethods"></a>
								<p>Available Payment Methods</p>
								
								<table class="defaultTable" style="width: 100%;" id="itemsTable">
									<tr id="tableHeader">
										<th class="la" style="text-decoration: underline; width: 25%;">Name</th>
										<th class="la" style="text-decoration: underline;">Settings</th>
									</tr>
									<tbody id="paymentMethods">

										<?php

											$currentBusiness->pullPaymentMethods("ORDER BY dateTimeAdded ASC");
											foreach ($currentBusiness->paymentMethods as $paymentMethodId) {
												$currentPaymentMethod = new paymentMethod($paymentMethodId);
												if ($currentPaymentMethod->existed) {
													echo '<tr>
													<td><input type="hidden" name="paymentMethodId[]" value="'.htmlspecialchars($paymentMethodId).'">
													<input class="invisibleInput" style="height: 1.3em; width: 5em; max-width: 30vw; font-size: 1.3em;" type="text" name="paymentMethodName[]" value="'.htmlspecialchars($currentPaymentMethod->name).'"> 
													<br><br>
													<span id="deletePaymentMethod:::'.htmlspecialchars($paymentMethodId).'" class="smallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../images/ultiscape/icons/trash.svg"></span>
													</td>
													<td class="tg-0lax">
													<div class="twoCol" style="grid-template-columns: 30% 70%">
														<div>
															<p>Amount Cut</p>
																<input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="0.01" name="paymentMethodAmountCut[]" value="'.htmlspecialchars($currentPaymentMethod->amountCut).'" min="0" style="width: 5em;" value="25">
															<p>Percent Cut</p>
																<input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step=".001" name="paymentMethodPercentCut[]" value="'.htmlspecialchars($currentPaymentMethod->percentCut).'" min="0" max="100" style="width: 5em;" value="1"><label for="paymentMethodTax">%</label>
														</div>

														<div>
															<p>Notes to Customer (included on payment screen)</p>
																<textarea class="invisibleInput" style="height: 1.3em; width: 15em; max-width: 20vw; font-size: 1.3em; resize: none; height: 4em;" type="text" name="paymentMethodNotes[]">'.htmlspecialchars($currentPaymentMethod->notes).'</textarea>
														</div>
													</div>
													</td>
													</tr>';
												}
											}

										?>

									</tbody>

									<tr id="subTotalRow">
										<td colspan="2"><a href="#" id="addPaymentMethod">Add Method</a><img style="display: none; width: 2em;" src="../../images/ultiscape/etc/loading.gif" class="addPaymentMethodLoadingGif"></td>
									</tr>

								</table>

								<br>

								<p>Methods that can be chosen from when recording a payment for an invoice. If PayPal is enabled and connected, a PayPal payment method will be automatically created.</p>
							</div>
							
							<br><br>

							<h3>Estimates</h3>
							<div class="defaultInputGroup">
								<div class="twoCol" style="grid-template-columns: 25% 75%;">
									<div>
										<label for="estimateValidity"><p>Default Estimate Validity (Days)</p></label>
										<input class="defaultInput" type="number" min="0" step="1" name="estimateValidity" id="estimateValidity" placeholder="None" value="<?php echo htmlspecialchars($currentBusiness->estimateValidity); ?>" style="width: 4em;">
										<span id="estimateValidityError" class="underInputError" style="display: none;"><br>Input a number.</span>
									</div>
									<div>
										<p>How long an estimate can be redeemed for a service. Set to nothing for no estimate validity enforcement.</p>
									</div>
								</div>
							</div>
							
							<br><br>

							<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">

							<input type="hidden" name="deletePaymentMethodAuthToken" id="authToken" value="<?php echo htmlspecialchars($deletePaymentMethodAuthToken->authTokenId); ?>">
							<input type="hidden" name="addPaymentMethodAuthToken" id="authToken" value="<?php echo htmlspecialchars($addPaymentMethodAuthToken->authTokenId); ?>">
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
