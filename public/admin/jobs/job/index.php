<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// This is the business select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['ultiscape_adminId'])) {
		header("location: ../../login");
	}

	require_once '../../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../../lib/table/business.php';
	require_once '../../../../lib/table/job.php';
	// require_once '../../../../lib/table/jobCancellation.php';
	// require_once '../../../../lib/table/jobCompleted.php';
	// require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentJob = new job($_GET['id']);
	} else {
		$currentJob = new job();
	}

	$currentBusiness = new business($_SESSION['ultiscape_businessId']);

	if ($currentJob->existed) {
		$titleName = $currentJob->name;
	} else {
		$titleName = 'New Job';
	}

	if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        header("location: ./");
		exit();
    }

	echo $adminUIRender->renderAdminHtmlTop('../../../', htmlspecialchars($titleName), 'Edit '.htmlspecialchars($titleName).'.');
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editJob';
	$mainAuthToken->set();

	$updateJobStaffAuthToken = new authToken();
	$updateJobStaffAuthToken->authName = 'updateJobStaffLinks';
	$updateJobStaffAuthToken->set();

	$deleteJobStaffAuthToken = new authToken();
	$deleteJobStaffAuthToken->authName = 'deleteJobStaffLinks';
	$deleteJobStaffAuthToken->set();

	$updateJobCrewsAuthToken = new authToken();
	$updateJobCrewsAuthToken->authName = 'updateJobCrewsLinks';
	$updateJobCrewsAuthToken->set();

	$deleteJobCrewAuthToken = new authToken();
	$deleteJobCrewAuthToken->authName = 'deleteJobCrewLinks';
	$deleteJobCrewAuthToken->set();

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
		var jobId ='<?php echo $currentJob->jobId; ?>';
		var formState;
		var checkStaff;
		var checkCrews;
		var url = new URL(window.location.href);

		var isNewJob = <?php if ($currentJob->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

		$(function() {

			$("#jobForm").submit(function(event) {
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

			function registerJobStaffDeleteButtonClicks() {
				$("span[id*='deleteJobStaff:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deleteJobStaff.script.php", {
							emailAddressId: currentId,
							deleteJobStaffAuthToken: '<?php echo $deleteJobStaffAuthToken->authTokenId; ?>'
						}, function () {
							loadJobStaff();
						});
					});
				});
			}

			function loadJobStaff() {
				$("#jobStaffLoader").load("./includes/jobStaff.inc.php", {
					jobId: jobId
				}, function () {
					$(":input").change(function () {
						inputChange();
					});
					registerJobStaffDeleteButtonClicks();
				});
			}

			function registerJobCrewsDeleteButtonClicks() {
				$("span[id*='deleteJobCrew:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deleteJobCrew.script.php", {
							jobCrewId: currentId,
							deleteJobCrewAuthToken: '<?php echo $deleteJobCrewAuthToken->authTokenId; ?>'
						}, function () {
							loadJobCrews();
						});
					});
				});
			}

			function loadJobCrews() {
				$("#jobCrewsLoader").load("./includes/jobCrews.inc.php", {
					jobId: jobId
				}, function () {
					$(":input").change(function () {
						inputChange();
					});
					registerJobCrewsDeleteButtonClicks();
				});
			}

			function checkChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				
				formData = $("#jobForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editJob.script.php", {
					jobId: jobId,
					formData: formData
				}, function () {
					scriptOutput = $("#scriptLoader").html().split(":::");
					jobId = scriptOutput[0];
					formState = scriptOutput[1];
					clearFormErrors();

					checkStaff = false;
					checkCrews = false;

					switch (formState) {
						case 'success':
							setSaved();
							if (isNewJob) {
								isNewJob = false;
								window.history.pushState("string", 'UltiScape (Admin) - New Job', "./?id="+jobId);
							}
							checkStaff = true;
							checkCrews = true;
							
							// property selector

							if ($("#customerSelector").val() != 'none') {
								$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
									jobId: jobId,
									customerId: $("#customerSelector").val()
								}, function () {
									$(":input").change(function () {
										inputChange();
									});
								});
							} else {
								$("#propertySelectorLoader").html('');
							}

							break;
						default:
							setWaitingForError();
							showFormError("#"+formState+"Error", "#"+formState);
							$("#"+formState).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							checkStaff = false;
							checkCrews = false;
							break;
					}

					// if (checkStaff) {
					// 	$("#scriptLoader").load("./scripts/async/updateJobStaff.script.php", {
					// 		jobId: jobId,
					// 		formData: formData
					// 	}, function () {
					// 		scriptOutput = $("#scriptLoader").html().split("::::");
					// 		formState = scriptOutput[1];

					// 		switch (formState) {
					// 			case 'success':
					// 				setSaved();
					// 				checkCrews = true;
					// 				// Reload the emails box if a new email was put in
					// 				if ($("#newJobStaff").val().length > 0) {
					// 					loadJobStaff();
					// 				}
					// 				break;
					// 			default:
					// 				setWaitingForError();
					// 				showFormError("#"+formState+"Error", "#"+formState);
					// 				$("#"+formState).shake(50);
					// 				checkCrews = false;
					// 				$('.loadingGif').each(function() {
					// 					$(this).fadeOut(100);
					// 				});
					// 				break;
					// 		}
					// 	});
					// }

					// if (checkCrews) {
					// 	$("#scriptLoader").load("./scripts/async/updateJobCrews.script.php", {
					// 		jobId: jobId,
					// 		formData: formData
					// 	}, function () {
					// 		scriptOutput = $("#scriptLoader").html().split("::::");
					// 		formState = scriptOutput[1];

					// 		switch (formState) {
					// 			case 'success':
					// 				setSaved();
					// 				// Reload the emails box if a new email was put in
					// 				if ($("#newJobCrews").val().length > 0) {
					// 					loadJobCrews();
					// 				}
					// 				break;
					// 			default:
					// 				setWaitingForError();
					// 				showFormError("#"+formState+"Error", "#"+formState);
					// 				$("#"+formState).shake(50);

					// 				$('.loadingGif').each(function() {
					// 					$(this).fadeOut(100);
					// 				});
					// 				break;
					// 		}
					// 	});
					// }

					$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				});
				changesSaved = true;
			}

			// Load the staff form on startup
			loadJobStaff();

			// Load the crews form on startup
			loadJobCrews();

			// Load the property selector on startup
			if ($("#customerSelector").val() != 'none') {
				$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
					jobId: jobId,
					customerId: $("#customerSelector").val()
				}, function () {
					$(":input").change(function () {
						inputChange();
					});
				});
			} else {
				$("#propertySelectorLoader").html('');
			}

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

		});
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
			<form class="defaultForm maxHeight" id="jobForm">

				<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">
				<input type="hidden" name="updateJobStaffAuthToken" id="updateJobStaffAuthToken" value="<?php echo htmlspecialchars($updateJobStaffAuthToken->authTokenId); ?>">
				<input type="hidden" name="updateJobCrewsAuthToken" id="updateJobCrewsAuthToken" value="<?php echo htmlspecialchars($updateJobCrewsAuthToken->authTokenId); ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>

						
						<h3>Job Info</h3>
						<div class="defaultInputGroup">
							<label for="name"><p>Name</p></label>
							<input class="bigInput" style="width: 93%;" type="text" name="name" id="name" placeholder="Name..." value="<?php echo htmlspecialchars($currentJob->name); ?>">
							<span id="nameError" class="underInputError" style="display: none;"><br>Enter a name for the job.</span>

							<br><br>

							<div class="twoCol">
								<div>
									<label for="customerSelector"><p>Customer</p></label>
									<!-- Select customer dialog -->
									<?php
									
										require_once '../../../../lib/render/input/customerSelector.php';
										$customerSelector = new customerSelector("customerSelector", ["name" => 'customer', "selectedId" => $currentJob->linkedToCustomerId]);
										$customerSelector->render();
										echo $customerSelector->output;

									?>

									<span id="customerError" class="underInputError" style="display: none;"><br>Select a customer.</span>	
								</div>

								<div>
									<span id="propertySelectorLoader"></span>
								</div>
							</div>

							<br>

							<label for="description"><p>Description (Visible to Customer)</p></label>
							<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="description" id="description"><?php echo htmlspecialchars($currentJob->description); ?></textarea>
							<span id="descriptionError" class="underInputError" style="display: none;"><br>Enter a valid description.</span>

							<br><br>

							<div class="threeCol">
								<div>
									<label for="name"><p>Price (<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>)</p></label>
									<input class="defaultInput" style="width: 5em;" type="number" min="0.00" max="999999999999" step="0.01" name="price" id="price" placeholder="Free" value="<?php echo htmlspecialchars($currentJob->price); ?>">
									<span id="priceError" class="underInputError" style="display: none;"><br>Enter a number.</span>
								</div>

								<div>
									<label for="name"><p>Estimated Hours</p></label>
									<input class="defaultInput" style="width: 5em;" type="number" min="0" max="999999999999" name="estHours" id="estHours" placeholder="Est. Hours..." value="<?php echo htmlspecialchars($currentJob->estHours); ?>">
									<span id="estHoursError" class="underInputError" style="display: none;"><br>Enter a number.</span>

									<p id="perHourCalc" style="color: gray;">$--/hour</p>
								</div>

								<div>
								<input class="defaultInput" type="checkbox" name="isPrepaid" id="isPrepaid" <?php if ($currentJob->isPrepaid == '1') {echo 'checked="checked"';} ?>><label for="isPrepaid"> <p style="display: inline; clear: both;">Customer has already paid for this service</p></label>
								</div>
							</div>

							<br><hr style="border-color: var(--utliscapeColorTheme);" class="defaultMainShadows"><br>

							<?php

								// Seperate the dateTime strings

								

								if ($currentJob->existed) {
									$startDateArray = explode(' ', $currentJob->startDateTime);
									$endDateArray = explode(' ', $currentJob->endDateTime);
	
									$startDate = $startDateArray[0];
									$endDate = $endDateArray[0];
									$startTime = $startDateArray[1];
									$endTime = $endDateArray[1];
								} else {
									$currentDate = new DateTime();
									$startDate = $currentDate->format('Y-m-d');
									$endDate = $currentDate->format('Y-m-d');
									$startTime = '';
									$endTime = '';
								}

							?>

							<div><span id="clearEndDateTime" class="smallButtonWrapper orangeButton" style="float: right;">Clear Dates</span></div>

								<div>
									<div class="twoCol" style="max-width: 25em;">
										<div>
											<label for="startDate"><p>Start Date</p></label>
											<input class="defaultInput" style="width: 100%; max-width: 9em;" type="date" name="startDate" id="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
											<span id="startDateError" class="underInputError" style="display: none;"><br>Select a valid date.</span>
										</div>

										<div style="padding-left: 1em;">
											<label for="startTime"><p>Start Time (Optional)</p></label>
											<input class="defaultInput" style="width: 100%; max-width: 6em;" type="time" name="startTime" id="startTime" value="<?php echo htmlspecialchars($startTime); ?>">
											<span id="startTimeError" class="underInputError" style="display: none;"><br>Select a valid time.</span>
										</div>
									</div>
								</div>

								<br>

								<div>
									<div class="twoCol" style="max-width: 25em;">
										<div>
											<label for="endDate"><p>End Date</p></label>
											<input class="defaultInput" style="width: 100%; max-width: 9em;" type="date" name="endDate" id="endDate" value="<?php echo htmlspecialchars($endDate); ?>">
											<span id="endDateError" class="underInputError" style="display: none;"><br>Select a valid date.</span>
										</div>

										<div style="padding-left: 1em;">
											<label for="endTime"><p>End Time (Optional)</p></label>
											<input class="defaultInput" style="width: 100%; max-width: 6em;" type="time" name="endTime" id="endTime" value="<?php echo htmlspecialchars($endTime); ?>">
											<span id="endTimeError" class="underInputError" style="display: none;"><br>Select a valid time.</span>
										</div>
									</div>
								</div>
							
						</div>

						<br>

						<h3>Recurrence</h3>
						<div class="defaultInputGroup">
							<input class="defaultInput" type="checkbox" name="isRecurring" id="isRecurring" <?php if ($currentJob->frequencyInterval != 'none') {echo 'checked="checked"';} ?>><label for="isRecurring"> <p style="display: inline; clear: both;">Recurring</p></label>
							<br><br>

							<span>Every </span>
							<input class="defaultInput" style="width: 5em;" type="number" min="0" max="999999999999" name="frequency" id="frequency" placeholder="Est. Hours..." value="<?php echo htmlspecialchars($currentJob->frequency); ?>">

							<select class="defaultInput" name="frequencyInterval" id="frequencyInterval">
								<option value="day"<?php if ($currentJob->frequencyInterval == 'day') {echo ' selected="selected"';} ?>>day(s)</option>
								<option value="week"<?php if ($currentJob->frequencyInterval == 'week') {echo ' selected="selected"';} ?>>week(s)</option>
								<option value="month"<?php if ($currentJob->frequencyInterval == 'month') {echo ' selected="selected"';} ?>>month(s)</option>
								<option value="year"<?php if ($currentJob->frequencyInterval == 'year') {echo ' selected="selected"';} ?>>year(s)</option>
							</select>
							<span id="frequencyIntervalError" class="underInputError" style="display: none;"><br>Select an option from the menu.</span>
							<span id="frequencyError" class="underInputError" style="display: none;"><br>Enter a number.</span>
						</div>

						<?php

							// Tag Editor (need to add tag support to jobs)

							// if ($currentJob->existed) {
							// 	$tagEditor = new tagEditor("underNameTagEditor", [
							// 		'rootPathPrefix' => '../../../',
							// 		'type' => 'job',
							// 		'objectId' => $currentJob->jobId,
							// 		// 'style' => 'display: inline;',
							// 		'largeSize' => true
							// 	]);
							// 	$tagEditor->render();
							// 	echo $tagEditor->output;
							// }

						?>

						<br><br>

						<table class="defaultTable" style="width: 35em; max-width: 100%;">
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Staff</td>
								<td class="defaultTableCell" style="padding: 1em;" id="jobStaffLoader"><img style="width: 2em;" src="../../../images/ultiscape/etc/loading.gif"></td>
							</tr>
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Crews</td>
								<td class="defaultTableCell" style="padding: 1em;" id="jobCrewsLoader"><img style="width: 2em;" src="../../../images/ultiscape/etc/loading.gif"></td>
							</tr>
						</table>
						<br>

						<label for="privateNotes"><p>Notes (Private to Admins)</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="privateNotes" id="privateNotes"><?php echo htmlspecialchars($currentJob->privateNotes); ?></textarea>
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
							$addedDate = new DateTime($currentJob->dateTimeAdded);
						?>

						<p>Added on <?php echo $addedDate->format('D, d M y'); ?></p>
					</div>
				</div>
			</form>

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
