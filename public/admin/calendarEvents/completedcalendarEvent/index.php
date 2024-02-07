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
	require_once '../../../../lib/table/workspace.php';
	require_once '../../../../lib/table/calendarEvent.php';
	require_once '../../../../lib/table/completedCalendarEvent.php';
	// require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentCalendarEvent = new completedCalendarEvent($_GET['id']);

		if (!$currentCalendarEvent->existed || $currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
			header("location: ../");
			exit();
		}
	} else {
		header("location: ../");
		exit();
	}

	$currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);

	if ($currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        header("location: ../");
		exit();
    }


	$titleName = $currentCalendarEvent->name;

	// Seperate the dateTime strings

	$instanceDate = new DateTime($currentCalendarEvent->instanceDate);
	$instanceDate = $instanceDate->format('Y-m-d');
	if ($currentCalendarEvent->existed) {
		$startDateArray = explode(' ', $currentCalendarEvent->startDateTime);
		if ($currentCalendarEvent->endDateTime == NULL) {
			$endDateArray = ['', ''];
		} else {
			$endDateArray = explode(' ', $currentCalendarEvent->endDateTime);
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

	echo $adminUIRender->renderAdminHtmlTop('../../../', [
		"pageTitle" => htmlspecialchars($titleName),
		"pageDescription" => 'Edit '.htmlspecialchars($titleName).'.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	// $mainAuthToken = new authToken();
	// $mainAuthToken->authName = 'editCompletedCalendarEvent';
	// $mainAuthToken->set();

	$uncompleteCompletedCalendarEventAuthToken = new authToken();
	$uncompleteCompletedCalendarEventAuthToken->authName = 'uncompleteCompletedCalendarEvent';
	$uncompleteCompletedCalendarEventAuthToken->set();

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
		var completedCalendarEventId ='<?php echo $currentCalendarEvent->completedCalendarEventId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

		var currentCalendarEventDate = url.searchParams.get('instance');

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

		function updatePreviewNotice(freqInt, freq = null, weekday = null, weekNumber = null, dayOfMonth = null) {
			
			var recurrencePreview = '';
			
			// Determine the frequency
			if (freqInt == 'none') {
				recurrencePreview = "Occurs once.";
			} else {

				var url = new URL(window.location.href);

				if (url.searchParams.get('instance') != null) {
					if (Date.parse(url.searchParams.get('instance'))) {
						var currentCalendarEventDate = url.searchParams.get('instance');
					} else {
						if (Date.parse($("#startDate").val())) {
							var currentCalendarEventDate = $("#startDate").val();
						} else {
							var currentCalendarEventDate = '<?php echo $startDate ?>';
						}
					}
				} else {
					if (Date.parse($("#startDate").val())) {
						var currentCalendarEventDate = $("#startDate").val();
					} else {
						var currentCalendarEventDate = '<?php echo $startDate ?>';
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

			$("#instancePreviewNotice").html("This is a completed occurrence of the recurring calendarEvent \"<b><?php echo htmlspecialchars($currentCalendarEvent->name) ?></b>\", which recurs <b>" + recurrencePreview + "</b>. You cannot change the recurrence of an edited instance of a recurring calendarEvent. ");
		}

		//UNCOMPLETE BUTTON FUNCTIONS
		function makeUncomplete() {
			// Load the script to convert it into a completed calendarEvent and redirect if successful
			$("#completeLoading").fadeIn(300);
			$("#uncompleteButtonText").hide(300);
			
			$("#scriptLoader").load("./scripts/async/uncompleteCompletedCalendarEvent.script.php", {
				completedCalendarEventId: completedCalendarEventId,
				uncompleteCompletedCalendarEventAuthToken: '<?php echo $uncompleteCompletedCalendarEventAuthToken->authTokenId; ?>'
			}, function () {
				scriptOutput = $("#scriptLoader").html().split(":::");
				if (scriptOutput[1] == 'calendarEvent') {
					window.location.href = '../calendarEvent?id=' + scriptOutput[0] + '&instance=<?php echo htmlspecialchars($instanceDate) ?>';
				} else if (scriptOutput[1] == 'instance') {
					window.location.href = '../instance?id=' + scriptOutput[0] + '&instance=<?php echo htmlspecialchars($instanceDate) ?>';
				} else {
					$("#completeLoading").fadeOut(300);
					$("#uncompleteButtonText").show(300);
				}
			});
		}
		function uncompleteButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				$('#calendarEventForm').submit(function () {
					makeUncomplete();
				});
			} else {
				makeUncomplete();
			}
		}

		// function sendChanges(formData) {
		// 	$("#scriptLoader").load("./scripts/async/editCalendarEventInstance.script.php", {
		// 		instanceId: instanceId,
		// 		formData: formData
		// 	}, function () {
		// 		scriptOutput = $("#scriptLoader").html().split(":::");
		// 		completedCalendarEventId = scriptOutput[0];
		// 		formState = scriptOutput[1];
		// 		clearFormErrors();

		// 		checkStaff = false;
		// 		checkCrews = false;

		// 		switch (formState) {
		// 			case 'success':

		// 				// property selector
		// 				if ($("#contactSelector").val() != 'none') {
		// 					$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
		// 						instanceId: instanceId,
		// 						contactId: $("#contactSelector").val()
		// 					}, function () {
		// 						$(":input").change(function (e) {
		// 							inputChange(e);
		// 						});
		// 					});
		// 				} else {
		// 					$("#propertySelectorLoader").html('');
		// 				}

		// 				setSaved();
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

		// ON LOAD
		$(function() {

			// $("#calendarEventForm :input").change(function () {
			// 	inputChange();
			// });

			// if ($.isNumeric(url.searchParams.get('wsl'))) {
			// 	$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			// }

			// $("#calendarEventForm").submit(function(event) {
			// 	event.preventDefault();
			// 	$('.loadingGif').each(function() {
			// 		$(this).fadeIn(100);
			// 	});

			// 	if (!changesSaved) {
			// 		formData = $("#calendarEventForm").serialize();

			// 		sendChanges(formData);
			// 	}

			// 	$('.loadingGif').each(function() {
			// 			$(this).fadeOut(100);
			// 		});
				
			// });

			

			// window.onbeforeunload = function() {
			// 	if (changesSaved == false || waitingForError == true) {
			// 		return "Changes have not been saved yet. Are you sure you would like to leave?";
			// 	} else {
			// 		return;
			// 	}
			// };

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
			<form class="defaultForm maxHeight" id="calendarEventForm">

				<!-- <input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php // echo htmlspecialchars($mainAuthToken->authTokenId); ?>"> -->

				<input type="hidden" name="uncompleteCompletedCalendarEventAuthToken" id="uncompleteCompletedCalendarEventAuthToken" value="<?php echo htmlspecialchars($uncompleteCompletedCalendarEventAuthToken->authTokenId); ?>">

				<input type="hidden" name="instanceDate" id="instanceDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>

						<?php
						
								echo '<div class="threeCol" style="width: 30em;">';
									echo '<span class="smallButtonWrapper orangeButton centered defaultMainShadows" onclick="uncompleteButton()"><span id="uncompleteButtonText"><strike>✔</strike> Set Incomplete</span><span style="display: none;" id="completeLoading"><img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span></span>';
								echo '</div>';

								echo '<br>';

						
						?>
						
						<h3>CalendarEvent Info</h3>
						<div class="defaultInputGroup">
							<label for="name"><p>Name</p></label>
							<h3 id="name"><?php echo htmlspecialchars($currentCalendarEvent->name); ?></h3>

							<br>

							<div class="twoCol">
								<div>
									<label for="contact"><p>Contact</p></label>
									<p id="contact"><b>
										<?php
											$useArchviedContact = false;
											require_once '../../../../lib/table/contact.php';
											if (!empty($currentCalendarEvent->linkedToContactId)) {
												$currentContact = new contact($currentCalendarEvent->linkedToContactId);
												if ($currentContact->existed) {
													echo '<a href="../../contacts/contact/?id='.htmlspecialchars($currentContact->contactId).'">';
													if (!empty($currentContact->firstName)) {
														echo htmlspecialchars($currentContact->firstName);
		
														if (!empty($currentContact->lastName)) {
															echo " ".htmlspecialchars($currentContact->lastName);
														}
													} else {
														echo 'View Contact';
													}
													echo '</a>';
												} else {
													$useArchviedContact = true;
												}
											}

											if ($useArchviedContact) {
												if (!empty($currentCalendarEvent->contactFirstName)) {
													echo htmlspecialchars($currentCalendarEvent->contactFirstName);
	
													if (!empty($currentCalendarEvent->contactLastName)) {
														echo " ".htmlspecialchars($currentCalendarEvent->contactLastName);
													}
												} else {
													echo 'Not assigned';
												}
											}
										
										?></b></p>

								</div>

								<div>
									<label for="property"><p>Property</p></label>
									<p id="property"><b>
										<?php
											$useArchviedProperty = false;
											require_once '../../../../lib/table/property.php';
											if (!empty($currentCalendarEvent->linkedToPropertyId)) {
												$currentProperty = new property($currentCalendarEvent->linkedToPropertyId);
												if ($currentProperty->existed) {
													echo '<a href="../../properties/property/?id='.htmlspecialchars($currentProperty->propertyId).'">';
													echo htmlspecialchars($currentProperty->address1.' - '.$currentProperty->city);
													echo '</a>';
												} else {
													$useArchviedProperty = true;
												}
											}

											if ($useArchviedProperty) {
												if (!empty($currentCalendarEvent->propertyAddress1)) {
													echo htmlspecialchars($currentCalendarEvent->propertyAddress1.' - '.$currentCalendarEvent->propertyCity);
												} else {
													echo 'Not assigned';
												}
											}
										
										?></b></p>
								</div>
							</div>

							<span style="display: <?php if (!empty($currentCalendarEvent->description)) { echo 'block'; } else { echo 'none'; }; ?>;">
								<br>
								<label for="description"><p>Description (Visible to Contact)</p></label>
								<div style="font-size: 1.2em; width: 80%; height: 3em; border: 1px solid gray; border-radius: .3em; padding: .5em; overflow: scroll; resize: vertical;" id="description"><?php echo nl2br(htmlspecialchars($currentCalendarEvent->description)); ?></div>
							</span>

							<br>

							<div class="threeCol">
								<div>
									<label for="name"><p>Price</p></label>
									<p id="price" style="color:green; font-size: 1.2em;"><b>
										<?php 
											if ($currentCalendarEvent->price == NULL || $currentCalendarEvent->price <= 0) {
												echo '<span>Free</span>';
											} else {
												echo htmlspecialchars($currentWorkspace->currencySymbol); echo htmlspecialchars($currentCalendarEvent->price);
											}
										?>
									</b></p>
								</div>

								<div>
									<?php 
										if (!($currentCalendarEvent->estHours == NULL || $currentCalendarEvent->estHours <= 0)) {
											echo '<label for="name"><p>Estimated Hours</p></label><p id="estHours" style="font-size: 1.2em;"><b>';
											echo htmlspecialchars($currentCalendarEvent->estHours);
											echo '</b></p>';
											if ($currentCalendarEvent->price != NULL || $currentCalendarEvent->price > 0) {
												echo '<p id="perHourCalc" style="color: gray;">$'.$currentCalendarEvent->price / $currentCalendarEvent->estHours.'/hour</p>';
											}
											
										}
									?>
								</div>

								<div>
									<?php 
										if ((bool)$currentCalendarEvent->isPrepaid) {
											echo '<p id="isPrepaid" style="font-size: 1.2em;"><b>Was</b> prepaid.</p>';
										} else {
											echo '<p id="isPrepaid" style="font-size: 1.2em;"><b>Was not</b> prepaid.</p>';
										}
									?>
								</div>
							</div>

							<br><hr style="border-color: var(--utliscapeColorTheme);" class="defaultMainShadows"><br>

								<div>
									<label for="startDate"><p>Date</p></label>
									<?php

										if (!empty($currentCalendarEvent->startDateTime)) {
											$startDateTimeOutput = new DateTime($currentCalendarEvent->startDateTime);
											$startDateTimeOutput = $startDateTimeOutput->format('D, M d Y \a\t h:i');
										} else {
											$startDateTimeOutput = NULL;
										}

										if (!empty($currentCalendarEvent->startDateTime)) {
											$endDateTimeOutput = new DateTime($currentCalendarEvent->endDateTime);
											$endDateTimeOutput = $endDateTimeOutput->format('D, M d Y \a\t h:i');
										} else {
											$endDateTimeOutput = NULL;
										}


										if (empty($currentCalendarEvent->endDateTime)) {
											echo '<p style="font-size: 1.2em;"><b>'.$startDateTimeOutput.'</b></p>';
										} else {
											echo '<p style="font-size: 1.2em;">Started on <b>'.$startDateTimeOutput.'</b> and ends/ended on <b>'.$endDateTimeOutput.'</b></p>';
										}


									?>
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

						<!-- <br><br> -->

						<table class="defaultTable" style="display: none; width: 35em; max-width: 100%;">
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Staff</td>
								<td class="defaultTableCell" style="padding: 1em;" id="calendarEventStaffLoader"><img style="width: 2em;" src="../../../images/lifems/etc/loading.gif"></td>
							</tr>
							<tr>
								<td class="defaultTableCell" style="padding: 1em; width: 5em;">Assigned Crews</td>
								<td class="defaultTableCell" style="padding: 1em;" id="calendarEventCrewsLoader"><img style="width: 2em;" src="../../../images/lifems/etc/loading.gif"></td>
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
						<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="privateNotes" id="privateNotes"><?php echo htmlspecialchars($currentCalendarEvent->privateNotes); ?></textarea>
						<br><br>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br>

						<h3>Other Info</h3>

						<?php
							$calendarEventAdded = new DateTime($currentCalendarEvent->dateTimeAdded);
						?>

						<p>Marked completed on <?php echo $calendarEventAdded->format('D, M d Y'); ?></p>
					</div>
				</div>

				<div id="deletePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog">
						<h3>Delete CalendarEvent?</h3>
						<p>This will delete ALL occurrences of this calendarEvent!</p>
						<br>

						<div id="deleteButtons" class="twoCol centered" style="width: 10em;">
							<div>
								<span id="deleteYesButton" class="smallButtonWrapper greenButton" onclick="deleteYes()">Yes</span>
							</div>

							<div>
								<span id="deleteNoButton" class="smallButtonWrapper redButton" onclick="deleteNo()">No</span>
							</div>
						</div>

						<span style="display: none;" id="deleteLoading"><img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span>
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
