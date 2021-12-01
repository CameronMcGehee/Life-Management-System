<?php

	// Start Session
	require_once '../../../php/startSession.php';

	// This is the business select page so if we are not signed in, just redirect to the login page

	if (!isset($_SESSION['ultiscape_adminId'])) {
		header("location: ../login");
	}

	require_once '../../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	// Other required libraries
	require_once '../../../../lib/table/admin.php';
	require_once '../../../../lib/table/customer.php';
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$currentCustomer = new customer($_GET['id']);
	} else {
		$currentCustomer = new customer();
	}

	if ($currentCustomer->existed) {
		$titleName = $currentCustomer->firstName.' '.$currentCustomer->lastName;
	} else {
		$titleName = 'New Customer';
	}

	require_once '../../../../lib/timezones/Timezones.php';

	echo $adminUIRender->renderAdminHtmlTop('../../../', htmlspecialchars($titleName), 'Edit your UltiScape business.');
	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

?>

	<script src="../../../js/etc/animation/shake.js"></script>

	<script src="../../../js/etc/form/showFormError.js"></script>
	<script src="../../../js/etc/form/clearFormErrors.js"></script>

	<script>

		var formData;
		var formOutput;
		var url = new URL(window.location.href);

		var lastChange = new Date();
		var changesSaved = true;

		$(function() {

			function setUnsaved() {
				$(".changesMessage").each(function () {
					$(this).html('<span style="color: gray;">⏳ Waiting to save changes...</span>');
				});
				$(".changesMessage").each(function () {
					$(this).shake(50);
				});
				changesSaved = false;
			}

			if ($.isNumeric(url.searchParams.get('wsl'))) {
				$(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
			}

			function saveChanges() {
				$('.loadingGif').each(function() {
					$(this).fadeIn(100);
				});
				formData = $("#customerForm").serialize();
				
				$("#scriptLoader").load("./scripts/async/editcustomer.script.php", {
					formData: formData
				}, function () {
					formOutput = $("#scriptLoader").html();
					clearFormErrors();
					

					if (formOutput == 'success') {
						$(".changesMessage").each(function () {
							$(this).html('<span style="color: green;">Up to date ✔</span>');
						});
						changesSaved = true;
					} else {
						showFormError("#"+formOutput+"Error", "#"+formOutput);
						$("#"+formOutput).shake(50);
					}

					$('.loadingGif').each(function() {
					$(this).fadeOut(100);
				});
				});
				changesSaved = true;
			}
			
			$("#customerForm input, #customerForm select").change(function () {
				setUnsaved();
				lastChange = new Date();
			});


			var interval = setInterval(function() {
				if (changesSaved == false && (new Date() - lastChange) / 1000 > 2) {
					saveChanges();
				}
			}, 1000);

			window.onbeforeunload = function() {
				if (changesSaved == false) {
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
			<form class="defaultForm" action="./" method="POST" id="customerForm">

				<div class="twoColPage-Content-Info">
					<div id="twoColContentWrapper" class="paddingLeftRight90 paddingTopBottom90" style="overflow: auto;">

					<?php

						if (empty($currentCustomer->lastName)) {
							$nameOutput = $currentCustomer->firstName;
						} else {
							$nameOutput = $currentCustomer->firstName.' '.$currentCustomer->lastName;
						}

					?>

					<input class="bigInput" style="width: 93%;" type="text" name="firstLastName" id="firstLastName" placeholder="Name..." value="<?php echo htmlspecialchars($nameOutput); ?>">
					<span id="firstLastNameError" class="underInputError" style="display: none;"><br>Please enter a name, preferrably first and last.</span>

					<br><br>

					<table class="defaultTable" style="width: min-content;">
						<tr>
							<td class="defaultTableCell" style="padding: 1em;">Billing Address</td>
							<td class="defaultTableCell" style="padding: 1em;">
								<div>
									<label for="billAddress1"><p>Address Line 1</p></label>
									<input class="almostInvisibleInput" style="font-size: 1.2em;" type="text" name="billAddress1" id="billAddress1" value="<?php echo htmlspecialchars($currentCustomer->billAddress1); ?>">
									<br><br>

									<label for="billAddress2"><p>Address Line 2</p></label>
									<input class="almostInvisibleInput" style="font-size: 1.2em;" type="text" name="billAddress2" id="billAddress2" value="<?php echo htmlspecialchars($currentCustomer->billAddress2); ?>">
									<br><br>

									<label for="billCity"><p>City</p></label>
									<input class="almostInvisibleInput" style="font-size: 1.2em;" type="text" name="billCity" id="billCity" value="<?php echo htmlspecialchars($currentCustomer->billCity); ?>">
									<br><br>

									<label for="billState"><p>State</p></label>
									<input class="almostInvisibleInput" style="font-size: 1.2em;" type="text" name="billState" id="billState" value="<?php echo htmlspecialchars($currentCustomer->billState); ?>">

									<span id="billAddressError" class="underInputError" style="display: none;"><br>Please enter a name, preferrably first and last.</span>
								</div>
							</td>
						</tr>
					</table>

					<br><br>

					<div>
						<input class="defaultInput" type="checkbox" name="overrideCreditAlertIsEnabled" id="overrideCreditAlertIsEnabled" <?php if ($currentCustomer->overrideCreditAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideCreditAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when credit is less than or equal to</p></label>
						<br>
						<input class="defaultInput" type="number" name="overrideCreditAlertAmount" id="overrideCreditAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentCustomer->overrideCreditAlertAmount, 2)); ?>" style="width: 5em;">
						<span id="overrideCreditAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
					</div>
					<br>
					<div>
						<input class="defaultInput" type="checkbox" name="overrideBalanceAlertIsEnabled" id="overrideBalanceAlertIsEnabled" <?php if ($currentCustomer->overrideBalanceAlertIsEnabled == '1') {echo 'checked="checked"';} ?>><label for="overrideBalanceAlertIsEnabled"> <p style="display: inline; clear: both;">Alert Customer when balance is greater than or equal to</p></label>
						<br>
						<input class="defaultInput" type="number" name="overrideBalanceAlertAmount" id="overrideBalanceAlertAmount" placeholder="$100" min="0.00" step="0.01" value="<?php echo htmlspecialchars(number_format($currentCustomer->overrideBalanceAlertAmount, 2)); ?>" style="width: 5em;">
						<span id="overrideBalanceAlertAmountError" class="underInputError" style="display: none;"><br>Input a number.</span>
					</div>
					
					</div>

					<div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
						<span class="desktopOnlyBlock">
							<div c style="width: min-content;"lass="changesMessage"><span style="color: green;">Up to date ✔</span></div>
							<img style="display: none; width: 2em;" src="../../../images/ultiscape/etc/loading.gif" class="loadingGif">
						</span>

					</div>
				</div>

				<br><br>
							
				<?php
					// Generate an auth token for the form
					require_once '../../../../lib/table/authToken.php';
					$token = new authToken();
					$token->authName = 'editcustomer';
					$token->set();
				?>

				<input type="hidden" name="authToken" id="authToken" value="<?php echo htmlspecialchars($token->authTokenId); ?>">
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
