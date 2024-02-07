<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/contactHeaderRedirect.php';
	contactHeaderRedirect('../', '../');

	require_once '../../../lib/contactUIRender.php';
	$contactUIRender = new contactUIRender();

	echo $contactUIRender->renderContactHtmlTop('../../', 'Invoices', 'Create, edit, and view invoices.');

	echo $contactUIRender->renderContactUIMenuToggleScripts('../../');

?>

</head>

<body>
	<div style="display: none;" id="scriptLoader"></div>
	<div class="contactBodyWrapper">

		<?php
			echo $contactUIRender->renderContactTopBar('../../');
		?>

		<?php
			echo $contactUIRender->renderContactSideBar('../../');
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

						require_once '../../../lib/render/invoice/contactInvoiceTable.php';
						$contactInvoiceTable = new contactInvoiceTable('main', [
							'rootPathPrefix' => '../../',
							'maxRows' => 15,
							'showSort' => true,
							'showBatch' => false,
						]);
						$contactInvoiceTable->render();
						echo $contactInvoiceTable->output;

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
			echo $contactUIRender->renderContactFooter('../../');
		?>

		<?php 
			echo $contactUIRender->renderContactMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $contactUIRender->renderContactTopBarDropdowns('../../');
	?>
</body>

<?php 
	echo $contactUIRender->renderContactHtmlBottom('../../');
?>
