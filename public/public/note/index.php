<?php

	// Start Session
	require_once '../../php/startSession.php';

	require_once '../../../lib/publicNotesUIRender.php';
	$publicNotesUIRender = new publicNotesUIRender();

	// Other required libraries
	require_once '../../../lib/table/admin.php';
	require_once '../../../lib/table/note.php';
	require_once '../../../lib/table/business.php';
	require_once '../../../lib/render/etc/tagEditor.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentNote = new note($_GET['id']);
	} else {
		header("location: ./");
		exit();
	}

	$currentBusiness = new business($_SESSION['ultiscape_businessId']);

	if ($currentNote->existed) {
		$titleName = $currentNote->title;
	} else {
		header("location: ./");
		exit();
	}

	echo $publicNotesUIRender->renderHtmlTop('../../', [
		"pageTitle" => htmlspecialchars($titleName),
		"pageDescription" => 'Edit '.htmlspecialchars($titleName).'.']);

	// Generate all the needed authTokens for the page
	require_once '../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editNote';
	$mainAuthToken->set();

	// NOTE AUTHORIZATION
	$noteViewAuth = 'denied';
	$noteEditAuth = 'denied';
	$initialAuth = false;

	if (!isset($_SESSION['ultiscape_noteAccess'])) {
	} else if (gettype($_SESSION['ultiscape_noteAccess']) !== 'array') {
		$_SESSION['ultiscape_noteAccess'] = array();
	} else if (in_array([$currentNote->noteId, 'view', $currentNote->viewPass], $_SESSION['ultiscape_noteAccess'])) {
		$initialAuth = 'view';
	} else if (in_array([$currentNote->noteId, 'edit', $currentNote->editPass], $_SESSION['ultiscape_noteAccess'])) {
		$initialAuth = 'edit';
	}

	// Double check that the settings have not been changed since last session authorization
	if ($initialAuth == 'view' && ($currentNote->viewPrivacy == 'private' || ($currentNote->viewPrivacy == 'password' && $currentNote->viewPass != $initialViewPass))) {
		$initialAuth = false;
	}
	if ($initialAuth == 'edit' && ($currentNote->editPrivacy == 'private' || ($currentNote->editPrivacy == 'password' && $currentNote->editPass != $initialEditPass))) {
		$initialAuth = false;
	}

	if ($initialAuth == 'view') { // If the auth has already been granted
		$noteViewAuth = 'authorized';
	} else {
		switch ($currentNote->viewPrivacy) {
			case 'private':
				break;
			case 'password':
				$noteViewAuth = 'password'; // noteViewAuth will change to authorized once the password has been put in
				break;
			case 'link':
				$noteViewAuth = 'authorized';
				if (!in_array([$currentNote->noteId, 'view', NULL], $_SESSION['ultiscape_noteAccess'])) {
					array_push($_SESSION['ultiscape_noteAccess'], [$currentNote->noteId, 'view', NULL]);
				}
			default:
				break;
		}
	}

	if ($initialAuth == 'edit') {
		$noteEditAuth = 'authorized';
	} else {
		switch ($currentNote->editPrivacy) {
			case 'private':
				break;
			case 'password':
				$noteEditAuth = 'password'; // noteEditAuth will change to authorized once the password has been put in
				break;
			case 'link':
				$noteEditAuth = 'authorized';
				if (!in_array([$currentNote->noteId, 'edit', NULL], $_SESSION['ultiscape_noteAccess'])) {
					array_push($_SESSION['ultiscape_noteAccess'], [$currentNote->noteId, 'edit', NULL]);
				}
			default:
				break;
		}
	}

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

	<script src="../../js/etc/animation/shake.js"></script>

	<script src="../../js/etc/form/showFormError.js"></script>
	<script src="../../js/etc/form/clearFormErrors.js"></script>

	<link rel="stylesheet" href="../../js/etc/form/easymde/easymde.min.css">
	<script src="../../js/etc/form/easymde/easymde.min.js"></script>

	<script>
		var formData;
		var scriptOutput;
		var noteId = '<?php echo $currentNote->noteId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var isNewNote = false;
		var lastChange = new Date();

		var changesSaved = true;
		var waitingForError = false;

		$(function() {

			var lastContent = easyMDE.value();
			var currentContent = '';

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
	<div class="publicNotesBodyWrapper">

		<?php 
			echo $publicNotesUIRender->renderTopBar('../../', true, true, true);
		?>

		<div class="publicNotesMainContentWrapper textColorThemeGray styledText">
			<div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white; z-index: 99;">
				<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
				<img style="display: none; width: 2em;" src="../../images/ultiscape/etc/loading.gif" class="loadingGif">
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

									$accessOutput = '';

									if ($noteViewAuth == 'authorized') {
										$accessOutput .= 'This note is shared publicly. ';
									}
									if ($noteViewAuth == 'password') {
										$accessOutput .= 'You need a password to view this note. ';
									}

									if ($noteEditAuth == 'authorized') {
										$accessOutput .= 'Anyone can edit this note. ';
									}
									if ($noteEditAuth == 'password') {
										$accessOutput .= 'You need a password to edit this note. ';
									}

									echo '<div>';

										echo '<p>'.$accessOutput.'</p>';
										
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

						</form>
						
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<br class="desktopOnlyBlock">
						<span class="desktopOnlyBlock">
							<div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../images/ultiscape/etc/loading.gif" class="loadingGif">
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

						<span style="display: none;" id="deleteLoading"><img style="display: none; width: 2em;" src="../../images/ultiscape/etc/loading.gif" class="loadingGif"></span>
					</div>
				</div>
		</div>

		<?php
			echo $publicNotesUIRender->renderFooter('../../');
		?>

	</div>

</body>
<?php 
	echo $publicNotesUIRender->renderHtmlBottom('../../');
?>
