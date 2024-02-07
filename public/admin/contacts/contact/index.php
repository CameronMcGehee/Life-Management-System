<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// This is the workspace select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['lifems_adminId'])) {
		header("location: ../../login");
	}

	require_once '../../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../../lib/table/admin.php';
	require_once '../../../../lib/table/contact.php';
	require_once '../../../../lib/render/etc/tagEditor.php';
	require_once '../../../../lib/table/contactEmailAddress.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentContact = new contact($_GET['id']);
	} else {
		$currentContact = new contact();
	}

	if ($currentContact->existed) {
		$titleName = $currentContact->firstName.' '.$currentContact->lastName;
	} else {
		$titleName = 'New Contact';
	}

	if ($currentContact->workspaceId != $_SESSION['lifems_workspaceId']) {
        header("location: ./");
		exit();
    }

	echo $adminUIRender->renderAdminHtmlTop('../../../', [
		"pageTitle" => htmlspecialchars($titleName),
		"pageDescription" => 'Edit '.htmlspecialchars($titleName).'.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editContact';
	$mainAuthToken->set();

	$updateEmailAddressesAuthToken = new authToken();
	$updateEmailAddressesAuthToken->authName = 'updateEmailAddresses';
	$updateEmailAddressesAuthToken->set();

	$deleteEmailAddressAuthToken = new authToken();
	$deleteEmailAddressAuthToken->authName = 'deleteEmailAddress';
	$deleteEmailAddressAuthToken->set();

	$updatePhoneNumbersAuthToken = new authToken();
	$updatePhoneNumbersAuthToken->authName = 'updatePhoneNumbers';
	$updatePhoneNumbersAuthToken->set();

	$deletePhoneNumberAuthToken = new authToken();
	$deletePhoneNumberAuthToken->authName = 'deletePhoneNumber';
	$deletePhoneNumberAuthToken->set();

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
		var contactId ='<?php echo $currentContact->contactId; ?>';
		var formState;
		var checkEmails;
		var checkPhoneNumbers;
		var url = new URL(window.location.href);

		var isNewContact = <?php if ($currentContact->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

		$(function() {

			if (isNewContact) {
				$("#firstLastName").focus();
			}

			$("#contactForm").submit(function(event) {
				event.preventDefault();
			});

			function setUnsaved() {
				$(".changesMessage").each(function () {
					$(this).html('<span style="color: gray; width: 10em;">⏳ Saving changes...</span>');
				});
				// $(".changesMessage").each(function () {
				// 	$(this).shake(50);
				// });
				changesSaved = false;
			}

			function inputChange (e) {
				setUnsaved();
				lastChange = new Date();
			}

			setInterval(() => {
				currentTime = new Date();
				if ((currentTime.getTime() - lastChange.getTime()) > 500 && !changesSaved) {
					checkChanges();
				}
			}, 1000);

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

			function registerEmailDeleteButtonClicks() {
				$("span[id*='deleteEmailAddress:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deleteEmailAddress.script.php", {
							emailAddressId: currentId,
							deleteEmailAddressAuthToken: '<?php echo $deleteEmailAddressAuthToken->authTokenId; ?>'
						}, function () {
							loadEmails();
						});
					});
				});
			}

			function loadEmails() {
				$("#emailAddressesLoader").load("./includes/emailAddresses.inc.php", {
					contactId: contactId
				}, function () {
					$("#contactForm :input").change(function () {
						inputChange();
					});
					registerEmailDeleteButtonClicks();
				});
			}

			function registerPhoneNumberDeleteButtonClicks() {
				$("span[id*='deletePhoneNumber:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deletePhoneNumber.script.php", {
							phoneNumberId: currentId,
							deletePhoneNumberAuthToken: '<?php echo $deletePhoneNumberAuthToken->authTokenId; ?>'
						}, function () {
							loadPhoneNumbers();
						});
					});
				});
			}

			function loadPhoneNumbers() {
				$("#phoneNumbersLoader").load("./includes/phoneNumbers.inc.php", {
					contactId: contactId
				}, function () {
					$("#contactForm :input").change(function () {
						inputChange();
					});
					registerPhoneNumberDeleteButtonClicks();
				});
			}

			function checkChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				
				formData = $("#contactForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editcontact.script.php", {
					contactId: contactId,
					formData: formData
				}, function () {
					scriptOutput = $("#scriptLoader").html().split(":::");
					contactId = scriptOutput[0];
					formState = scriptOutput[1];
					clearFormErrors();

					checkEmails = false;
					checkPhoneNumbers = false;

					switch (formState) {
						case 'success':
							setSaved();
							if (isNewContact) {
								isNewContact = false;
								window.history.pushState("string", 'LifeMS (Admin) - New Contact', "./?id="+contactId);
								window.location.reload();
							}
							checkEmails = true;
							checkPhoneNumbers = true;
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
							checkEmails = false;
							checkPhoneNumbers = false;
							break;
						default:
							setWaitingForError();
							showFormError("#"+formState+"Error", "#"+formState);
							$("#"+formState).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							checkEmails = false;
							checkPhoneNumbers = false;
							break;
					}

					if (checkEmails) {
						$("#scriptLoader").load("./scripts/async/updateEmailAddresses.script.php", {
							contactId: contactId,
							formData: formData
						}, function () {
							scriptOutput = $("#scriptLoader").html().split("::::");
							formState = scriptOutput[1];

							switch (formState) {
								case 'success':
									setSaved();
									checkPhoneNumbers = true;
									// Reload the emails box if a new email was put in
									if ($("#newEmailAddress").val().length > 0) {
										loadEmails();
									}
									break;
								default:
									setWaitingForError();
									showFormError("#"+formState+"Error", "#"+formState);
									$("#"+formState).shake(50);
									checkPhoneNumbers = false;
									$('.loadingGif').each(function() {
										$(this).fadeOut(100);
									});
									break;
							}
						});
					}

					if (checkPhoneNumbers) {
						$("#scriptLoader").load("./scripts/async/updatePhoneNumbers.script.php", {
							contactId: contactId,
							formData: formData
						}, function () {
							scriptOutput = $("#scriptLoader").html().split("::::");
							formState = scriptOutput[1];

							switch (formState) {
								case 'success':
									setSaved();
									// Reload the emails box if a new email was put in
									if ($("#newPhoneNumber").val().length > 0) {
										loadPhoneNumbers();
									}
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
						});
					}

					$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				});
				changesSaved = true;
			}

			// Load the email form on startup
			loadEmails();

			// Load the phone number form on startup
			loadPhoneNumbers();

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
					$('#password').show(200);
					$("#showPasswordButton").html("Hide");
				} else {
					$('#password').hide(200);
					$("#showPasswordButton").html("Show");
				}
			})
		});
	</script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
	<div class="adminBodyWrapper">

		<?php 
			echo $adminUIRender->renderAdminTopBar('../../../', true, true, true);
		?>

		<?php 
            echo $adminUIRender->renderAdminSideBar('../../../');
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white; z-index: 99;">
				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
				<img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif">
			</div>

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<form class="defaultForm" id="contactForm">

							<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars(strval($mainAuthToken->authTokenId)); ?>">
							<input type="hidden" name="updateEmailAddressesAuthToken" id="updateEmailAddressesAuthToken" value="<?php echo htmlspecialchars(strval($updateEmailAddressesAuthToken->authTokenId)); ?>">
							<input type="hidden" name="updatePhoneNumbersAuthToken" id="updatePhoneNumbersAuthToken" value="<?php echo htmlspecialchars(strval($updatePhoneNumbersAuthToken->authTokenId)); ?>">


							<?php

								if (empty($currentContact->lastName)) {
									$nameOutput = $currentContact->firstName;
								} else {
									$nameOutput = $currentContact->firstName.' '.$currentContact->lastName;
								}

							?>

							<br>

							<br>

							<h3>Contact Info</h3>
							<div class="defaultInputGroup">
								
								<label for="firstLastName"><p>Name</p></label>
								<input class="bigInput" style="width: 95%;" type="text" name="firstLastName" id="firstLastName" placeholder="Name..." value="<?php echo htmlspecialchars(strval($nameOutput)); ?>">
								<span id="firstLastNameError" class="underInputError" style="display: none;"><br>Please enter a name, preferrably first and last.</span>

								<?php

									// Tag Editor

									if ($currentContact->existed) {
										$tagEditor = new tagEditor("underNameTagEditor", [
											'rootPathPrefix' => '../../../',
											'type' => 'contact',
											'objectId' => $currentContact->contactId,
											'largeSize' => false
										]);
										$tagEditor->render();
										echo '<br><br>'.$tagEditor->output.'<br>';
									}

									if (!$currentContact->existed) {
										echo "<br><br>";
									}

								?>

								<label for="billAddress1"><p>Billing Address</p></label>
								<div>
									<!-- <label for="billAddress1"><p>Address</p></label> -->
									<input placeholder="Line 1..." class="defaultInput" style="font-size: 1.2em; width: 80%;" type="text" name="billAddress1" id="billAddress1" value="<?php echo htmlspecialchars(strval($currentContact->billAddress1)); ?>">
									<br><br>

									<input placeholder="Line 2..." class="defaultInput" style="font-size: 1.2em; width: 80%;" type="text" name="billAddress2" id="billAddress2" value="<?php echo htmlspecialchars(strval($currentContact->billAddress2)); ?>">
									<br><br>

									<div class="twoCol">
										<div>
											<label for="billCity"><p>City</p></label>
											<input placeholder="City..." class="defaultInput" style="font-size: 1.2em; width: 80%;" type="text" name="billCity" id="billCity" value="<?php echo htmlspecialchars(strval($currentContact->billCity)); ?>">
											,
										</div>
										<div>
											<label for="billState"><p>State</p></label>
											<input placeholder="State..." class="defaultInput" style="font-size: 1.2em; width: 80%;" type="text" name="billState" id="billState" value="<?php echo htmlspecialchars(strval($currentContact->billState)); ?>">
										</div>
									</div>

									<br>

									<label for="billZipCode"><p>Zip Code</p></label>
									<input placeholder="Zip Code..." class="defaultInput" style="font-size: 1.2em; width: 6em;" type="text" name="billZipCode" id="billZipCode" value="<?php echo htmlspecialchars(strval($currentContact->billZipCode)); ?>">

									<span id="billAddressError" class="underInputError" style="display: none;"><br><br>Please enter a valid address.</span>
								</div>

								<br><hr style="border-color: var(--lifemsColorTheme);">

								<table style="width: 100%">
									<tr>
										<td class="defaultTableCell" style="padding: 1em; width: 5em;">Emails</td>
										<td class="defaultTableCell" style="padding: 1em;" id="emailAddressesLoader"><img style="width: 2em;" src="../../../images/lifems/etc/loading.gif"></td>
									</tr>
									<tr>
										<td class="defaultTableCell" style="padding: 1em; width: 5em;">Phone Numbers</td>
										<td class="defaultTableCell" style="padding: 1em;" id="phoneNumbersLoader"><img style="width: 2em;" src="../../../images/lifems/etc/loading.gif"></td>
									</tr>
								</table>
							</div>

							<br><br>

							<h3>Credit and Balance Settings</h3>
							<div class="twoCol defaultInputGroup">
								<div>
									<input class="defaultInput" type="checkbox" name="overrideCreditAlertIsEnabled" id="overrideCreditAlertIsEnabled" <?php if ($currentContact->overrideCreditAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideCreditAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Contact when credit is less than or equal to</p></label>
									<br>
									<input class="defaultInput" type="number" name="overrideCreditAlertAmount" id="overrideCreditAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentContact->overrideCreditAlertAmount, 2)); ?>" style="width: 5em;">
									<input class="defaultInput" type="checkbox" name="overrideCreditAlertAmountUseDefault" id="overrideCreditAlertAmountUseDefault" <?php if ($currentContact->overrideCreditAlertAmount == NULL) {echo 'checked="checked"';} ?>><label for="overrideCreditAlertAmountUseDefault"> <p style="display: inline; clear: both;">Use default</p></label>
									<span id="overrideCreditAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
									<br><br>

									<input class="defaultInput" type="checkbox" name="overrideAutoApplyCredit" id="overrideAutoApplyCredit" <?php if ($currentContact->overrideAutoApplyCredit == '1') {echo 'checked="checked"';} ?>><label for="overrideAutoApplyCredit"> <p style="display: inline; clear: both;">Automatically apply any available credit to new invoices</p></label>
								</div>
								<div>
									<input class="defaultInput" type="checkbox" name="overrideBalanceAlertIsEnabled" id="overrideBalanceAlertIsEnabled" <?php if ($currentContact->overrideBalanceAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideBalanceAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Contact when balance is greater than or equal to</p></label>
									<br>
									<input class="defaultInput" type="number" name="overrideBalanceAlertAmount" id="overrideBalanceAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentContact->overrideBalanceAlertAmount, 2)); ?>" style="width: 5em;">
									<input class="defaultInput" type="checkbox" name="overrideBalanceAlertAmountUseDefault" id="overrideBalanceAlertAmountUseDefault" <?php if ($currentContact->overrideBalanceAlertAmount == NULL) {echo 'checked="checked"';} ?>><label for="overrideBalanceAlertAmountUseDefault"> <p style="display: inline; clear: both;">Use default</p></label>
									<span id="overrideBalanceAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
								</div>
							</div>

							<br><br>

							<h3>Contact Portal Settings</h3>
							<div class="twoCol defaultInputGroup">
								<div>
									<input class="defaultInput" type="checkbox" name="allowCZSignIn" id="allowCZSignIn" <?php if ($currentContact->allowCZSignIn == '1') {echo 'checked="checked"';} ?>><label for="allowCZSignIn"> <p style="display: inline; clear: both;">Allow this contact to sign into the <a href="../../../contact">Contact Portal</a></p></label>
								</div>
								<div>
									<label for="password"><p>Contact Portal Password</p></label>
									<input class="defaultInput" style="font-size: 1.2em; width: 10em; display: none;" type="text" name="password" id="password" value="<?php if (!$currentContact->existed) { $newPassword = new tableUuid('contact', 'password'); echo $newPassword->generatedId; } else {echo htmlspecialchars(strval($currentContact->password));} ?>">
									<span class="smallButtonWrapper greenButton" id="showPasswordButton" style="display: inline-block;">Show</span>
									<span id="passwordError" class="underInputError" style="display: none;"><br>You must enter a contact-unique password for security purposes.</span>
									<span id="passwordInUseError" class="underInputError" style="display: none;"><br>That password is already assigned to another contact.</span>
								</div>
							</div>
							<br><br>

							<label for="notes"><p>Notes (Private to Admins)</p></label>
							<textarea class="defaultInput" style="font-size: 1.2em; width: 95%;" name="notes" id="notes"><?php echo htmlspecialchars(strval($currentContact->notes)); ?></textarea>

						</form>

						<br><br>

						<h3>Properties</h3>
						<br>

						<?php

							// Properties
							require_once '../../../../lib/render/property/propertyTable.php';
							$propertyTable = new propertyTable('contactProperties', [
								'rootPathPrefix' => '../../../',
								'queryParams' => "AND contactId = '".$currentContact->contactId."'",
								'maxRows' => 15,
								'showAdd' => true,
								'showSort' => true,
								'showBatch' => true
							]);
							$propertyTable->render();
							echo $propertyTable->output;

						?>

						<br>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br class="desktopOnlyBlock">
						<span class="desktopOnlyBlock">
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif">
						</span>

						<br><hr><br>

						<h3>Other Info</h3>

						<?php
							$addedDate = new DateTime($currentContact->dateTimeAdded);
						?>

						<p>Added on <?php echo $addedDate->format('D, M d Y'); ?></p>
					</div>
				</div>
		</div>

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
