<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/customerHeaderRedirect.php';
	customerHeaderRedirect('../', '../');

	require_once '../../../lib/customerUIRender.php';
	$customerUIRender = new customerUIRender();

	echo $customerUIRender->rendercustomerHtmlTop('../../', 'Estimates', 'Create, edit, and view estimates.');

	echo $customerUIRender->rendercustomerUIMenuToggleScripts('../../');

?>

</head>

<body>
	<div style="display: none;" id="scriptLoader"></div>
	<div class="customerBodyWrapper">

		<?php
			echo $customerUIRender->rendercustomerTopBar('../../');
		?>

		<?php
			echo $customerUIRender->rendercustomerSideBar('../../');
		?>

		<?php
            require_once '../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', 'class' => 'styledText spacedText defaultMainShadows']);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

		<div class="cmsMainContentWrapper styledText textColorThemeEstimates">
			<div style="margin-left: 2em; margin-right: 2em;" class="spacedText">
				<h1>Estimates</h1>
			</div>

			<br>

			<div class="twoColPage-Content-Info">
				<div id="twoColContentWrapper" class="paddingLeftRight90">
					<?php

						require_once '../../../lib/render/estimate/customerEstimateTable.php';
						$estimateTable = new customerEstimateTable('main', [
							'rootPathPrefix' => '../../',
							'maxRows' => 15,
							'showAdd' => true,
							'showSort' => true,
							'showBatch' => false,
						]);
						$estimateTable->render();
						echo $estimateTable->output;

						require_once '../../../lib/render/etc/didYouKnowBox.php';
						$didYouKnowBox = new didYouKnowBox('estimatesPageDidYouKnowBox', ['rootPathPrefix' => '../../']);
						$didYouKnowBox->render();

					?>
				</div>

				<div id="twoColInfoWrapper" class="paddingLeftRight90">
				
					<?php
						echo $didYouKnowBox->output;
					?>

					<br><hr><br>

					<h3 style="color: var(--grayTextColorTheme);">Other Links</h3>

					<br>

					<a style="color: var(--grayTextColorTheme); display: inline-block;" href="./import">Import</a> | <a style="color: var(--grayTextColorTheme); display: inline-block;" href="./export">Export</a>

				</div>
			</div>
			<br>
		</div>
		
		<?php 
			echo $customerUIRender->rendercustomerFooter('../../');
		?>

		<?php 
			echo $customerUIRender->rendercustomerMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $customerUIRender->rendercustomerTopBarDropdowns('../../');
	?>
</body>

<?php 
	echo $customerUIRender->rendercustomerHtmlBottom('../../');
?>
