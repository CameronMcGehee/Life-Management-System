<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/adminHeaderRedirect.php';
	adminHeaderRedirect('../', '../');

	require_once '../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => "Estimates",
		"pageDescription" => 'Create, edit, and view estimates.']);

	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

</head>

<body>
	<div style="display: none;" id="scriptLoader"></div>
	<div class="adminBodyWrapper">

		<?php
			echo $adminUIRender->renderAdminTopBar('../../');
		?>

		<?php
			echo $adminUIRender->renderAdminSideBar('../../');
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

						require_once '../../../lib/render/estimate/estimateTable.php';
						$estimateTable = new estimateTable('main', [
							'rootPathPrefix' => '../../',
							'maxRows' => 15,
							'showAdd' => true,
							'showSort' => true,
							'showBatch' => true,
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
			echo $adminUIRender->renderAdminFooter('../../');
		?>

		<?php 
			echo $adminUIRender->renderAdminMobileNavBar('../../');
		?>

	</div>

	<?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../');
	?>
</body>

<?php 
	echo $adminUIRender->renderAdminHtmlBottom('../../');
?>
