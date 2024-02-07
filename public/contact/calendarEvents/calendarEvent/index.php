<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../../lib/etc/contactHeaderRedirect.php';
	contactHeaderRedirect('../../', '../../');

	require_once '../../../../lib/contactUIRender.php';
	$contactUIRender = new contactUIRender();

	// Other required libraries
	require_once '../../../../lib/table/workspace.php';
	require_once '../../../../lib/table/calendarEvent.php';
	// require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentCalendarEvent = new calendarEvent($_GET['id']);
	} else {
		$currentCalendarEvent = new calendarEvent();
	}

	$currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);

	if ($currentCalendarEvent->existed) {
		$titleName = $currentCalendarEvent->name;
	} else {
		header("location: ../");
		exit();
	}

	if ($currentCalendarEvent->workspaceId != $_SESSION['lifems_workspaceId']) {
        header("location: ../");
		exit();
    }

	// Seperate the dateTime strings
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

	//Verify that the instanceDate given is a real date, and fits in the pattern of the calendarEvent if it existed already
	require_once '../../../../lib/etc/time/getRecurringDates.php';
	if ($currentCalendarEvent->existed && !isset($_GET['instance'])) {
		header("location: ../");
		exit();
	}
	if ($currentCalendarEvent->frequencyInterval != 'none') {
		$calendarEventInstancesCheck = getRecurringDates($currentCalendarEvent->startDateTime, $currentCalendarEvent->endDateTime, $currentCalendarEvent->startDateTime, $_GET['instance'], $currentCalendarEvent->frequencyInterval, $currentCalendarEvent->frequency, $currentCalendarEvent->weekday);
		if (!in_array($_GET['instance'], $calendarEventInstancesCheck)) {
			header("location: ../");
			exit();
		}
	} else {
		if ($currentCalendarEvent->existed && $_GET['instance'] != $startDate) {
			header("location: ../");
			exit();
		}
	}

	// Make sure there is not an instance exception for this instance
	$currentCalendarEvent->pullInstanceExceptions();
	if (($currentCalendarEvent->instanceExceptions) > 0) {
		require_once '../../../../lib/table/calendarEventInstanceException.php';
		foreach ($currentCalendarEvent->instanceExceptions as $instanceExceptionId) {
			$currentInstanceException = new calendarEventInstanceException($instanceExceptionId);
			if ($currentInstanceException->instanceDate == $_GET['instance']) {
				header("location: ../");
				exit();
			}
		}
	}

	echo $contactUIRender->renderContactHtmlTop('../../../', htmlspecialchars($titleName), 'Edit '.htmlspecialchars($titleName).'.');
	echo $contactUIRender->renderContactUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

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
		var calendarEventId ='<?php echo $currentCalendarEvent->calendarEventId; ?>';
		var formState;
		var checkStaff;
		var checkCrews;
		var url = new URL(window.location.href);

		var isNewCalendarEvent = <?php if ($currentCalendarEvent->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

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

		function updateRecurrencePreview(freqInt, freq = null, weekday = null, weekNumber = null, dayOfMonth = null) {
			// If freqInt is null, say 'not recurring'
			if (freqInt == 'none') {
				$("#recurrencePreview").html("Occurs once.");
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
						$("#recurrencePreview").html("Recurs every " + freq + " day(s).");
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

						$("#recurrencePreview").html("Recurs every " + freq + " week(s) on " + weekDayName + ".");
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
							$("#recurrencePreview").html("Recurs every " + freq + " month(s) on the " + weekDayName + " of week " + weekNumber);
						} else { // Otherwise, just use the month day provided
							$("#recurrencePreview").html("Recurs every " + freq + " month(s) on day " + dayOfMonth + ".");
						}

						break;
					case 'year':
						$("#recurrencePreview").html("Recurs every " + freq + " year(s).");
						break;
					default:
						$("#recurrencePreview").html("Error...");
						break;
				}
			}
		}

		// ON LOAD
		$(function() {

			<?php

				require_once '../../../../lib/etc/time/getWeekNumbers.php';

				if ($currentCalendarEvent->frequencyInterval == NULL) {
					$frequencyIntervalOutput = 'null';
				} else {
					$frequencyIntervalOutput = "'".$currentCalendarEvent->frequencyInterval."'";
				}

				if ($currentCalendarEvent->frequency == NULL) {
					$frequencyOutput = 'null';
				} else {
					$frequencyOutput = "".$currentCalendarEvent->frequency."";
				}

				if (strrpos($currentCalendarEvent->weekday, '-')) {
					$weekdayOutput = explode('-', $currentCalendarEvent->weekday)[1];
					$weekNumberOutput = explode('-', $currentCalendarEvent->weekday)[0];
					$dayOfMonthOutput = 'null';
				} else {
					if ($currentCalendarEvent->weekday == NULL) {
						$weekdayOutput = 'null';
					} else {
						$weekdayOutput = $currentCalendarEvent->weekday;
					}
					$weekNumberOutput = 'null';

					if ($currentCalendarEvent->frequencyInterval == 'month') {
						$dayOfMonthOutput = new DateTime($currentCalendarEvent->startDateTime);
						$dayOfMonthOutput = $dayOfMonthOutput->format('d');
					} else {
						$dayOfMonthOutput = 'null';
					}
				}
				
				

			?>

			updateRecurrencePreview(<?php echo $frequencyIntervalOutput ?>, <?php echo $frequencyOutput; ?>, <?php echo $weekdayOutput; ?>, <?php echo $weekNumberOutput; ?>, <?php echo $dayOfMonthOutput; ?>);

		});
	</script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
	<div class="contactBodyWrapper">

		<?php
			echo $contactUIRender->renderContactTopBar('../../../', true, true, true);
		?>

		<?php
            echo $contactUIRender->renderContactSideBar('../../../');
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white;">
			<button class="mediumButtonWrapper greenButton centered defaultMainShadows" type="submit" onclick="$('#calendarEventForm').submit()">Save Changes</button>
				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
				<img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif">
			</div>
			<form class="defaultForm maxHeight" id="calendarEventForm">

				<input type="hidden" name="instanceDate" id="instanceDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>
						
						<h3>Scheduled Service</h3>
						<div class="defaultInputGroup">
							<label for="name"><p>Name</p></label>
							<h3 id="name"><?php echo htmlspecialchars($currentCalendarEvent->name); ?></h3>

							<br>

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
										// if (!($currentCalendarEvent->estHours == NULL || $currentCalendarEvent->estHours <= 0)) {
										// 	echo '<label for="name"><p>Estimated Hours</p></label><p id="estHours" style="font-size: 1.2em;"><b>';
										// 	echo htmlspecialchars($currentCalendarEvent->estHours);
										// 	echo '</b></p>';
										// 	if ($currentCalendarEvent->price != NULL || $currentCalendarEvent->price > 0) {
										// 		echo '<p id="perHourCalc" style="color: gray;">$'.$currentCalendarEvent->price / $currentCalendarEvent->estHours.'/hour</p>';
										// 	}
											
										// }
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

						<h3>Recurrence</h3>

							<div>
								<p id="recurrencePreview"></p>
							</div>
						
						</div>

						<br>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">

						<br>
						
						<h3>Other Info</h3>

						<?php
							$addedDate = new DateTime($currentCalendarEvent->dateTimeAdded);
						?>

						<p>Added on <?php echo $addedDate->format('D, M d Y'); ?></p>
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
			echo $contactUIRender->renderContactFooter('../../../');
		?>

		<?php 
			echo $contactUIRender->renderContactMobileNavBar('../../../');
		?>

	</div>

	<?php
		echo $contactUIRender->renderContactTopBarDropdowns('../../../');
	?>
</body>
<?php 
	echo $contactUIRender->renderContactHtmlBottom('../../../');
?>
