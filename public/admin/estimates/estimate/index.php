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
	require_once '../../../../lib/table/estimate.php';
	require_once '../../../../lib/table/workspace.php';
	require_once '../../../../lib/table/docId.php';
	require_once '../../../../lib/render/etc/tagEditor.php';
	require_once '../../../../lib/table/estimateItem.php';
	require_once '../../../../lib/table/payment.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentEstimate = new estimate($_GET['id']);
		$currentDocId = new docId($currentEstimate->docIdId);
	} else {
		$currentEstimate = new estimate();
		$currentDocId = new docId($_SESSION['lifems_workspaceId']);
	}

	if ($currentEstimate->workspaceId != $_SESSION['lifems_workspaceId']) {
        header("location: ./");
		exit();
    }

	$currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);

	if ($currentEstimate->existed) {
		if ((string)$currentWorkspace->docIdIsRandom == '1') {
			$titleName = 'Estimate '.$currentDocId->randomId;
		} else {
			$titleName = 'Estimate '.$currentDocId->incrementalId;
		}
	} else {
		$titleName = 'New Estimate';
	}

	echo $adminUIRender->renderAdminHtmlTop('../../../', [
		"pageTitle" => htmlspecialchars($titleName),
		"pageDescription" => 'Edit '.htmlspecialchars($titleName).'.']);
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editEstimate';
	$mainAuthToken->set();

	$recordApprovalAuthToken = new authToken();
	$recordApprovalAuthToken->authName = 'recordApproval';
	$recordApprovalAuthToken->set();

	$removeApprovalAuthToken = new authToken();
	$removeApprovalAuthToken->authName = 'removeApproval';
	$removeApprovalAuthToken->set();

	$deleteEstimateAuthToken = new authToken();
	$deleteEstimateAuthToken->authName = 'deleteEstimate';
	$deleteEstimateAuthToken->set();

	$addItemAuthToken = new authToken();
	$addItemAuthToken->authName = 'addEstimateItem';
	$addItemAuthToken->set();

	$deleteItemAuthToken = new authToken();
	$deleteItemAuthToken->authName = 'deleteEstimateItem';
	$deleteItemAuthToken->set();

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
		var estimateId ='<?php echo $currentEstimate->estimateId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var isNewEstimate = <?php if ($currentEstimate->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();

		var changesSaved = true;
		var waitingForError = false;
		var workingOnApproval = false;
		var deleting = false;

		// RECORD APPROVAL BUTTON FUNCTIONS
		function recordApprovalButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				saveChanges();
				$("#recordApprovalPrompt").fadeIn(300);
			} else {
				$("#recordApprovalPrompt").fadeIn(300);
			}
		}
		function recordApprovalYes() {
			if (!workingOnApproval) {
				workingOnApproval = true;
				// Run the script and reload
				$("#recordApprovalLoading").fadeIn(300);
				$("#scriptLoader").load("./scripts/async/recordApproval.script.php", {
					estimateId: estimateId,
					reason: $("#recordApprovalReason").val(),
					recordApprovalAuthToken: '<?php echo $recordApprovalAuthToken->authTokenId; ?>'
				}, function () {
					scriptOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (scriptOutput) {
						case 'success':
								window.location.reload();
							break;
						default:
							workingOnApproval = false;
							showFormError("#"+scriptOutput+"Error", "#"+scriptOutput);
							$("#"+scriptOutput).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							break;
					}
				});
			}
		}
		function recordApprovalCancel() {
			// Just hide the prompt
			$("#recordApprovalPrompt").fadeOut(300);
		}

		// REMOVE APPROVAL BUTTON FUNCTIONS
		function removeApprovalButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				saveChanges();
				$("#removeApprovalPrompt").fadeIn(300);
			} else {
				$("#removeApprovalPrompt").fadeIn(300);
			}
		}
		function removeApprovalYes() {
			if (!workingOnApproval) {
				workingOnApproval = true;
				// Run the script and reload
				$("#removeApprovalLoading").fadeIn(300);
				$("#scriptLoader").load("./scripts/async/removeApproval.script.php", {
					estimateId: estimateId,
					removeApprovalAuthToken: '<?php echo $removeApprovalAuthToken->authTokenId; ?>'
				}, function () {
					scriptOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (scriptOutput) {
						case 'success':
								window.location.reload();
							break;
						default:
							workingOnApproval = false;
							showFormError("#"+scriptOutput+"Error", "#"+scriptOutput);
							$("#"+scriptOutput).shake(50);

							$('.loadingGif').each(function() {
								$(this).fadeOut(100);
							});
							break;
					}
				});
			}
		}
		function removeApprovalNo() {
			// Just hide the prompt
			$("#removeApprovalPrompt").fadeOut(300);
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
				$("#scriptLoader").load("./scripts/async/deleteEstimate.script.php", {
					estimateId: estimateId,
					deleteEstimateAuthToken: '<?php echo $deleteEstimateAuthToken->authTokenId; ?>'
				}, function () {
					if ($("#scriptLoader").html() == 'success') {
						window.location.href = '../?popup=estimateDeleted';
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

			var updatePaymentValue = true;

			if (isNewEstimate) {
				$("#firstLastName").focus();
			}

			$("#estimateForm").submit(function(event) {
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
			}

			if ($.isNumeric(url.searchParams.get('wsl'))) {
				$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			}

			function saveChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				
				formData = $("#estimateForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editEstimate.script.php", {
					estimateId: estimateId,
					formData: formData
				}, function () {
					scriptOutput = $("#scriptLoader").html().split(":::");
					estimateId = scriptOutput[0];
					formState = scriptOutput[1];
					clearFormErrors();

					switch (formState) {
						case 'success':
							setSaved();
							if (isNewEstimate) {
								isNewEstimate = false;
								window.history.pushState("string", 'LifeMS (Admin) - New Estimate', "./?id="+estimateId);
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

			function updateTotals() {

				if (discountAmount == '' || discountAmount == ' ' || grandTotal < 0) {
					$("#discount").val(0);
					discountAmount = 0;
				}
				
				var subTotal = 0;
				var discountIsPercent = <?php if ((string)$currentEstimate->discountIsPercent == '1') {echo 'true';} else {echo 'false';} ?>;
				var totalTax = 0;
				var grandTotal = 0;
				var discountAmount = 0;

				$(".itemTotal").each(function () {
					var price = 0;
					var quantity = 0;
					var salesTax = 0;
					var itemTotalSalesTax = 0;
					var total = 0;

					price = $(this).closest('tr').find('input[name="itemPrice[]"').val();
					quantity = $(this).closest('tr').find('input[name="itemQuantity[]"').val();
					salesTaxInput = $(this).closest('tr').find('input[name="itemTax[]"').val();

					salesTax = salesTaxInput / 100;
					itemTotalSalesTax = (price * quantity) * (1 + (salesTax)) - (price * quantity);
					
					total = (price * quantity) * (1 + (salesTax));

					total = "$" + total.toFixed(2);
					$(this).html(total);

					totalTax = totalTax + itemTotalSalesTax;
					subTotal = subTotal + (price * quantity);
				});

				totalTaxOutput = totalTax.toFixed(2);
				subTotalOutput = subTotal.toFixed(2);
				grandTotal = subTotal + totalTax;

				if (discountIsPercent == false) {
					grandTotal = grandTotal - $("#discount").val();
					$("#discountOutput").html("-$" + $("#discount").val());
				} else {
					grandTotal = grandTotal * (1 - ($("#discount").val()/100));
					$("#discountOutput").html("-$" + (grandTotal * (($("#discount").val()/100))).toFixed(2));
				}
				
				grandTotalOutput = grandTotal.toFixed(2);

				$("#totalTax").html("$" + totalTaxOutput);
				$("#subTotal").html("$" + subTotalOutput);
				$("#grandTotal").html("$" + grandTotalOutput);

				if (updatePaymentValue) {
					$("#recordApprovalAmount").val(grandTotalOutput);
					updatePaymentValue = false;
				}

				// Payments

				runningTotal = grandTotalOutput;
				$(".paymentRow").each(function (i, el) {
					$("#grandTotal").css('text-decoration', 'line-through');
					// Define elements
					paymentAmount = parseFloat($(el).find('.paymentAmount').html());
					runningTotal = runningTotal - paymentAmount;
					$(el).find('.paymentBalanceUpdate').html("$" + runningTotal.toFixed(2));

					// If not the last row (not last payment, cross out total above it)

					if (!$(el).closest('tr').is(':last-child')) {
						$(el).find('.paymentBalanceUpdate').css('text-decoration', 'line-through');
					} else {
						$(el).find('.paymentBalanceUpdate').css('font-weight', 'bold');

					}
				});

				if ($('.paymentRow').length == 0) {
					$("#grandTotal").css('text-decoration', 'none');
					$("#grandTotal").css('font-weight', 'bold');
				}

			}

			function registerItemDeleteButtonClicks() {
				$("span[id*='deleteItem:::']").each(function (i, el) {
					$(el).on('click', function(e) {
						currentId = this.id.split(":::")[1];
						$.post("./scripts/async/deleteItem.script.php", {
							itemId: currentId,
							deleteItemAuthToken: '<?php echo $deleteItemAuthToken->authTokenId; ?>'
						}, function () {
							// find the closest <tr> to the delete button and remove it.
							$(el).closest('tr').remove();
						});
					});
				});
			}

			registerItemDeleteButtonClicks();

			$("#add").click(function(event) {

				if (isNewEstimate) {
					saveChanges();
				} else {
					$('.addItemLoadingGif').fadeIn(100);

					// set a new item with script, and then add it to the list with it's Id

					setTimeout(() => {
						$("#scriptLoader").load("./scripts/async/addItem.script.php", {
							estimateId: estimateId,
							addItemAuthToken: '<?php echo $addItemAuthToken->authTokenId; ?>'
						}, function () {
							scriptOutput = $("#scriptLoader").html().split(":::");
							itemId = scriptOutput[0];
							formState = scriptOutput[1];

							switch (formState) {
								case 'success':

									// Append item to list
									$("#items").append('<tr><td><input type="hidden" name="itemId[]" value="' + itemId + '"><input class="invisibleInput" style="height: 1.3em; width: 16em; font-size: 1.3em;" type="text" name="itemName[]"> <span id="deleteItem:::' + itemId + '" class="smallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../../images/lifems/icons/trash.svg"></span></td><td class="tg-0lax"><input class="invisibleInput" style="height: 1.3em; width: 5em; font-size: 1.3em;" type="number" step="0.01" name="itemPrice[]"  min="0" style="width: 5em;" value="25"></td><td class="tg-0lax"><input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemQuantity[]" min="1" style="width: 5em;" value="1"></td><td class="tg-0lax"><input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemTax[]" min="0" max="100"style="width: 5em;" value="0"><label for="itemTax">%</label></td><td class="tg-0lax"><span class="itemTotal"></span></td></tr>');
									updateTotals();

									// Make sure the new inputs update the changes tracker
									$("#estimateForm :input").change(function () {
										inputChange();
									});
									registerItemDeleteButtonClicks()

									break;
								default:
									break;
							}

							$('.addItemLoadingGif').fadeOut(100);
						});
					}, 300);
				}
				
			});

			window.setInterval(updateTotals, 500);

			$("#estimateForm :input").change(function () {
				inputChange();
			});

			$("#estimateForm :input[name=contact]").change(function () {
				// Show the contact's billing address
				contactId = $(":input[name=contact]").val();
				$("#billingAddressLoader").load("./includes/contactBillingAddress.inc.php", {
					contactId: contactId
				});
			});

			$("#billingAddressLoader").load("./includes/contactBillingAddress.inc.php", {
				contactId: $(":input[name=contact]").val()
			});
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

				<div class="twoColPage-Content-InfoSmall maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<form class="defaultForm" id="estimateForm">

							<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">

							<br>

							<?php

								if ($currentEstimate->dateTimeApproved != NULL) {

									if ($currentEstimate->approvedByAdminId != NULL) {
										// Get the admin's name
										$currentAdmin = new admin($currentEstimate->approvedByAdminId);
										if ($currentEstimate->approvedByAdminId == $_SESSION['lifems_adminId']) {
											$approvalNameOutput = '<a href="../../myprofile">You</a>';
										} elseif ($currentAdmin->existed) {
											$approvalNameOutput = '<a href="../../profile?id='.htmlspecialchars(strval($currentEstimate->approvedByAdminId)).'">'.htmlspecialchars($currentAdmin->firstName).' '.htmlspecialchars($currentAdmin->lastName).'</a>';
										} else {
											$approvalNameOutput = 'an admin';
										}
									} else {
										$approvalNameOutput = '<a href="../../contacts/contact?id='.htmlspecialchars(strval($currentEstimate->contactId)).'">the contact</a>';
									}

									$approvalDateOutput = new DateTime($currentEstimate->dateTimeApproved);
									$approvalDateOutput = $approvalDateOutput->format('D, M d Y');
									echo '<div class="defaultInputGroup twoCol" style="grid-template-columns: min-content auto; background-color: rgb(179, 255, 179);">
											<div><img style="height: 3em;" src="../../../images/lifems/icons/thumbs_up.svg"></div>
											<div>
											<p style="display: inline;">This estimate was approved on <b>'.$approvalDateOutput.'</b> by <b>'.$approvalNameOutput.'</b>.</p>';

											if ($currentEstimate->adminReason != NULL) {
												echo '<p>Reason for admin approval: <b>'.htmlspecialchars(strval($currentEstimate->adminReason)).'</b></p>';
											}
									echo '</div></div>
									<br>';
								}
							
								if ($currentEstimate->existed) {
									echo '<div class="twoCol" style="width: 23em;">';
										if ($currentEstimate->dateTimeApproved == NULL) {
											echo '<span style="width: 9em;" class="smallButtonWrapper greenButton centered defaultMainShadows" onclick="recordApprovalButton()"><img style="height: 1.2em;" src="../../../images/lifems/icons/thumbs_up.svg"> Admin Approve</span>';
										} else {
											echo '<span style="width: 11em;" class="smallButtonWrapper orangeButton centered defaultMainShadows" onclick="removeApprovalButton()"><img style="height: 1.2em;" src="../../../images/lifems/icons/thumbs_down.svg"> Remove Approval</span>';
										}
										echo '<span style="width: 5em;" class="smallButtonWrapper redButton centered defaultMainShadows" onclick="deleteButton()"><img style="height: 1.2em;" src="../../../images/lifems/icons/trash.svg"> Delete</span>';
									echo '</div>';

									echo '<br>';
								}
							
							?>

							<h3>Estimate</h3>

							<div class="twoCol">
								<div>
									<label for="docId"><p>Doc ID</p></label>
									<input <?php if ($currentEstimate->dateTimeApproved != NULL) { echo 'readonly ';} ?>class="defaultInput" id="docId" type="number" step="1" name="docId" min="0" style="width: 5em;" value="<?php if ((string)$currentWorkspace->docIdIsRandom == '1') {echo $currentDocId->randomId;} else {echo $currentDocId->incrementalId;}  ?>">
									<span id="docIdError" class="underInputError" style="display: none;"><br>Input a valid Id.</span>	
								</div>
								<div style="text-align: right;">
									<label for="contactSelector"><p>Contact</p></label>
									<!-- Select contact dialog -->
									<?php
									
										require_once '../../../../lib/render/input/contactSelector.php';

										if ($currentEstimate->dateTimeApproved != NULL) {
											$contactReadonly = true;
										} else {
											$contactReadonly = false;
										}
										$contactSelector = new contactSelector("contactSelector", ["readonly" => $contactReadonly, "name" => 'contact', "selectedId" => $currentEstimate->contactId]);
										$contactSelector->render();
										echo $contactSelector->output;

									?>
									<span id="contactError" class="underInputError" style="display: none;"><br>Select a contact.</span>
									<p id="billingAddressLoader"></p>
								</div>
							</div>

							<br>

							<h3>Items</h3>

							<table class="defaultTable" style="width: 100%;" id="itemsTable">
								<tr id="tableHeader">
									<td style="text-decoration: underline;">Item</td>
									<td style="text-decoration: underline;">Price</td>
									<td style="text-decoration: underline;">Quantity</td>
									<td style="text-decoration: underline;">Sales Tax</td>
									<td style="text-decoration: underline;">Total</td>
								</tr>
								<tbody id="items">

								<?php

									$currentEstimate->pullItems("ORDER BY dateTimeAdded ASC");
									foreach ($currentEstimate->items as $itemId) {
										$currentItem = new estimateItem($itemId);
										if ($currentItem->existed) {
											if ($currentEstimate->dateTimeApproved != NULL) {
												echo '<tr>
														<td>
															<input type="hidden" name="itemId[]" value="'.htmlspecialchars($itemId).'"><input readonly class="invisibleInput" style="height: 1.3em; width: 16em; max-width: 30vw; font-size: 1.3em;" type="text" name="itemName[]" value="'.htmlspecialchars($currentItem->name).'"> 
														</td>
														<td class="tg-0lax">
															<input readonly class="invisibleInput" style="height: 1.3em; width: 5em; font-size: 1.3em;" type="number" step="0.01" name="itemPrice[]" value="'.htmlspecialchars($currentItem->price).'" min="0" style="width: 5em;" value="25">
														</td>
														<td class="tg-0lax">
															<input readonly class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemQuantity[]" value="'.htmlspecialchars($currentItem->quantity).'" min="1" style="width: 5em;" value="1">
														</td>
														<td class="tg-0lax">
															<input readonly class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemTax[]" value="'.htmlspecialchars($currentItem->tax).'" min="0" max="100"style="width: 5em;" value="0"><label for="itemTax">%</label>
														</td>
														<td class="tg-0lax">
															<span class="itemTotal"></span>
														</td>
													</tr>';
											} else {
												echo '<tr>
														<td>
															<input type="hidden" name="itemId[]" value="'.htmlspecialchars($itemId).'"><input class="invisibleInput" style="height: 1.3em; width: 16em; max-width: 30vw; font-size: 1.3em;" type="text" name="itemName[]" value="'.htmlspecialchars($currentItem->name).'"> 
															<span id="deleteItem:::'.htmlspecialchars($itemId).'" class="smallButtonWrapper orangeButton xyCenteredFlex" style="width: 1em; display: inline;"><img style="height: 1em;" src="../../../images/lifems/icons/trash.svg"></span>
														</td>
														<td class="tg-0lax">
															<input class="invisibleInput" style="height: 1.3em; width: 5em; font-size: 1.3em;" type="number" step="0.01" name="itemPrice[]" value="'.htmlspecialchars($currentItem->price).'" min="0" style="width: 5em;" value="25">
														</td>
														<td class="tg-0lax">
															<input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemQuantity[]" value="'.htmlspecialchars($currentItem->quantity).'" min="1" style="width: 5em;" value="1">
														</td>
														<td class="tg-0lax">
															<input class="invisibleInput" style="height: 1.3em; width: 3em; font-size: 1.3em;" type="number" step="any" name="itemTax[]" value="'.htmlspecialchars($currentItem->tax).'" min="0" max="100"style="width: 5em;" value="0"><label for="itemTax">%</label>
														</td>
														<td class="tg-0lax">
															<span class="itemTotal"></span>
														</td>
													</tr>';
											}
											
										}
									}

								?>

								</tbody>

								<tr id="subTotalRow">
									<?php

										if ($currentEstimate->dateTimeApproved != NULL) {
											echo '<td colspan="3">You cannot alter, add or remove items once an estimate has been approved.</td>';
										} else {
											echo '<td colspan="3"><a href="#" id="add">Add Item</a><img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="addItemLoadingGif"></td>';
										}

									?>
									<td style="text-decoration: underline; border-top-width: 2px; border-left-width: 2px; border-left-color: green; border-top-color: green;">Subtotal:</td>
									<td id="subTotal" style="border-top-width: 2px; border-top-color: green;">$0</td>
								</tr>

								<tr id="totalTaxRow">
									<td style="border: none;" colspan="3"></td>
									<td style="text-decoration: underline; border-left-width: 2px; border-left-color: green;">Total Tax:</td>
									<td id="totalTax">$0</td>
								</tr>

								<tr id="discountRow">
									<td style="border: none;" colspan="3"></td>
									<td style="text-decoration: underline; border-left-width: 2px; border-left-color: green;">Discount:</td>
									<td id="discountOutput">-$0</td>
								</tr>

								<tr id="grandTotalRow">
									<td style="border: none;" colspan="3"></td>
									<td style="text-decoration: underline; border-left-width: 2px; border-left-color: green;"><b>Grand Total:</b></td>
									<td><span style="font-size: 1.5em; color: green;" id="grandTotal">$0</span></td>
								</tr>
							</table>

							<br>
							<div style="text-align: right;">
								<label for="discount"><p>Discount</p></label>
								<input <?php if ($currentEstimate->dateTimeApproved != NULL) { echo 'readonly ';} ?>class="defaultInput" id="discount" type="number" step="0.01" name="discount" min="0" style="width: 5em;" value="<?php echo htmlspecialchars(strval($currentEstimate->discount)); ?>">
							</div>
							<br><br>

							<h3>Notes</h3>
							<div class="defaultInputGroup">
								<label for="notes"><p>Comments (included on estimate)</p></label>
								<textarea <?php if ($currentEstimate->dateTimeApproved != NULL) { echo 'readonly ';} ?>class="defaultInput" style="font-size: 1.2em; width: 95%;" name="comments" id="comments"><?php echo htmlspecialchars(strval($currentEstimate->comments)); ?></textarea>

								<br><br>
								
								<label for="notes"><p>Private (to Admins)</p></label>
								<textarea <?php if ($currentEstimate->dateTimeApproved != NULL) { echo 'readonly ';} ?>class="defaultInput" style="font-size: 1.2em; width: 95%;" name="privateNotes" id="privateNotes"><?php echo htmlspecialchars(strval($currentEstimate->privateNotes)); ?></textarea>
							</div>
							<br><br>

						</form>
						
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
							$addedDate = new DateTime($currentEstimate->dateTimeAdded);
						?>

						<p>Added on <?php echo $addedDate->format('D, M d Y'); ?></p>
					</div>
				</div>

				<div id="deletePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog">
						<h3>Delete Estimate?</h3>
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

						<span style="display: none;" id="deleteLoading"><img style="display: none; width: 2em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span>
					</div>
				</div>

				<div id="recordApprovalPrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog" style="width: 30em;">
						<h3>Record Admin Approval</h3>

						<br>
						
						<label for="recordApprovalReason"><p>Reason for admin approval</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 95%;" name="recordApprovalReason" id="recordApprovalReason"></textarea>
						
						<br><br>

						<div id="recordApprovalButtons" class="twoCol centered" style="width: 15em;">
							<div>
								<span id="recordApprovalYesButton" class="smallButtonWrapper greenButton" onclick="recordApprovalYes()">Record <span style="display: none;" id="recordApprovalLoading"><img style="width: 1em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span></span>
							</div>

							<div>
								<span id="recordApprovalCancelButton" class="smallButtonWrapper redButton" onclick="recordApprovalCancel()">Cancel</span>
							</div>
						</div>

					</div>
				</div>

				<div id="removeApprovalPrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog" style="width: 30em;">
						<h3>Remove Approval?</h3>
						<p>If the contact has approved this estimate, the approval status will be reset!</p>
						<br>

						<div id="removeApprovalButtons" class="twoCol centered" style="width: 15em;">
							<div>
								<span id="removeApprovalYesButton" class="smallButtonWrapper greenButton" onclick="removeApprovalYes()">Yes <span style="display: none;" id="removeApprovalLoading"><img style="width: 1em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span></span>
							</div>

							<div>
								<span id="removeApprovalNoButton" class="smallButtonWrapper redButton" onclick="removeApprovalNo()">No</span>
							</div>
						</div>

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
