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

	// Seperate the dateTime strings
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

	$deleteJobAuthToken = new authToken();
	$deleteJobAuthToken->authName = 'deleteJob';
	$deleteJobAuthToken->set();

	$cancelJobAuthToken = new authToken();
	$cancelJobAuthToken->authName = 'cancelJob';
	$cancelJobAuthToken->set();

	$completeJobAuthToken = new authToken();
	$completeJobAuthToken->authName = 'completeJob';
	$completeJobAuthToken->set();

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

		function openChangeRecurrenceDialog() {
			$("#recurrenceSettings .popupMessageDialog").hide(0, function () {
				$("#recurrenceSettings").fadeIn(100, function () {
					$("#recurrenceSettings .popupMessageDialog").show(300);
				});
			});
		}

		function saveRecurringSettings() {
			$("#recurrenceSettings .popupMessageDialog").slideUp(300, function () {
				$("#recurrenceSettings").fadeOut(100, function () {
					setUnsaved();
				});
			});
		}

		function updateRecurringInputs() {

			if ($("#isRecurring").is(':checked')) {
				$("#recurringSettingsInnerBox").show(300);
			} else {
				$("#recurringSettingsInnerBox").hide(300);
			}

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

			var monthDayNumber = $('#startDate').val();
			// If daily or yearly is selected, hide the week selector and month recurrence selector
			if ($("#frequencyInterval option:selected").val() == 'day' || $("#frequencyInterval option:selected").val() == 'year') {
				$("#weekdaySelector").hide(300);
				$("#monthRecurrenceSelector").hide(300);
			} else if ($("#frequencyInterval option:selected").val() == 'week') { // If week is selected, show the weekday selector
				$("#weekdaySelector").show(300);
				$("#monthRecurrenceSelector").hide(300);
			} else if ($("#frequencyInterval option:selected").val() == 'month') { // If month is selected, show the monthRecurrenceSelector and update it's inputs
				$("#weekdaySelector").hide(300);
				$("#monthRecurrenceSelector").show(300);

				var dateArray = currentInstanceDate.split('-');

				var dateObject = new Date(parseInt(dateArray[0]), parseInt(dateArray[1])-1, parseInt(dateArray[2]));

				// Set first option to the number of the day in the date
				
				$('select[id=monthRecurrenceSelectorInput] option:first').html("Day " + parseInt(dateArray[2]));

				// Set second option to the week that the date falls in in the month
				// and the day of the week (0-6, Sunday-Saturday) that the date falls in

				var weekOfMonth = getWeekNumber(currentInstanceDate);
				var weekDayOfWeek = dateObject.getDay();

				switch (weekDayOfWeek) {
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

				$('select[id=monthRecurrenceSelectorInput] option:eq(1)').html("The " + weekDayName + " of week " + weekOfMonth);

			}
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
						
						break;
					default:
						$("#recurrencePreview").html("Error...");
						break;
				}
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
			$("#scriptLoader").load("./scripts/async/deleteJob.script.php", {
				jobId: jobId,
				deleteJobAuthToken: '<?php echo $deleteJobAuthToken->authTokenId; ?>'
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
			
			$("#scriptLoader").load("./scripts/async/completeJob.script.php", {
				jobId: jobId,
				instanceDate: currentInstanceDate,
				completeJobAuthToken: '<?php echo $completeJobAuthToken->authTokenId; ?>'
			}, function () {
				scriptOutput = $("#scriptLoader").html().split(":::");
				jobId = scriptOutput[0];
				formState = scriptOutput[1];
				if (formState == 'success') {
					window.location.href = '../completedJob?id=' + jobId;
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

		// ON LOAD
		$(function() {

			$("#jobForm :input").change(function () {
				inputChange();
			});

			// Update the last job recurring details so that it shows the right recurrance update prompt options
			lastFreq = $("input[name='frequency']").val();
			lastFreqInt = $("#frequencyInterval option:selected").val();
			lastStartDate = $("input[name='startDate']").val();
			lastWeekday = $("input[name='weekday']").val();
			lastMonthRecurrenceType = $("input[name='monthRecurrenceSelector']").val();
			if ($("#isRecurring").is(':checked')) {
				lastIsRecurring = true;
			} else {
				lastIsRecurring = false;
			}

			console.log(lastFreq);
					console.log(lastFreqInt);
					console.log(lastStartDate);

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
					$(":input").change(function (e) {
						inputChange(e);
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
					$(":input").change(function (e) {
						inputChange(e);
					});
					registerJobCrewsDeleteButtonClicks();
				});
			}

			function sendChanges(formData) {
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
								window.location.reload();
							}
							checkStaff = true;
							checkCrews = true;
							
							// property selector

							if ($("#customerSelector").val() != 'none') {
								$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
									jobId: jobId,
									customerId: $("#customerSelector").val()
								}, function () {
									$(":input").change(function (e) {
										inputChange(e);
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

					// Update the last job recurring details so that it shows the right recurrance update prompt options
					lastFreq = $("input[name='frequency']").val();
					lastFreqInt = $("#frequencyInterval option:selected").val();
					lastStartDate = $("input[name='startDate']").val();
					lastWeekday = $("input[name='weekday']").val();
					lastMonthRecurrenceType = $("input[name='monthRecurrenceSelector']").val();
					if ($("#isRecurring").is(':checked')) {
						lastIsRecurring = true;
					} else {
						lastIsRecurring = false;
					}
				});
			}

			$("#jobForm").submit(function(event) {
				event.preventDefault();
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});

				if (!changesSaved) {
					formData = $("#jobForm").serialize();

					// If the job was a one-time job to start with, just load the script
					if (!lastIsRecurring) { 
						sendChanges(formData);
					// If we are updating a recurring job
					} else { 
						// If updating anything but recurrence, options are single instance and this and future
						if (lastFreq == $("input[name='frequency']").val() && lastFreqInt == $("#frequencyInterval option:selected").val() && lastWeekday == $("input[name='weekday']").val() && lastMonthRecurrenceType == $("input[name='monthRecurrenceSelector']").val()) {
							$("#recurrenceUpdateOptionThisOnly").show(0);
							$("#recurrenceUpdateOptionThisAndFuture").show(0);
							$("#recurrenceUpdateOptionAll").hide(0);
							$("#recurrenceUpdatePrompt").fadeIn(300);
						// If updating the recurrence of an instance, options are this and future and "all"
						} else if (!(lastFreq == $("input[name='frequency']").val() && lastFreqInt == $("#frequencyInterval option:selected").val() && lastWeekday == $("input[name='weekday']").val() && lastMonthRecurrenceType == $("input[name='monthRecurrenceSelector']").val()) && lastStartDate == $("input[name='startDate']").val()) {
							$("#recurrenceUpdateOptionThisOnly").hide(0);
							$("#recurrenceUpdateOptionThisAndFuture").show(0);
							$("#recurrenceUpdateOptionAll").show(0);
							$("#recurrenceUpdatePrompt").fadeIn(300);
						// If updating the recurrence AND the start date of an instance, there are no options, the parent recurring job is ended and a new one is created with those new options
						} else {
							sendChanges(formData);
						}
					}
				}

				$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				
				
			});

			// Load the staff form on startup
			loadJobStaff();

			// Load the crews form on startup
			loadJobCrews();

			// Load the recurring inputs correctly on startup
			updateRecurringInputs();

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

			// Load the property selector on startup
			if ($("#customerSelector").val() != 'none') {
				$("#propertySelectorLoader").load('./includes/selectProperty.inc.php', {
					jobId: jobId,
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
				<input type="hidden" name="updateJobStaffAuthToken" id="updateJobStaffAuthToken" value="<?php echo htmlspecialchars($updateJobStaffAuthToken->authTokenId); ?>">
				<input type="hidden" name="updateJobCrewsAuthToken" id="updateJobCrewsAuthToken" value="<?php echo htmlspecialchars($updateJobCrewsAuthToken->authTokenId); ?>">

				<input type="hidden" name="deleteJobAuthToken" id="deleteJobAuthToken" value="<?php echo htmlspecialchars($deleteJobAuthToken->authTokenId); ?>">
				<input type="hidden" name="cancelJobAuthToken" id="cancelJobAuthToken" value="<?php echo htmlspecialchars($deleteJobAuthToken->authTokenId); ?>">
				<input type="hidden" name="completeJobAuthToken" id="completeJobAuthToken" value="<?php echo htmlspecialchars($completeJobAuthToken->authTokenId); ?>">

				<input type="hidden" name="instanceDate" id="instanceDate" value="<?php if (isset($_GET['instance'])) {echo htmlspecialchars($_GET['instance']);} else {echo htmlspecialchars($startDate);} ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>

						<?php
						
							if ($currentJob->existed) {
								echo '<div class="threeCol" style="width: 25em;">';
									echo '<span class="smallButtonWrapper greenButton centered defaultMainShadows" onclick="completeButton()"><span id="completeButtonText">✔ Complete</span><span style="display: none;" id="completeLoading"><img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif"></span></span>';
									echo '<span class="smallButtonWrapper orangeButton centered defaultMainShadows" onclick="cancelButton()">❌Cancel</span>';
									echo '<span class="smallButtonWrapper redButton centered defaultMainShadows" onclick="deleteButton()"><img style="height: 1.2em;" src="../../../images/ultiscape/icons/trash.svg"> Delete</span>';
								echo '</div>';

								echo '<br>';
							}
						
						?>

						

						
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

								

							?>

							<div><span id="clearEndDateTime" class="smallButtonWrapper orangeButton" style="float: right;" onclick="clearDates()">Clear Dates</span></div>

								<div>
									<div class="twoCol" style="max-width: 25em;">
										<div>
											<label for="startDate"><p>Start Date</p></label>
											<input onchange="updateRecurringInputs()" class="defaultInput" style="width: 100%; max-width: 9em;" type="date" name="startDate" id="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
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
									$("#startDate").val("");
									$("#startTime").val("");
									$("#endDate").val("");
									$("#endTime").val("");
								}

								function changeStartDate() {
									if ($("#endDate").val() == '') {
										$("#endDate").val() == $("#startDate").val();
									}
									updateRecurringInputs();
								}
							</script>
							
						</div>

						<br>

						<h3>Recurrence</h3>
						<div class="defaultInputGroup" id="recurringSettingsBox">

						<div>
							<p id="recurrencePreview"></p>
						</div>
						
						<br>

						<span onclick="openChangeRecurrenceDialog()" id="changeRecurrenceButton" class="smallButtonWrapper greenButton" style="">Change Recurrence</span>

							<div id="recurrenceSettings" class="dimOverlay xyCenteredFlex" style="display: none;">
								<div class="popupMessageDialog" style="width: 25em; max-width: 85%;">

									<span onclick="saveRecurringSettings()" id="clearEndDateTime" class="smallButtonWrapper greenButton" style="float: right;">Save Changes</span>
									<input class="defaultInput" type="checkbox" name="isRecurring" id="isRecurring" <?php if ($currentJob->frequencyInterval != 'none') {echo 'checked="checked"';} ?> onchange="updateRecurringInputs()"><label for="isRecurring"> <p style="display: inline; clear: both;">This job is recurring</p></label>
									<br>
									
									<div id="recurringSettingsInnerBox">
									
										<br>

										<span>Every </span>
										<input class="defaultInput" style="width: 5em;" type="number" min="0" max="999999999999" name="frequency" id="frequency" placeholder="Est. Hours..." value="<?php echo htmlspecialchars($currentJob->frequency); ?>">

										<select class="defaultInput" name="frequencyInterval" id="frequencyInterval" onchange="updateRecurringInputs()">
											<option value="day"<?php if ($currentJob->frequencyInterval == 'day') {echo ' selected="selected"';} ?>>day(s)</option>
											<option value="week"<?php if ($currentJob->frequencyInterval == 'week') {echo ' selected="selected"';} ?>>week(s)</option>
											<option value="month"<?php if ($currentJob->frequencyInterval == 'month') {echo ' selected="selected"';} ?>>month(s)</option>
											<option value="year"<?php if ($currentJob->frequencyInterval == 'year') {echo ' selected="selected"';} ?>>year(s)</option>
										</select>
										<span id="frequencyIntervalError" class="underInputError" style="display: none;"><br>Select an option from the menu.</span>
										<span id="frequencyError" class="underInputError" style="display: none;"><br>Enter a number.</span>
										
										<div id="weekdaySelector" style="display: none;">
											<br>
											<span>On </span><select class="defaultInput" name="weekdaySelector" id="weekdaySelectorInput">
												<option value="0"<?php if ($currentJob->weekday == '0') {echo ' selected="selected"';} ?>>Sunday</option>
												<option value="1"<?php if ($currentJob->weekday == '1') {echo ' selected="selected"';} ?>>Monday</option>
												<option value="2"<?php if ($currentJob->weekday == '2') {echo ' selected="selected"';} ?>>Tuesday</option>
												<option value="3"<?php if ($currentJob->weekday == '3') {echo ' selected="selected"';} ?>>Wednesday</option>
												<option value="4"<?php if ($currentJob->weekday == '4') {echo ' selected="selected"';} ?>>Thursday</option>
												<option value="5"<?php if ($currentJob->weekday == '5') {echo ' selected="selected"';} ?>>Friday</option>
												<option value="6"<?php if ($currentJob->weekday == '6') {echo ' selected="selected"';} ?>>Saturday</option>
											</select>
										</div>
										<span id="weekdayError" class="underInputError" style="display: none;"><br>Select a valid weekday.</span>

										<div id="monthRecurrenceSelector" style="display: none;">
											<br>
											<span>On </span><select class="defaultInput" name="monthRecurrenceSelector" id="monthRecurrenceSelectorInput">
												<option value="dayNumber"<?php if ($currentJob->weekday == '0') {echo ' selected="selected"';} ?>>Day <span id="monthRecurrenceDayNumber">(Loading...)</span></option>
												<option value="weekdayOfWeekNumber"<?php if ($currentJob->weekday == '1') {echo ' selected="selected"';} ?>>The <span id="monthRecurrenceWeekNumber">(Loading...)</span> <span id="monthRecurrenceWeekday">(Loading...)</span></option>
											</select>
										</div>
										<span id="monthRecurrenceSelectorError" class="underInputError" style="display: none;"><br>Select a valid option.</span>
									</div>

								</div>
							</div>

							<div id="recurrenceUpdatePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
								<div class="popupMessageDialog">
									<h3>Update Recurring Job</h3>
									<br>

									<span id="recurrenceUpdateOptionThisOnly">
										<input type="radio" name="recurrenceUpdateType" id="thisInstance" value="thisInstance"><label for="thisInstance"> This Instance Only</label>
										<br>
									</span>

									<span id="recurrenceUpdateOptionThisAndFuture">
										<input type="radio" name="recurrenceUpdateType" id="thisAndFutureInstances" value="thisAndFutureInstances"><label for="thisAndFutureInstances"> This and Future Instances</label>
										<br>
									</span>

									<span id="recurrenceUpdateOptionAll">
										<input type="radio" name="recurrenceUpdateType" id="allInstances" value="allInstances"><label for="allInstances"> All Instances</label>
										<br>
									</span>

									<br>

									<span id="recurrenceUpdatePromptOkButton" class="smallButtonWrapper greenButton xCenteredFlex" onclick="checkRecurrenceUpdatePrompt()">Ok</span>
								</div>
							</div>

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

						<label for="privateNotes"><p>Notes (Private to Admins)</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 80%;" name="privateNotes" id="privateNotes"><?php echo htmlspecialchars($currentJob->privateNotes); ?></textarea>
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
							$addedDate = new DateTime($currentJob->dateTimeAdded);
						?>

						<p>Added on <?php echo $addedDate->format('D, d M y'); ?></p>
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
