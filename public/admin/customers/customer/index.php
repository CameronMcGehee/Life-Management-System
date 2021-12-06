<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// This is the business select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['ultiscape_adminId'])) {
		header("location: ../login");
	}

	require_once '../../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../../lib/table/admin.php';
	require_once '../../../../lib/table/customer.php';
	require_once '../../../../lib/table/customerEmailAddress.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentCustomer = new customer($_GET['id']);
	} else {
		$currentCustomer = new customer();
	}

	if ($currentCustomer->existed) {
		$titleName = $currentCustomer->firstName.' '.$currentCustomer->lastName;
	} else {
		$titleName = 'New Customer';
	}

	require_once '../../../../lib/timezones/Timezones.php';

	echo $adminUIRender->renderAdminHtmlTop('../../../', htmlspecialchars($titleName), 'Edit your UltiScape business.');
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

?>

	<style>
		/* Hide scrollbar for Chrome, Safari and Opera */
		#twoColContentWrapper::-webkit-scrollbar {
			display: none;
		}

		/* Hide scrollbar for IE, Edge and Firefox */
		#twoColContentWrapper {
			-ms-overflow-style: none;  /* IE and Edge */
			scrollbar-width: none;  /* Firefox */
		}
	</style>

	<script src="../../../js/etc/animation/shake.js"></script>

	<script src="../../../js/etc/form/showFormError.js"></script>
	<script src="../../../js/etc/form/clearFormErrors.js"></script>

	<script>
		var formData;
		var scriptOutput;
		var customerId;
		var formState;
		var url = new URL(window.location.href);

		var isNewCustomer = <?php if ($currentCustomer->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		$(function() {

			function setUnsaved() {
				$(".changesMessage").each(function () {
					$(this).html('<span style="color: gray; width: 10em;">⏳ Saving changes...</span>');
				});
				// $(".changesMessage").each(function () {
				// 	$(this).shake(50);
				// });
				changesSaved = false;
			}

			function setWaitingForError() {
				$(".changesMessage").each(function () {
					$(this).html('<span style="color: red;">Uh oh, fix the error!</span>');
				});
				$(".changesMessage").each(function () {
					$(this).shake(50);
				});
				waitingForError = true;
			}

			function setSaved() {
				$(".changesMessage").each(function () {
					$(this).html('<span style="color: green;">Up to date ✔</span>');
				});
				changesSaved = true;
				waitingForError = false;
			}

			if ($.isNumeric(url.searchParams.get('wsl'))) {
				$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			}

			function updateEmails() {
				formData = $("#customerForm").serialize();
				$("#scriptLoader").load("./scripts/async/updateEmailAddresses.script.php", {
					formData: formData
				}, function () {
					formData = $("#customerForm").serialize();
					$("#emailAddressesLoader").load("./includes/emailAddressDynForm.inc.php", {
					customerId: '<?php echo $currentCustomer->customerId ?>'
					}, function () {
						
					});
				});
			}

			function checkChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				
				formData = $("#customerForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editcustomer.script.php", {
					formData: formData
				}, function () {
					scriptOutput = $("#scriptLoader").html().split(":::");
					customerId = scriptOutput[0];
					formState = scriptOutput[1];
					clearFormErrors();

					switch (formState) {
						case 'success':
							setSaved();
							url.searchParams.set('id', customerId);
							if (isNewCustomer) {
								window.location.replace(url.href);
							}
							break;
						case 'password':
							$('#password').show();
							$("#showPasswordButton").html("Hide");
							setWaitingForError();
							showFormError("#"+formState+"Error", "#"+formState);
							$("#"+formState).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							break;
						default:
							setWaitingForError();
							showFormError("#"+formState+"Error", "#"+formState);
							$("#"+formState).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							break;
					}

					$("#scriptLoader").load("./scripts/async/updateEmailAddresses.script.php", {
						customerId: '<?php echo $currentCustomer->customerId ?>',
						formData: formData
					}, function () {
						$("#emailAddressesLoader").load("./includes/emailAddressDynForm.inc.php", {
						customerId: '<?php echo $currentCustomer->customerId ?>'
						}, function () {
							
						});
					});

					$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				});
				changesSaved = true;
			}

			// Load the email form on startup
			$("#emailAddressesLoader").load("./includes/emailAddressDynForm.inc.php", {
				customerId: '<?php echo $currentCustomer->customerId ?>'
			});
			
			
			$("#customerForm input, #customerForm select, #customerForm textarea").change(function () {
				setUnsaved();
				lastChange = new Date();
			});


			var interval = setInterval(function() {
				if (changesSaved == false && (new Date() - lastChange) / 1000 > .5) {
					checkChanges();
				}
			}, 1000);

			window.onbeforeunload = function() {
				if (changesSaved == false || waitingForError == true) {
					return "Changes have not been saved yet. Are you sure you would like to leave?";
				} else {
					return;
				}
			};


			// Show and hide password button

			var pass_field = $("#password");

			$("#showPasswordButton").on("click", function () {
				if($("#showPasswordButton").html() == "Show"){
					$('#password').show();
					$("#showPasswordButton").html("Hide");
				} else {
					$('#password').hide();
					$("#showPasswordButton").html("Show");
				}
			})
		});
	</script>
	<script>
        
    </script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
	<div class="cmsBodyWrapper">

		<?php 
			echo $adminUIRender->renderAdminTopBar('../../../', true, true, true);
		?>

		<?php 
            echo $adminUIRender->renderAdminSideBar('../../../');
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white;">
				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
				<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
			</div>
			<form class="defaultForm maxHeight" id="customerForm">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<?php

							if (empty($currentCustomer->lastName)) {
								$nameOutput = $currentCustomer->firstName;
							} else {
								$nameOutput = $currentCustomer->firstName.' '.$currentCustomer->lastName;
							}

						?>
						<br>

						<input class="bigInput" style="width: 93%;" type="text" name="firstLastName" id="firstLastName" placeholder="Name..." value="<?php echo htmlspecialchars($nameOutput); ?>">
						<span id="firstLastNameError" class="underInputError" style="display: none;"><br>Please enter a name, preferrably first and last.</span>

						<br><br>

						<table class="defaultTable" style="width: 35em; max-width: 100%;">
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Email(s)</td>
								<td class="defaultTableCell" style="padding: 1em;" id="emailAddressesLoader"><img style="width: 2em;" src="../../../images/ultiscape/etc/loading.gif"></td>
							</tr>
							<tr>
								<td class="defaultTableCell" style="padding: 1em;">Billing Address</td>
								<td class="defaultTableCell" style="padding: 1em;">
									<div>
										<label for="billAddress1"><p>Address Line 1</p></label>
										<input class="almostInvisibleInput" style="font-size: 1.2em; width: 80%;" type="text" name="billAddress1" id="billAddress1" value="<?php echo htmlspecialchars($currentCustomer->billAddress1); ?>">
										<br><br>

										<label for="billAddress2"><p>Address Line 2</p></label>
										<input class="almostInvisibleInput" style="font-size: 1.2em; width: 80%;" type="text" name="billAddress2" id="billAddress2" value="<?php echo htmlspecialchars($currentCustomer->billAddress2); ?>">
										<br><br>

										<label for="billCity"><p>City</p></label>
										<input class="almostInvisibleInput" style="font-size: 1.2em; width: 80%;" type="text" name="billCity" id="billCity" value="<?php echo htmlspecialchars($currentCustomer->billCity); ?>">
										<br><br>

										<label for="billState"><p>State</p></label>
										<input class="almostInvisibleInput" style="font-size: 1.2em; width: 80%;" type="text" name="billState" id="billState" value="<?php echo htmlspecialchars($currentCustomer->billState); ?>">
										<br><br>

										<label for="billZipCode"><p>Zip Code</p></label>
										<input class="almostInvisibleInput" style="font-size: 1.2em; width: 80%;" type="text" name="billZipCode" id="billZipCode" value="<?php echo htmlspecialchars($currentCustomer->billZipCode); ?>">

										<span id="billAddressError" class="underInputError" style="display: none;"><br><br>Please enter a valid address.</span>
									</div>
								</td>
							</tr>
						</table>

						<br><br>

						<input class="defaultInput" type="checkbox" name="overrideAutoApplyCredit" id="overrideAutoApplyCredit" <?php if ($currentCustomer->overrideAutoApplyCredit == '1') {echo 'checked="checked"';} ?>><label for="overrideAutoApplyCredit"> <p style="display: inline; clear: both;">Automatically apply any available credit to new invoices</p></label>

						<br><br>

						<div>
							<input class="defaultInput" type="checkbox" name="overrideCreditAlertIsEnabled" id="overrideCreditAlertIsEnabled" <?php if ($currentCustomer->overrideCreditAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideCreditAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when credit is less than or equal to</p></label>
							<br>
							<input class="defaultInput" type="number" name="overrideCreditAlertAmount" id="overrideCreditAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentCustomer->overrideCreditAlertAmount, 2)); ?>" style="width: 5em;">
							<input class="defaultInput" type="checkbox" name="overrideCreditAlertAmountUseDefault" id="overrideCreditAlertAmountUseDefault" <?php if ($currentCustomer->overrideCreditAlertAmount == NULL) {echo 'checked="checked"';} ?>><label for="overrideCreditAlertAmountUseDefault"> <p style="display: inline; clear: both;">Use default</p></label>
							<span id="overrideCreditAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
						</div>
						<br>
						<div>
							<input class="defaultInput" type="checkbox" name="overrideBalanceAlertIsEnabled" id="overrideBalanceAlertIsEnabled" <?php if ($currentCustomer->overrideBalanceAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideBalanceAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when balance is greater than or equal to</p></label>
							<br>
							<input class="defaultInput" type="number" name="overrideBalanceAlertAmount" id="overrideBalanceAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentCustomer->overrideBalanceAlertAmount, 2)); ?>" style="width: 5em;">
							<input class="defaultInput" type="checkbox" name="overrideBalanceAlertAmountUseDefault" id="overrideBalanceAlertAmountUseDefault" <?php if ($currentCustomer->overrideBalanceAlertAmount == NULL) {echo 'checked="checked"';} ?>><label for="overrideBalanceAlertAmountUseDefault"> <p style="display: inline; clear: both;">Use default</p></label>
							<span id="overrideBalanceAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
						</div>

						<br><br>

						<input class="defaultInput" type="checkbox" name="allowCZSignIn" id="allowCZSignIn" <?php if ($currentCustomer->allowCZSignIn == '1') {echo 'checked="checked"';} ?>><label for="allowCZSignIn"> <p style="display: inline; clear: both;">Allow this customer to sign into the <a href="../../../customer">Customer Portal</a></p></label>
						<br><br>
						<label for="password"><p>Customer Portal Password</p></label>
						<input class="defaultInput" style="font-size: 1.2em; width: 10em; display: none;" type="text" name="password" id="password" value="<?php echo htmlspecialchars($currentCustomer->password); ?>">
						<span class="smallButtonWrapper greenButton" id="showPasswordButton" style="display: inline-block;">Show</span>
						<span id="passwordError" class="underInputError" style="display: none;"><br>You must enter a customer-unique password for security purposes.</span>
						<br><br>

						<label for="notes"><p>Notes (Private to Admins)</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="notes" id="notes"><?php echo htmlspecialchars($currentCustomer->notes); ?></textarea>
						<br><br>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br>
						<span class="desktopOnlyBlock">
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
						</span>

						<br><hr><br>

						<h3>Other Info</h3>

						<?php
							$addedDate = new DateTime($currentCustomer->dateTimeAdded);
						?>
						<p>Added on <?php echo $addedDate->format('D, d M y'); ?></p>
					</div>
				</div>
							
				<?php
					// Generate an auth token for the form
					require_once '../../../../lib/table/authToken.php';
					$token = new authToken();
					$token->authName = 'editCustomer';
					$token->set();
				?>

				<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">

				<input type="hidden" name="customerId" id="customerId" value="<?php echo htmlspecialchars($currentCustomer->customerId); ?>">
				</div>
			
			</form>
		
		<?php
			echo $adminUIRender->renderAdminFooter('../../../');
		?>

		<?php 
			echo $adminUIRender->renderAdminMobileNavBar('../../../');
		?>

	</div>

	<?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../../');
	?>
</body>
<?php 
	echo $adminUIRender->renderAdminHtmlBottom('../../../');
?>
