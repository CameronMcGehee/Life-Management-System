<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/adminHeaderRedirect.php';
	adminHeaderRedirect('../', '../');

	require_once '../../../lib/adminUIRender.php';
	$adminUIRender = new adminUIRender();

	echo $adminUIRender->renderAdminHtmlTop('../../', 'Jobs', 'Create, edit, and view jobs.');

	echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

</head>

<body>
	<div style="display: none;" id="scriptLoader"></div>
	<div class="cmsBodyWrapper">

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

		<div class="cmsMainContentWrapper styledText textColorThemeJobs">

            <div class="desktopOnlyBlock" style="width: 100%; height: 90%;">
                <?php

					if (isset($_GET['main-m'])) {
						$selectedMonth = $_GET['main-m'];
					} else {
						$currentDate = new DateTime();
						$selectedMonth = $currentDate->format('Y-m');
					}

                    require_once '../../../lib/render/job/jobCalendar.php';
                    $jobCalendar = new jobCalendar('main', ['rootPathPrefix' => '../../', 'month' => $selectedMonth, 'style' => 'width: 100%; height: 100%;']);
                    $jobCalendar->render();
                    echo $jobCalendar->output;

                ?>
            </div>

            <div class="mobileOnlyBlock">
                <p>Mobile Jobs List....</p>
            </div>
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
