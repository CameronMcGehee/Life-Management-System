<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../../lib/etc/contactHeaderRedirect.php';
	contactHeaderRedirect('../../', '../../');

	require_once '../../../../lib/contactUIRender.php';
	$contactUIRender = new contactUIRender();

	// Other required libraries
	require_once '../../../../lib/table/contact.php';
	require_once '../../../../lib/table/invoice.php';
	require_once '../../../../lib/table/workspace.php';
	require_once '../../../../lib/table/docId.php';
	require_once '../../../../lib/render/etc/tagEditor.php';
	require_once '../../../../lib/table/invoiceItem.php';
	require_once '../../../../lib/table/payment.php';
	if (!isset($_GET['id']) || empty($_GET['id'])) {
		header("location: ../");
		exit();
	} else {
		$currentInvoice = new invoice($_GET['id']);
		$currentDocId = new docId($currentInvoice->docIdId);
	}

	if ($currentInvoice->workspaceId != $_SESSION['lifems_workspaceId']) {
        header("location: ../");
		exit();
    }

	$currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);

	if ($currentInvoice->existed) {
		if ((string)$currentWorkspace->docIdIsRandom == '1') {
			$titleName = 'Invoice '.$currentDocId->randomId;
		} else {
			$titleName = 'Invoice '.$currentDocId->incrementalId;
		}
	} else {
		header("location: ../");
		exit();
	}

	echo $contactUIRender->renderContactHtmlTop('../../../', htmlspecialchars($titleName), 'Edit '.htmlspecialchars($titleName).'.');
	echo $contactUIRender->renderContactUIMenuToggleScripts('../../../');

	// Generate all the needed authTokens for the page
	require_once '../../../../lib/table/authToken.php';

	$mainAuthToken = new authToken();
	$mainAuthToken->authName = 'editInvoice';
	$mainAuthToken->set();

	$payInvoiceAuthToken = new authToken();
	$payInvoiceAuthToken->authName = 'payInvoice';
	$payInvoiceAuthToken->set();

	$deletePaymentAuthToken = new authToken();
	$deletePaymentAuthToken->authName = 'deletePayment';
	$deletePaymentAuthToken->set();

	$deleteInvoiceAuthToken = new authToken();
	$deleteInvoiceAuthToken->authName = 'deleteInvoice';
	$deleteInvoiceAuthToken->set();

	$addItemAuthToken = new authToken();
	$addItemAuthToken->authName = 'addInvoiceItem';
	$addItemAuthToken->set();

	$deleteItemAuthToken = new authToken();
	$deleteItemAuthToken->authName = 'deleteInvoiceItem';
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
		var invoiceId ='<?php echo $currentInvoice->invoiceId; ?>';
		var formState;
		var url = new URL(window.location.href);

		var isNewInvoice = <?php if ($currentInvoice->existed) {echo 'false';} else {echo 'true';} ?>;
		var lastChange = new Date();

		var changesSaved = true;
		var waitingForError = false;
		var recordingPayment = false;
		var deleting = false;

		// RECORD PAYMENT BUTTON FUNCTIONS
		function payInvoiceButton() {
			// Save changes to avoid issues
			if (!changesSaved) {
				saveChanges();
				$("#payInvoicePrompt").fadeIn(300);
			} else {
				$("#payInvoicePrompt").fadeIn(300);
			}
		}
		function payInvoiceContinue() {
			if (!recordingPayment) {
				recordingPayment = true;
				// Run the script and reload
				$("#payInvoiceLoading").fadeIn(300);
				$("#scriptLoader").load("./scripts/async/payInvoice.script.php", {
					invoiceId: invoiceId,
					amount: $("#payInvoiceAmount").val(),
					method: $("#payInvoiceMethod").val(),
					notes: $("#payInvoiceNotes").val(),
					excessType: $("input:radio[name=payInvoiceExcessType]:checked").val(),
					payInvoiceAuthToken: '<?php echo $payInvoiceAuthToken->authTokenId; ?>'
				}, function () {
					scriptOutput = $("#scriptLoader").html();
					clearFormErrors();

					switch (scriptOutput) {
						case 'success':
								window.location.reload();
							break;
						default:
							recordingPayment = false;
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
		function payInvoiceCancel() {
			// Just hide the prompt
			$("#payInvoicePrompt").fadeOut(300);
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
				$("#scriptLoader").load("./scripts/async/deleteInvoice.script.php", {
					invoiceId: invoiceId,
					deleteInvoiceAuthToken: '<?php echo $deleteInvoiceAuthToken->authTokenId; ?>'
				}, function () {
					if ($("#scriptLoader").html() == 'success') {
						window.location.href = '../?popup=invoiceDeleted';
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

			function updateTotals() {

				if (discountAmount == '' || discountAmount == ' ' || grandTotal < 0) {
					$("#discount").val(0);
					discountAmount = 0;
				}
				
				var subTotal = 0;
				var discountIsPercent = <?php if ((string)$currentInvoice->discountIsPercent == '1') {echo 'true';} else {echo 'false';} ?>;
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

				$("#payInvoiceAmount").val(grandTotalOutput);

				// Payments

				runningTotal = parseFloat(grandTotalOutput);
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

			
			// Load contact billing address on load
			$("#billingAddressLoader").load("./includes/contactBillingAddress.inc.php", {
					contactId: '<?php echo htmlspecialchars($_SESSION['lifems_contactId']); ?>'
			});

			updateTotals();

			$("#invoiceForm :input").change(function () {
				inputChange();
			});

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

				<div class="twoColPage-Content-InfoSmall maxHeight">
					<div id="twoColContentWrapper" class="paddingLeftRight90 maxHeight" style="overflow: auto;">

						<form class="defaultForm" id="invoiceForm">

							<input type="hidden" name="mainAuthToken" id="mainAuthToken" value="<?php echo htmlspecialchars($mainAuthToken->authTokenId); ?>">

							<br>

							<?php
							
								if ($currentInvoice->existed) {
									echo '<div class="twoCol" style="width: 10em;">';
										echo '<span style="width: 9em;" class="smallButtonWrapper greenButton centered defaultMainShadows" onclick="payInvoiceButton()"><img style="height: 1.2em;" src="../../../images/lifems/icons/credit_card.svg"> Pay Invoice</span>';
									echo '</div>';

									echo '<br>';
								}
							
							?>

							<div class="twoCol">
								<div>
									<h3>Invoice #<?php if ((string)$currentWorkspace->docIdIsRandom == '1') {echo $currentDocId->randomId;} else {echo $currentDocId->incrementalId;} ?></h3>
								</div>
								<div style="text-align: right;">
									<p id="billingAddressLoader"></p>
								</div>
							</div>

							<br>

							<h3>Items</h3>

							<table class="defaultTable" style="width: 100%;" id="itemsTable">
								<tr id="tableHeader">
									<th class="la" style="text-decoration: underline;">Item</th>
									<th class="la" style="text-decoration: underline;">Price</th>
									<th class="la" style="text-decoration: underline;">Quantity</th>
									<th class="la" style="text-decoration: underline;">Sales Tax</th>
									<th class="la" style="text-decoration: underline;">Total</th>
								</tr>
								<tbody id="items">

								<?php

									$currentInvoice->pullItems("ORDER BY dateTimeAdded ASC");
									foreach ($currentInvoice->items as $itemId) {
										$currentItem = new invoiceItem($itemId);
										if ($currentItem->existed) {
											echo '<tr>
											<td>
												<span style="display: none; ">'.htmlspecialchars($itemId).'</span>
												<input readonly class="invisibleInput" style="height: 1.3em; width: 16em; max-width: 30vw; font-size: 1.3em;" type="text" name="itemName[]" value="'.htmlspecialchars($currentItem->name).'"> 
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
											<td class="tg-0lax"><span class="itemTotal"></span>
										</td></tr>';
										}
									}

								?>

								</tbody>

								<tr id="subTotalRow">
									<td style="border: none;" colspan="3"></td>
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

								<?php

									$currentInvoice->pullPayments("ORDER BY dateTimeAdded ASC");
									foreach ($currentInvoice->payments as $paymentId) {
										$currentPayment = new payment($paymentId);
										if ($currentPayment->existed) {
											$paymentDateOutput = new DateTime($currentPayment->dateTimeAdded);
											$paymentDateOutput = $paymentDateOutput->format('D, M d Y');
											echo '<tr class="paymentRow">
													<td style="border: none;" colspan="2"></td>
													<td style="background-color: #fff2e6;">Payment on '.htmlspecialchars($paymentDateOutput).'</td>
													<td style="background-color: #fff2e6;"><span class="paymentAmount" style="display: none;">'.htmlspecialchars(number_format($currentPayment->amount, 2, '.', ',')).'</span>-$'.htmlspecialchars(number_format($currentPayment->amount, 2, '.', ',')).'</td>
													<td style="background-color: #fff2e6;"><span class="paymentBalanceUpdate" style="font-size: 1.5em; color: green;">Loading...</span></td>
												</tr>';
										}
									}

								?>
							</table>

							<br>
							<div style="text-align: right;">
								<label for="discount"><p>Discount</p></label>
								<input readonly class="defaultInput" id="discount" type="number" step="0.01" name="discount" min="0" style="width: 5em;" value="<?php echo htmlspecialchars($currentInvoice->discount); ?>">
							</div>
							<br><br>

							<h3>Comments</h3>
							<div class="defaultInputGroup">
								<p>
								
									<?php

										if (!empty($currentInvoice->comments)) {
											echo htmlspecialchars($currentInvoice->comments);
										} else {
											echo "None";
										}
									
									?>
								
								</p>
							</div>
							<br><br>

						</form>
						
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">

						<h3>Other Info</h3>

						<?php
							$addedDate = new DateTime($currentInvoice->dateTimeAdded);
						?>

						<p>Invoiced on <?php echo $addedDate->format('D, M d Y'); ?></p>
					</div>
				</div>

				<div id="payInvoicePrompt" class="dimOverlay xyCenteredFlex" style="display: none;">
					<div class="popupMessageDialog" style="width: 30em;">
						<h3>Pay Invoice</h3>

						<br>
						<label for="payInvoiceAmount"><p>Amount</p></label>
						<input class="defaultInput" id="payInvoiceAmount" type="number" step="0.01" name="payInvoiceAmount" min="0.01" style="width: 5em;" value="5">
						<span id="payInvoiceAmountError" class="underInputError" style="display: none;"><br>Enter an amount greater than 0.01.</span>
						<br><br>

						<label for="payInvoiceMethod"><p style="display: inline;">Method</p> <a href="../../workspacesettings/#paymentmethods"><span style="font-size: .75em; width: 20em;" class="extraSmallButtonWrapper orangeButton">Edit Methods</span></a></label>
						<br>
							<?php

								// Select Payment Method

								require_once '../../../../lib/render/input/paymentMethodSelector.php';
								$paymentMethodSelector = new paymentMethodSelector("paymentMethodSelector", ["name" => 'payInvoiceMethod', "id" => 'payInvoiceMethod']);
								$paymentMethodSelector->render();
								echo $paymentMethodSelector->output;

							?>
						<br><br>

						<p>If amount is greater than invoice balance, </p>
						<input type="radio" name="payInvoiceExcessType" value="tip" id="tipRadio" checked="checked"><label for="tipRadio">Record as Tip</label>
						<br>
						<input type="radio" name="payInvoiceExcessType" value="credit" id="creditRadio"><label for="creditRadio">Add to My Account Credit</label>
						<span id="payInvoiceExcessTypeError" class="underInputError" style="display: none;"><br>Select an option.</span>

						<br><br>
						
						<label for="payInvoiceNotes"><p>Notes</p></label>
						<textarea class="defaultInput" style="font-size: 1.2em; width: 95%;" name="payInvoiceNotes" id="payInvoiceNotes"></textarea>
						
						<br><br>

						<div id="payInvoiceButtons" class="twoCol centered" style="width: 15em;">
							<div>
								<span id="payInvoiceContinueButton" class="smallButtonWrapper greenButton" onclick="payInvoiceContinue()">Continue <span style="display: none;" id="payInvoiceLoading"><img style="width: 1em;" src="../../../images/lifems/etc/loading.gif" class="loadingGif"></span></span>
							</div>

							<div>
								<span id="payInvoiceCancelButton" class="smallButtonWrapper redButton" onclick="payInvoiceCancel()">Cancel</span>
							</div>
						</div>

					</div>
				</div>
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
