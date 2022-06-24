<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/customerHeaderRedirect.php';
	customerHeaderRedirect('../', '../');

	require_once '../../../lib/customerUIRender.php';
	$customerUIRender = new customerUIRender();

	echo $customerUIRender->renderCustomerHtmlTop('../../', 'Invoices', 'Create, edit, and view invoices.');

	echo $customerUIRender->renderCustomerUIMenuToggleScripts('../../');

?>

</head>

<body>
	<div style="display: none;" id="scriptLoader"></div>
	<div class="customerBodyWrapper">

		<?php
			echo $customerUIRender->renderCustomerTopBar('../../');
		?>

		<?php
			echo $customerUIRender->renderCustomerSideBar('../../');
		?>

		<?php
            require_once '../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', 'class' => 'styledText spacedText defaultMainShadows']);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

		<div class="cmsMainContentWrapper styledText textColorThemeInvoices">
			<div style="margin-left: 2em; margin-right: 2em;" class="spacedText">
				<h1>Invoices</h1>
			</div>

			<br>

			<div class="twoColPage-Content-Info">
				<div id="twoColContentWrapper" class="paddingLeftRight90">
					<?php

						require_once '../../../lib/render/invoice/customerInvoiceTable.php';
						$customerInvoiceTable = new customerInvoiceTable('main', [
							'rootPathPrefix' => '../../',
							'maxRows' => 15,
							'showSort' => true,
							'showBatch' => false,
						]);
						$customerInvoiceTable->render();
						echo $customerInvoiceTable->output;

					?>
				</div>

				<div id="twoColInfoWrapper" class="paddingLeftRight90">

					<h3 style="color: var(--grayTextColorTheme);">Other Links</h3>

					<br>

					<a style="color: var(--grayTextColorTheme); display: inline-block;" href="./import">Import</a> | <a style="color: var(--grayTextColorTheme); display: inline-block;" href="./export">Export</a>

				</div>
			</div>
			<br>
		</div>
		
		<?php 
			echo $customerUIRender->renderCustomerFooter('../../');
		?>

		<?php 
			echo $customerUIRender->renderCustomerMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $customerUIRender->renderCustomerTopBarDropdowns('../../');
	?>
</body>

<?php 
	echo $customerUIRender->renderCustomerHtmlBottom('../../');
?>
