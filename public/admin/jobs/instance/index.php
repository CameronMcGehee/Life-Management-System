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
	require_once '../../../../lib/table/jobInstanceException.php';
	// require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentJob = new job($_GET['id']);
	} else {
		$currentJob = new job();
	}

	$currentBusiness = new business($_SESSION['ultiscape_businessId']);

	if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        header("location: ../");
		exit();
    }

	// Make sure that the instnace date is a real date
	require_once '../../../../lib/etc/time/validateDate.php';
	if (!validateDate($_GET['instance'], 'Y-m-d')) {
		header("location: ../");
		exit();
	}

	// Make sure there is an instance exception for this instance
	$currentJob->pullInstanceExceptions("AND startDateTime = '".$_GET['instance']."'");
	if (count($currentJob->instanceExceptions) !== 1) {
		header("location: ../");
		exit();
	}

	$currentInstance = new jobInstanceException($currentJob->instanceExceptions[0]);

	$titleName = $currentInstance->name;

	// Seperate the dateTime strings
	if ($currentInstance->existed) {
		$startDateArray = explode(' ', $currentInstance->startDateTime);
		if ($currentInstance->endDateTime == NULL) {
			$endDateArray = ['', ''];
		} else {
			$endDateArray = explode(' ', $currentInstance->endDateTime);
		}

		if ($startDateArray[1] == '00:00:00') {
			$startTime = '';
		} else {
			$startTime = $startDateArray[1];
		}

		$startDate = $startDateArray[0];

		if ($endDateArray[0] == '') {
			$endDate = '';
		} else {
			$endDate = $endDateArray[0];
		}

		if ($endDateArray[1] == '00:00:00') {
			$endTime = '';
		} else {
			$endTime = $endDateArray[1];
		}

	} else {
		$currentDate = new DateTime();
		$startDate = $currentDate->format('Y-m-d');
		$endDate = '';
		$startTime = '';
		$endTime = '';
	}

	echo $adminUIRender->renderAdminHtmlTop('../../../', htmlspecialchars($titleName), 'Edit '.htmlspecialchars($titleName).'.');
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editJobInstanceException';
	$mainAuthToken->set();

	$deleteJobInstanceAuthToken = new authToken();
	$deleteJobInstanceAuthToken->authName = 'deleteJobInstance';
	$deleteJobInstanceAuthToken->set();

	$cancelJobInstanceAuthToken = new authToken();
	$cancelJobInstanceAuthToken->authName = 'cancelJobInstance';
	$cancelJobInstanceAuthToken->set();

	$completeJobInstanceAuthToken = new authToken();
	$completeJobInstanceAuthToken->authName = 'completeJobInstance';
	$completeJobInstanceAuthToken->set();

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

		var lastFreq;
		var lastFreqInt;
		var lastStartDate;
		var lastWeekday;
		var lastMonthRecurrenceType;
		var lastIsRecurring;

		var redirectType;

		var scriptOutput;
		var jobId ='<?php echo $currentJob->jobId; ?>';
		var instanceId ='<?php echo $currentInstance->jobInstanceExceptionId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

		var currentInstanceDate = url.searchParams.get('instance');

		function getWeekNumber(thisDate) {
			var dt = new Date(thisDate);
			var thisDay = dt.getDate();

			var newDate = dt;
			newDate.setDate(1); // first day of month
			var digit = newDate.getDay();

			var Q = (thisDay + digit) / 7;

			var R = (thisDay + digit) % 7;

			if (R !== 0) return Math.ceil(Q);
			else return Q;
		}

		function setUnsaved() {

			$(".changesMessage").each(function () {
				$(this).html('<span style="color: red;">You have unsaved changes!</span>');
			});
			$(".changesMessage").each(function () {
				$(this).shake(50);
			});

			changesSaved = false;
		}

		function inputChange (e) {
			setUnsaved();
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

		function updateinstancePreviewNotice(freqInt, freq = null, weekday = null, weekNumber = null, dayOfMonth = null) {
			
			var recurrencePreview = '';
			
			// Determine the frequency
			if (freqInt == 'none') {
				recurrencePreview = "Occurs once.";
			} else {

				var url = new URL(window.location.href);

				if (url.searchParams.get('instance') != null) {
					if (Date.parse(url.searchParams.get('instance'))) {
						var currentInstanceDate = url.searchParams.get('instance');
					} else {
						if (Date.parse($("#startDate").val())) {
							var currentInstanceDate = $("#startDate").val();
						} else {
							var currentInstanceDate = '<?php echo $startDate ?>';
						}
					}
				} else {
					if (Date.parse($("#startDate").val())) {
						var currentInstanceDate = $("#startDate").val();
					} else {
						var currentInstanceDate = '<?php echo $startDate ?>';
					}
				}

				switch (freqInt) {
					case 'day':
						recurrencePreview = "every " + freq + " day(s)";
						break;
					case 'week':
						switch (weekday) {
							case 0:
								var weekDayName = 'Sunday';
								break;
							case 1:
								var weekDayName = 'Monday';
								break;
							case 2:
								var weekDayName = 'Tuesday';
								break;
							case 3:
								var weekDayName = 'Wednesday';
								break;
							case 4:
								var weekDayName = 'Thursday';
								break;
							case 5:
								var weekDayName = 'Friday';
								break;
							case 6:
								var weekDayName = 'Saturday';
								break;
							default:
								var weekDayName = 'Error';
								break;
						}

						recurrencePreview = "every " + freq + " week(s) on " + weekDayName;
						break;
					case 'month':
						
						// If the weekday is set then it is using the week of month-weekday of week format
						if (weekday !== null) {

							switch (weekday) {
								case 0:
									var weekDayName = 'Sunday';
									break;
								case 1:
									var weekDayName = 'Monday';
									break;
								case 2:
									var weekDayName = 'Tuesday';
									break;
								case 3:
									var weekDayName = 'Wednesday';
									break;
								case 4:
									var weekDayName = 'Thursday';
									break;
								case 5:
									var weekDayName = 'Friday';
									break;
								case 6:
									var weekDayName = 'Saturday';
									break;
								default:
									var weekDayName = 'Error';
									break;
							}
							recurrencePreview = "every " + freq + " month(s) on the " + weekDayName + " of week " + weekNumber;
						} else { // Otherwise, just use the month day provided
							recurrencePreview = "every " + freq + " month(s) on day " + dayOfMonth;
						}

						break;
					case 'year':
						recurrencePreview = "every " + freq + " year(s)";
						break;
					default:
						recurrencePreview = "Error...";
						break;
				}
			}

			$("#instancePreviewNotice").html("This is a single edited occurrence of the recurring job \"<b><?php echo htmlspecialchars($currentJob->name) ?></b>\", which recurs <b>" + recurrencePreview + "</b>. You cannot change the recurrence of an edited instance of a recurring job. ");
		}

		// Per hour calculator
		function updatePerHourCalc() {
			var price = parseFloat($("#price").val());
			var estHours = parseFloat($("#estHours").val());

			if (isNaN(price / estHours)) {
				$("#perHourCalc").html("<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>--/hour");
			} else {
				$("#perHourCalc").html("<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>" + (price / estHours).toFixed(2) + "/hour");
			}
		}

		// DELETE BUTTON FUNCTIONS
		function deleteButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				$('#jobForm').submit(function () {
					$("#deletePrompt").fadeIn(300);
				});
			} else {
				$("#deletePrompt").fadeIn(300);
			}
		}
		function deleteYes() {
			// Delete run the script
			$("#deleteLoading").fadeIn(300);
			$("#scriptLoader").load("./scripts/async/deleteJobInstance.script.php", {
				jobId: jobId,
				deleteJobInstanceAuthToken: '<?php echo $deleteJobInstanceAuthToken->authTokenId; ?>'
			}, function () {
				if ($("#scriptLoader").html() == 'success') {
					window.location.href = '../?popup=jobDeleted';
				} else {
					$("#deleteLoading").fadeOut(300);
					$("#deletePrompt").fadeOut(300);
				}
			});
		}
		function deleteNo() {
			// Just hide the prompt
			$("#deletePrompt").fadeOut(300);
		}

		//COMPLETE BUTTON FUNCTIONS
		function makeCompleted() {
			// Load the script to convert it into a completed job and redirect if successful
			$("#completeLoading").fadeIn(300);
			$("#completeButtonText").hide(300);
			
			$("#scriptLoader").load("./scripts/async/completeJobInstance.script.php", {
				instanceId: instanceId,
				instanceDate: currentInstanceDate,
				completeJobInstanceAuthToken: '<?php echo $completeJobInstanceAuthToken->authTokenId; ?>'
			}, function () {
				scriptOutput = $("#scriptLoader").html().split(":::");
				if (scriptOutput[1] == 'success') {
					window.location.href = '../completedjob?id=' + scriptOutput[0];
				} else {
					$("#completeLoading").fadeOut(300);
					$("#completeButtonText").show(300);
				}
			});
		}
		function completeButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				$('#jobForm').submit(function () {
					makeCompleted();
				});
			} else {
				makeCompleted();
			}
		}

		//CANCEL BUTTON FUNCTIONS
		function makeCancelled() {
			// Load the script to convert it into a completed job and redirect if successful
			$("#completeLoading").fadeIn(300);
			$("#completeButtonText").hide(300);
			
			$("#scriptLoader").load("./scripts/async/cancelJobInstance.script.php", {
				instanceId: instanceId,
				instanceDate: currentInstanceDate,
				cancelJobInstanceAuthToken: '<?php echo $cancelJobInstanceAuthToken->authTokenId; ?>'
			}, function () {
				scriptOutput = $("#scriptLoader").html().split(":::");
				if (scriptOutput[1] == 'success') {
					window.location.reload();
				} else {
					$("#completeLoading").fadeOut(300);
					$("#completeButtonText").show(300);
				}
			});
		}
		function cancelButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				$('#jobForm').submit(function () {
					makeCancelled();
				});
			} else {
				makeCancelled();
			}
		}

		function sendChanges(formData) {
			$("#scriptLoader").load("./scripts/async/editJobInstance.script.php", {
				instanceId: instanceId,
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

						// property selector
						if ($("#customerSelector").val() != 'none') {
							$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
								instanceId: instanceId,
								customerId: $("#customerSelector").val()
							}, function () {
								$(":input").change(function (e) {
									inputChange(e);
								});
							});
						} else {
							$("#propertySelectorLoader").html('');
						}

						setSaved();
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

		// ON LOAD
		$(function() {

			$("#jobForm :input").change(function () {
				inputChange();
			});

			if ($.isNumeric(url.searchParams.get('wsl'))) {
				$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			}

			$("#jobForm").submit(function(event) {
				event.preventDefault();
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});

				if (!changesSaved) {
					formData = $("#jobForm").serialize();

					sendChanges(formData);
				}

				$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				
			});

			<?php

				require_once '../../../../lib/etc/time/getWeekNumbers.php';

				if ($currentJob->frequencyInterval == NULL) {
					$frequencyIntervalOutput = 'null';
				} else {
					$frequencyIntervalOutput = "'".$currentJob->frequencyInterval."'";
				}

				if ($currentJob->frequency == NULL) {
					$frequencyOutput = 'null';
				} else {
					$frequencyOutput = "".$currentJob->frequency."";
				}

				if (strrpos($currentJob->weekday, '-')) {
					$weekdayOutput = explode('-', $currentJob->weekday)[1];
					$weekNumberOutput = explode('-', $currentJob->weekday)[0];
					$dayOfMonthOutput = 'null';
				} else {
					if ($currentJob->weekday == NULL) {
						$weekdayOutput = 'null';
					} else {
						$weekdayOutput = $currentJob->weekday;
					}
					$weekNumberOutput = 'null';

					if ($currentJob->frequencyInterval == 'month') {
						$dayOfMonthOutput = new DateTime($currentJob->startDateTime);
						$dayOfMonthOutput = $dayOfMonthOutput->format('d');
					} else {
						$dayOfMonthOutput = 'null';
					}
				}
				
				

			?>

			updateinstancePreviewNotice(<?php echo $frequencyIntervalOutput ?>, <?php echo $frequencyOutput; ?>, <?php echo $weekdayOutput; ?>, <?php echo $weekNumberOutput; ?>, <?php echo $dayOfMonthOutput; ?>);

			// Load the property selector on startup
			if ($("#customerSelector").val() != 'none') {
				$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
					instanceId: instanceId,
					customerId: $("#customerSelector").val()
				}, function () {
					$(":input").change(function (e) {
						inputChange(e);
					});
				});
			} else {
				$("#propertySelectorLoader").html('');
			}

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
				<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit" onclick="$('#jobForm').submit()">Save Changes</button>
				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
				<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
			</div>
			<form class="defaultForm maxHeight" id="jobForm">

				<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">

				<input type="hidden" name="deleteJob$deleteJobInstanceAuthToken" id="deleteJobInstanceAuthToken" value="<?php echo htmlspecialchars($deleteJobInstanceAuthToken->authTokenId); ?>">
				<input type="hidden" name="cancelJobInstanceAuthToken" id="cancelJobInstanceAuthToken" value="<?php echo htmlspecialchars($cancelJobInstanceAuthToken->authTokenId); ?>">
				<input type="hidden" name="completeJobInstanceAuthToken" id="completeJobInstanceAuthToken" value="<?php echo htmlspecialchars($completeJobInstanceAuthToken->authTokenId); ?>">

				<input type="hidden" name="instanceDate" id="instanceDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>

						<?php
						
								echo '<div class="threeCol" style="width: 25em;">';
									echo '<span class="smallButtonWrapper greenButton centered defaultMainShadows" onclick="completeButton()"><span id="completeButtonText">✔ Complete</span><span style="display: none;" id="completeLoading"><img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif"></span></span>';
									echo '<span class="smallButtonWrapper orangeButton centered defaultMainShadows" onclick="cancelButton()">❌Cancel</span>';
									echo '<span class="smallButtonWrapper redButton centered defaultMainShadows" onclick="deleteButton()"><img style="height: 1.2em;" src="../../../images/ultiscape/icons/trash.svg"> Delete</span>';
								echo '</div>';

								echo '<br>';

						
						?>
						
						<h3>Job Info</h3>
						<div class="defaultInputGroup">
							<label for="name"><p>Name</p></label>
							<input class="bigInput" style="width: 93%;" type="text" name="name" id="name" placeholder="Name..." value="<?php echo htmlspecialchars($currentInstance->name); ?>">
							<span id="nameError" class="underInputError" style="display: none;"><br>Enter a name for the job.</span>

							<br><br>

							<div class="twoCol">
								<div>
									<label for="customerSelector"><p>Customer</p></label>
									<!-- Select customer dialog -->
									<?php
									
										require_once '../../../../lib/render/input/customerSelector.php';
										$customerSelector = new customerSelector("customerSelector", ["name" => 'customer', "selectedId" => $currentInstance->linkedToCustomerId]);
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
							<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="description" id="description"><?php echo htmlspecialchars($currentInstance->description); ?></textarea>
							<span id="descriptionError" class="underInputError" style="display: none;"><br>Enter a valid description.</span>

							<br><br>

							<div class="threeCol">
								<div>
									<label for="name"><p>Price (<?php echo htmlspecialchars($currentBusiness->currencySymbol); ?>)</p></label>
									<input onchange="updatePerHourCalc()" class="defaultInput" style="width: 5em;" type="number" min="0.00" max="999999999999" step="0.01" name="price" id="price" placeholder="Free" value="<?php echo htmlspecialchars($currentInstance->price); ?>">
									<span id="priceError" class="underInputError" style="display: none;"><br>Enter a number.</span>
								</div>

								<div>
									<label for="name"><p>Estimated Hours</p></label>
									<input onchange="updatePerHourCalc()" class="defaultInput" style="width: 5em;" type="number" min="0" max="999999999999" step =".01" name="estHours" id="estHours" placeholder="Est. Hours..." value="<?php echo htmlspecialchars($currentInstance->estHours); ?>">
									<span id="estHoursError" class="underInputError" style="display: none;"><br>Enter a number.</span>

									<p id="perHourCalc" style="color: gray;">$--/hour</p>
								</div>

								<div>
								<input class="defaultInput" type="checkbox" name="isPrepaid" id="isPrepaid" <?php if ($currentInstance->isPrepaid == '1') {echo 'checked="checked"';} ?>><label for="isPrepaid"> <p style="display: inline; clear: both;">Customer has already paid for this service</p></label>
								</div>
							</div>

							<br><hr style="border-color: var(--utliscapeColorTheme);" class="defaultMainShadows"><br>

							<div><span id="clearEndDateTime" class="smallButtonWrapper orangeButton" style="float: right;" onclick="clearDates()">Clear Dates</span></div>

								<div>
									<div class="twoCol" style="max-width: 25em;">
										<div>
											<label for="startDate"><p>Start Date</p></label>
											<input onchange="updateRecurringInputs()" class="defaultInput" style="width: 100%; max-width: 9em;" type="date" name="startDate" id="startDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">
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

							<script>
								function clearDates() {
									// $("#startDate").val("");
									$("#startTime").val("");
									$("#endDate").val("");
									$("#endTime").val("");
									setUnsaved();
								}

								function changeStartDate() {
									if ($("#endDate").val() == '') {
										$("#endDate").val() == $("#startDate").val();
									}
								}
							</script>
							
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

						<!-- <br><br> -->

						<table class="defaultTable" style="display: none; width: 35em; max-width: 100%;">
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Staff</td>
								<td class="defaultTableCell" style="padding: 1em;" id="jobStaffLoader"><img style="width: 2em;" src="../../../images/ultiscape/etc/loading.gif"></td>
							</tr>
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Crews</td>
								<td class="defaultTableCell" style="padding: 1em;" id="jobCrewsLoader"><img style="width: 2em;" src="../../../images/ultiscape/etc/loading.gif"></td>
							</tr>
						</table>
						<!-- <br> -->
						
						<br>
						
						<div class="defaultInputGroup" id="recurringSettingsBox">

						<h3>Recurrence ⚠</h3>

							<div>
								<p id="instancePreviewNotice"></p>
							</div>
						
						</div>

						<br>

						<label for="privateNotes"><p>Notes (Private to Admins)</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="privateNotes" id="privateNotes"><?php echo htmlspecialchars($currentInstance->privateNotes); ?></textarea>
						<br><br>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br>
						<span class="desktopOnlyBlock">
							<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit">Save Changes</button>
							<br><br>
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
						</span>

						<br><hr><br>

						<h3>Other Info</h3>

						<?php
							$jobAdded = new DateTime($currentJob->dateTimeAdded);
						?>

						<p>Recurring Job created on <?php echo $jobAdded->format('D, M d Y'); ?></p>
					</div>
				</div>

				<div id="deletePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog">
						<h3>Delete Job?</h3>
						<p>This will delete ALL occurrences of this job!</p>
						<br>

						<div id="deleteButtons" class="twoCol centered" style="width: 10em;">
							<div>
								<span id="deleteYesButton" class="smallButtonWrapper greenButton" onclick="deleteYes()">Yes</span>
							</div>

							<div>
								<span id="deleteNoButton" class="smallButtonWrapper redButton" onclick="deleteNo()">No</span>
							</div>
						</div>

						<span style="display: none;" id="deleteLoading"><img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif"></span>
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
