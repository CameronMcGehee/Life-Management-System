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
	require_once '../../../../lib/table/admin.php';
	require_once '../../../../lib/table/job.php';
	require_once '../../../../lib/table/jobCancellation.php';
	require_once '../../../../lib/table/jobCompleted.php';
	require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentJob = new job($_GET['id']);
	} else {
		$currentJob = new job();
	}

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
				
				formData = $("#customerForm").serialize();
				
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

					if (checkStaff) {
						$("#scriptLoader").load("./scripts/async/updateJobStaff.script.php", {
							jobId: jobId,
							formData: formData
						}, function () {
							scriptOutput = $("#scriptLoader").html().split("::::");
							formState = scriptOutput[1];

							switch (formState) {
								case 'success':
									setSaved();
									checkCrews = true;
									// Reload the emails box if a new email was put in
									if ($("#newJobStaff").val().length > 0) {
										loadJobStaff();
									}
									break;
								default:
									setWaitingForError();
									showFormError("#"+formState+"Error", "#"+formState);
									$("#"+formState).shake(50);
									checkCrews = false;
									$('.loadingGif').each(function() {
										$(this).fadeOut(100);
									});
									break;
							}
						});
					}

					if (checkCrews) {
						$("#scriptLoader").load("./scripts/async/updateJobCrews.script.php", {
							jobId: jobId,
							formData: formData
						}, function () {
							scriptOutput = $("#scriptLoader").html().split("::::");
							formState = scriptOutput[1];

							switch (formState) {
								case 'success':
									setSaved();
									// Reload the emails box if a new email was put in
									if ($("#newJobCrews").val().length > 0) {
										loadJobCrews();
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

			// Load the staff form on startup
			loadJobStaff();

			// Load the crews form on startup
			loadJobCrews();

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
			<form class="defaultForm maxHeight" id="customerForm">

				<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">
				<input type="hidden" name="updateJobStaffAuthToken" id="updateJobStaffAuthToken" value="<?php echo htmlspecialchars($updateJobStaffAuthToken->authTokenId); ?>">
				<input type="hidden" name="updateJobCrewsAuthToken" id="updateJobCrewsAuthToken" value="<?php echo htmlspecialchars($updateJobCrewsAuthToken->authTokenId); ?>">

				<div class="twoColPage-Content-Info maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<br>

						<input class="bigInput" style="width: 93%;" type="text" name="name" id="name" placeholder="Name..." value="<?php echo htmlspecialchars($currentJob->name); ?>">
						<span id="nameError" class="underInputError" style="display: none;"><br>Please enter a name for the job.</span>

						<br><br>

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
