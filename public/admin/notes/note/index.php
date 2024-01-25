<?php

	// Start Session
	require_once '../../../php/startSession.php';



	require_once '../../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../../lib/table/admin.php';
	require_once '../../../../lib/table/note.php';
	require_once '../../../../lib/table/business.php';
	require_once '../../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentNote = new note($_GET['id']);
	} else {
		$currentNote = new note();
	}

	// If the requested note is not associated with the current business, redirect to blank new note page
	if ($currentNote->businessId != $_SESSION['ultiscape_businessId']) {
        header("location: ./");
		exit();
    }

	$currentBusiness = new business($_SESSION['ultiscape_businessId']);

	if ($currentNote->existed) {
		$titleName = $currentNote->title;
	} else {
		$titleName = 'New Note';
	}

	echo $adminUIRender->renderAdminHtmlTop('../../../', [
		"pageTitle" => htmlspecialchars($titleName),
		"pageDescription" => 'Edit '.htmlspecialchars($titleName).'.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editNote';
	$mainAuthToken->set();

	$editNotePrivacyAuthToken = new authToken();
	$editNotePrivacyAuthToken->authName = 'editNotePrivacy';
	$editNotePrivacyAuthToken->set();

	$deleteNoteAuthToken = new authToken();
	$deleteNoteAuthToken->authName = 'deleteNote';
	$deleteNoteAuthToken->set();

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

	<link rel="stylesheet" href="../../../js/etc/form/easymde/easymde.min.css">
	<script src="../../../js/etc/form/easymde/easymde.min.js"></script>

	<script>
		var formData;
		var scriptOutput;
		var noteId ='<?php echo $currentNote->noteId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var isNewNote = <?php if ($currentNote->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();

		var changesSaved = true;
		var waitingForError = false;
		var editingPrivacy = false;
		var deleting = false;

		// RECORD PAYMENT BUTTON FUNCTIONS
		function editPrivacyButton() {
			$("#editPrivacyPrompt").fadeIn(300);
		}
		function editPrivacySave() {
			$("#editPrivacyPrompt").fadeOut(300);
		}

		// DELETE BUTTON FUNCTIONS
		function deleteButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				saveChanges();
				$("#deletePrompt").fadeIn(300);
			} else {
				$("#deletePrompt").fadeIn(300);
			}
		}
		function deleteYes() {
			// Delete run the script
			if (!deleting) {
				deleting = true;
				$("#deleteLoading").fadeIn(300);
				$("#scriptLoader").load("./scripts/async/deleteNote.script.php", {
					noteId: noteId,
					deleteNoteAuthToken: '<?php echo $deleteNoteAuthToken->authTokenId; ?>'
				}, function () {
					if ($("#scriptLoader").html() == 'success') {
						window.location.href = '../?popup=noteDeleted&noteDeletednoteTitle=<?php echo htmlspecialchars($currentNote->title); ?>';
					} else {
						deleting = false;
						$("#deleteLoading").fadeOut(300);
						$("#deletePrompt").fadeOut(300);
					}
				});
			}
		}
		function deleteNo() {
			// Just hide the prompt
			$("#deletePrompt").fadeOut(300);
		}

		$(function() {

			var lastContent = easyMDE.value();
			var currentContent = '';

			if (isNewNote) {
				$("#title").focus();
			}

			$("#noteForm").submit(function(event) {
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
				if ((currentTime.getTime() - lastChange.getTime()) > 1000 && !changesSaved) {
					saveChanges();
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

				lastContent = easyMDE.value();
			}

			if ($.isNumeric(url.searchParams.get('wsl'))) {
				$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			}

			function saveChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				
				formData = $("#noteForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editNote.script.php", {
					noteId: noteId,
					formData: formData
				}, function () {
					scriptOutput = $("#scriptLoader").html().split(":::");
					noteId = scriptOutput[0];
					formState = scriptOutput[1];
					clearFormErrors();

					switch (formState) {
						case 'success':
							setSaved();
							if (isNewNote) {
								isNewNote = false;
								window.history.pushState("string", 'LifeMS (Admin) - New Note', "./?id="+noteId);
								window.location.reload();
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

					$('.loadingGif').each(function() {
						$(this).fadeOut(100);
					});
				});
				changesSaved = true;
			}

			// window.onbeforeunload = function() {
			// 	if (changesSaved == false || waitingForError == true) {
			// 		return "Changes have not been saved yet. Are you sure you would like to leave?";
			// 	} else {
			// 		return;
			// 	}
			// };

			// Show and hide view password button
			$("#showViewPassButton").on("click", function () {
				if($("#showViewPassButton").html() == "Show"){
					$('#password').show(200);
					$("#showViewPassButton").html("Hide");
				} else {
					$('#password').hide(200);
					$("#showViewPassButton").html("Show");
				}
			});

			// Show and hide edit password button
			$("#showEditPassButton").on("click", function () {
				if($("#showEditPassButton").html() == "Show"){
					$('#password').show(200);
					$("#showEditPassButton").html("Hide");
				} else {
					$('#password').hide(200);
					$("#showEditPassButton").html("Show");
				}
			});

			$("#noteForm :input").change(function () {
				inputChange();
			});

			$(window).on('change keyup keydown keypress paste', function() {
				inputChange();
				lastChange = new Date();
			});

			// Every 2 seconds, if the value of the markdown textarea has changed but it has not registered
			// a change above (like when backspace or return is pressed)
			setInterval(function () {
				currentContent = easyMDE.value();
				if (currentContent != lastContent) {
					setUnsaved();
				}
			}, 2000);
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
				<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
			</div>

				<div class="twoColPage-Content-InfoSmall maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<form class="defaultForm" id="noteForm">

							<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">

							<br>

							<?php
							
								if ($currentNote->existed) {
									if ($currentNote->viewPrivacy != 'private' || $currentNote->editPrivacy != 'private') {
										$lockIcon = 'lock_open';
									} else {
										$lockIcon = 'lock';
									}

									echo '<div class="twoCol" style="width: 21em;">';
										echo '<span style="width: 9em;" class="smallButtonWrapper greenButton centered defaultMainShadows" onclick="editPrivacyButton()"><img style="height: 1.2em;" src="../../../images/ultiscape/icons/'.$lockIcon.'.svg"> Sharing Settings</span>';
										echo '<span style="width: 5em;" class="smallButtonWrapper redButton centered defaultMainShadows" onclick="deleteButton()"><img style="height: 1.2em;" src="../../../images/ultiscape/icons/trash.svg"> Delete</span>';
									echo '</div>';

									echo '<br>';
								}
							
							?>

							<label for="title"><p>Title</p></label>
							<input class="bigInput" style="width: 95%;" type="text" name="title" id="title" placeholder="Title..." value="<?php echo htmlspecialchars($currentNote->title); ?>">
							<span id="titleError" class="underInputError" style="display: none;"><br>Please enter a title for the note.</span>
							
							<br><br>

							<textarea class="noteEditor" name="bodyMarkdown" id="bodyMarkdown"><?php echo htmlspecialchars($currentNote->bodyMarkdown); ?></textarea>
							<script>
								const easyMDE = new EasyMDE({
									autofocus: false,
									autosave: {
										enabled: false,
										uniqueId: "MyUniqueID",
										delay: 1000,
										submit_delay: 5000,
										timeFormat: {
											locale: 'en-US',
											format: {
												year: 'numeric',
												month: 'long',
												day: '2-digit',
												hour: '2-digit',
												minute: '2-digit',
											},
										},
										text: "Autosaved: "
									},
									blockStyles: {
										bold: "__",
										italic: "_",
									},
									unorderedListStyle: "-",
									// element: document.getElementById("noteEditor"),
									forceSync: true,
									hideIcons: ["preview"],
									indentWithTabs: false,
									// initialValue: "",
									insertTexts: {
										horizontalRule: ["", "\n\n-----\n\n"],
										image: ["![](http://", ")"],
										link: ["[", "](https://)"],
										table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
									},
									lineWrapping: true,
									minHeight: "500px",
									parsingConfig: {
										allowAtxHeaderWithoutSpace: false,
										strikethrough: false,
										underscoresBreakWords: true,
									},
									placeholder: "Type here...",

									// previewClass: "my-custom-styling",
									// previewClass: ["my-custom-styling", "more-custom-styling"],

									// previewRender: (plainText) => customMarkdownParser(plainText), // Returns HTML from a custom parser
									// previewRender: (plainText, preview) => { // Async method
									// 	setTimeout(() => {
									// 		preview.innerHTML = customMarkdownParser(plainText);
									// 	}, 250);

									// 	// If you return null, the innerHTML of the preview will not
									// 	// be overwritten. Useful if you control the preview node's content via
									// 	// vdom diffing.
									// 	// return null;

									// 	return "Loading...";
									// },
									promptURLs: true,
									promptTexts: {
										image: "Custom prompt for URL:",
										link: "Custom prompt for URL:",
									},
									renderingConfig: {
										singleLineBreaks: false,
										codeSyntaxHighlighting: true,
										sanitizerFunction: (renderedHTML) => {
											// Using DOMPurify and only allowing <b> tags
											return DOMPurify.sanitize(renderedHTML, {ALLOWED_TAGS: ['b']})
										},
									},
									shortcuts: {
										drawTable: "Cmd-Alt-T"
									},
									showIcons: ["code", "table"],
									spellChecker: false,
									status: false,
									// status: ["autosave", "lines", "words", "cursor"], // Optional usage
									status: ["lines", "words", "cursor", {
										className: "keystrokes",
										defaultValue: (el) => {
											el.setAttribute('data-keystrokes', 0);
										},
										onUpdate: (el) => {
											const keystrokes = Number(el.getAttribute('data-keystrokes')) + 1;
											el.innerHTML = `${keystrokes} Keystrokes`;
											el.setAttribute('data-keystrokes', keystrokes);
										},
									}], // Another optional usage, with a custom status bar item that counts keystrokes
									styleSelectedText: false,
									sideBySideFullscreen: false,
									syncSideBySidePreviewScroll: false,
									tabSize: 4
									// toolbar: true,
									// toolbarTips: false
									// toolbarButtonClassPrefix: "mde"
								});
							</script>

							<div id="editPrivacyPrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
								<div class="popupMessageDialog" style="width: 30em;">
									<h3>Change Sharing Settings</h3>

									<p>Viewing Rights</p>
									<input type="radio" name="editViewPrivacy" value="private" id="viewPrivacyRadioPrivate" <?php if ($currentNote->viewPrivacy == 'private') {echo 'checked="checked"';} ?>><label for="viewPrivacyRadioPrivate">Private</label>
									<br>
									<input type="radio" name="editViewPrivacy" value="password" id="viewPrivacyRadioPassword" <?php if ($currentNote->viewPrivacy == 'password') {echo 'checked="checked"';} ?>><label for="viewPrivacyRadioPassword">Password Protected</label>
									<br>
									<input type="radio" name="editViewPrivacy" value="link" id="viewPrivacyRadioLink" <?php if ($currentNote->viewPrivacy == 'link') {echo 'checked="checked"';} ?>><label for="viewPrivacyRadioLink">Anyone with the Link</label>
									<span id="editViewPrivacyError" class="underInputError" style="display: none;"><br>Select an option.</span>

									<br>
									<label for="editViewPass"><p>Password to <b>View</b></p></label>
									<input class="defaultInput" id="editViewPass" type="text" name="editViewPass" style="width: 10em;" value="<?php echo htmlspecialchars(strval($currentNote->viewPass)) ?>">
									<span id="editViewPassError" class="underInputError" style="display: none;"><br>Enter a valid password.</span>
									<br><br>

									<p>Editing Rights</p>
									<input type="radio" name="editEditPrivacy" value="private" id="editPrivacyRadioPrivate" <?php if ($currentNote->editPrivacy == 'private') {echo 'checked="checked"';} ?>><label for="editPrivacyRadioPrivate">Private</label>
									<br>
									<input type="radio" name="editEditPrivacy" value="password" id="editPrivacyRadioPassword" <?php if ($currentNote->editPrivacy == 'password') {echo 'checked="checked"';} ?>><label for="editPrivacyRadioPassword">Password Protected</label>
									<br>
									<input type="radio" name="editEditPrivacy" value="link" id="editPrivacyRadioLink" <?php if ($currentNote->editPrivacy == 'link') {echo 'checked="checked"';} ?>><label for="editPrivacyRadioLink">Anyone with the Link</label>
									<span id="editEditPrivacyError" class="underInputError" style="display: none;"><br>Select an option.</span>

									<br>
									<label for="editEditPass"><p>Password to <b>Edit</b></p></label>
									<input class="defaultInput" id="editEditPass" type="text" name="editEditPass" style="width: 10em;" value="<?php echo htmlspecialchars(strval($currentNote->editPass)) ?>">
									<span id="editEditPassError" class="underInputError" style="display: none;"><br>Enter a valid password.</span>
									<br><br>

									<div id="editPrivacyButtons" class="twoCol centered" style="width: 15em;">
										<div>
											<span id="editPrivacySaveButton" class="smallButtonWrapper greenButton" onclick="editPrivacySave()">Save <span style="display: none;" id="editPrivacyLoading"><img style="width: 1em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif"></span></span>
										</div>
									</div>

								</div>
							</div>

						</form>
						
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br class="desktopOnlyBlock">
						<span class="desktopOnlyBlock">
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
						</span>

						<br><hr><br>

						<h3>Other Info</h3>

						<?php
							$addedDate = new DateTime($currentNote->dateTimeAdded);
							$updatedDate = new DateTime($currentNote->lastUpdate);
						?>

						<p>Added on <?php echo $addedDate->format('D, M d Y'); ?></p>
						<p>Last updated <?php echo $updatedDate->format('D, M d Y h:m'); ?></p>
					</div>
				</div>

				<div id="deletePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog">
						<h3>Delete Note?</h3>
						<p>This is not reversable!</p>
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
