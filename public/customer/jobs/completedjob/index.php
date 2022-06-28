<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../../lib/etc/customerHeaderRedirect.php';
	customerHeaderRedirect('../../', '../../');

	require_once '../../../../lib/customerUIRender.php';
	$customerUIRender = new customerUIRender();

	// Other required libraries
	require_once '../../../../lib/table/business.php';
	require_once '../../../../lib/table/job.php';
	require_once '../../../../lib/table/completedJob.php';
	// require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentJob = new completedJob($_GET['id']);

		if (!$currentJob->existed || $currentJob->businessId != $_SESSION['ultiscape_businessId']) {
			header("location: ../");
			exit();
		}
	} else {
		header("location: ../");
		exit();
	}

	$currentBusiness = new business($_SESSION['ultiscape_businessId']);

	if ($currentJob->businessId != $_SESSION['ultiscape_businessId']) {
        header("location: ../");
		exit();
    }


	$titleName = $currentJob->name;

	// Seperate the dateTime strings

	$instanceDate = new DateTime($currentJob->instanceDate);
	$instanceDate = $instanceDate->format('Y-m-d');
	if ($currentJob->existed) {
		$startDateArray = explode(' ', $currentJob->startDateTime);
		if ($currentJob->endDateTime == NULL) {
			$endDateArray = ['', ''];
		} else {
			$endDateArray = explode(' ', $currentJob->endDateTime);
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

	echo $customerUIRender->renderCustomerHtmlTop('../../../', htmlspecialchars($titleName), 'View '.htmlspecialchars($titleName).'.');
	echo $customerUIRender->renderCustomerUIMenuToggleScripts('../../../');

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
		var completedJobId ='<?php echo $currentJob->completedJobId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var lastChange = new Date();
		var changesSaved = true;
		var waitingForError = false;

		var errorAddressId;

		var currentJobDate = url.searchParams.get('instance');

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
			
			var recurrencePreview = '';
			
			// Determine the frequency
			if (freqInt == 'none') {
				recurrencePreview = "Occurs once.";
			} else {

				var url = new URL(window.location.href);

				if (url.searchParams.get('instance') != null) {
					if (Date.parse(url.searchParams.get('instance'))) {
						var currentJobDate = url.searchParams.get('instance');
					} else {
						if (Date.parse($("#startDate").val())) {
							var currentJobDate = $("#startDate").val();
						} else {
							var currentJobDate = '<?php echo $startDate ?>';
						}
					}
				} else {
					if (Date.parse($("#startDate").val())) {
						var currentJobDate = $("#startDate").val();
					} else {
						var currentJobDate = '<?php echo $startDate ?>';
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

			$("#recurrencePreview").html("Recurence is/was <b>" + recurrencePreview + "</b>.");
		}

		// ON LOAD
		$(function() {
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

				updateRecurrencePreview(<?php echo $frequencyIntervalOutput ?>, <?php echo $frequencyOutput; ?>, <?php echo $weekdayOutput; ?>, <?php echo $weekNumberOutput; ?>, <?php echo $dayOfMonthOutput; ?>);
		});
	</script>
</head>

<body>
	<span style="display: none;" id="scriptLoader"></span>
	<div class="adminBodyWrapper">

		<?php 
			echo $customerUIRender->renderCustomerTopBar('../../../', true, true, true);
		?>

		<?php 
            echo $customerUIRender->renderCustomerSideBar('../../../');
        ?>

		<div class="cmsMainContentWrapper textColorThemeGray styledText">
			<form class="defaultForm maxHeight" id="jobForm">

				<input type="hidden" name="instanceDate" id="instanceDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>
						
						<h3>Completed Service</h3>
						<div class="defaultInputGroup">
							<label for="name"><p>Name</p></label>
							<h3 id="name"><?php echo htmlspecialchars($currentJob->name); ?></h3>

							<br>

							<div>
								<label for="property"><p>Property</p></label>
								<p id="property"><b>
									<?php
										$useArchviedProperty = false;
										require_once '../../../../lib/table/property.php';
										if (!empty($currentJob->linkedToPropertyId)) {
											$currentProperty = new property($currentJob->linkedToPropertyId);
											if ($currentProperty->existed) {
												echo '<a href="../../properties/property/?id='.htmlspecialchars($currentProperty->propertyId).'">';
												echo htmlspecialchars($currentProperty->address1.' - '.$currentProperty->city);
												echo '</a>';
											} else {
												$useArchviedProperty = true;
											}
										}

										if ($useArchviedProperty) {
											if (!empty($currentJob->propertyAddress1)) {
												echo htmlspecialchars($currentJob->propertyAddress1.' - '.$currentJob->propertyCity);
											} else {
												echo 'Not assigned';
											}
										}
									
									?></b></p>
							</div>

							<span style="display: <?php if (!empty($currentJob->description)) { echo 'block'; } else { echo 'none'; }; ?>;">
								<br>
								<label for="description"><p>Description (Visible to Customer)</p></label>
								<div style="font-size: 1.2em; width: 80%; height: 3em; border: 1px solid gray; border-radius: .3em; padding: .5em; overflow: scroll; resize: vertical;" id="description"><?php echo nl2br(htmlspecialchars($currentJob->description)); ?></div>
							</span>

							<br>

							<div class="threeCol">
								<div>
									<label for="name"><p>Price</p></label>
									<p id="price" style="color:green; font-size: 1.2em;"><b>
										<?php 
											if ($currentJob->price == NULL || $currentJob->price <= 0) {
												echo '<span>Free</span>';
											} else {
												echo htmlspecialchars($currentBusiness->currencySymbol); echo htmlspecialchars($currentJob->price);
											}
										?>
									</b></p>
								</div>

								<div>
									<?php 
										// if (!($currentJob->estHours == NULL || $currentJob->estHours <= 0)) {
										// 	echo '<label for="name"><p>Estimated Hours</p></label><p id="estHours" style="font-size: 1.2em;"><b>';
										// 	echo htmlspecialchars($currentJob->estHours);
										// 	echo '</b></p>';
										// 	if ($currentJob->price != NULL || $currentJob->price > 0) {
										// 		echo '<p id="perHourCalc" style="color: gray;">$'.$currentJob->price / $currentJob->estHours.'/hour</p>';
										// 	}
											
										// }
									?>
								</div>

								<div>
									<?php 
										if ((bool)$currentJob->isPrepaid) {
											echo '<p id="isPrepaid" style="font-size: 1.2em;">This service was billed upon scheduling (prepaid).</p>';
										} else {
											echo '<p id="isPrepaid" style="font-size: 1.2em;">This service was billion upon completion.</p>';
										}
									?>
								</div>
							</div>

							<br><hr style="border-color: var(--utliscapeColorTheme);" class="defaultMainShadows"><br>

								<div>
									<label for="startDate"><p>Date</p></label>
									<?php

										if (!empty($currentJob->startDateTime)) {
											$startDateTimeOutput = new DateTime($currentJob->startDateTime);
											$startDateTimeOutput = $startDateTimeOutput->format('D, M d Y \a\t h:i');
										} else {
											$startDateTimeOutput = NULL;
										}

										if (!empty($currentJob->startDateTime)) {
											$endDateTimeOutput = new DateTime($currentJob->endDateTime);
											$endDateTimeOutput = $endDateTimeOutput->format('D, M d Y \a\t h:i');
										} else {
											$endDateTimeOutput = NULL;
										}


										if (empty($currentJob->endDateTime)) {
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

						<h3>Recurrence</h3>

							<div>
								<p id="recurrencePreview"></p>
							</div>
						
						</div>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br>

						<h3>Other Info</h3>

						<?php
							$jobAdded = new DateTime($currentJob->dateTimeAdded);
						?>

						<p>Marked completed on <?php echo $jobAdded->format('D, M d Y'); ?></p>
					</div>
				</div>

			</form>

		</div>

		<?php
			echo $customerUIRender->renderCustomerFooter('../../../');
		?>

		<?php 
			echo $customerUIRender->renderCustomerMobileNavBar('../../../');
		?>

	</div>

	<?php
		echo $customerUIRender->renderCustomerTopBarDropdowns('../../../');
	?>
</body>
<?php 
	echo $customerUIRender->renderCustomerHtmlBottom('../../../');
?>
