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

		<div class="cmsMainContentWrapper styledText textColorThemeJobs">

            <div class="desktopOnlyBlock" style="width: 100%; height: 80vh; overflow: scroll;">
                <?php

                    require_once '../../../lib/render/job/jobCalendar.php';
                    $jobCalendar = new jobCalendar('main', ['rootPathPrefix' => '../../', 'style' => 'width: 100%; height: 95%;']);
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
