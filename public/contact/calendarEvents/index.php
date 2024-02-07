<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/contactHeaderRedirect.php';
	contactHeaderRedirect('../', '../');

	require_once '../../../lib/contactUIRender.php';
	$contactUIRender = new contactUIRender();

	echo $contactUIRender->renderContactHtmlTop('../../', 'CalendarEvents', 'Create, edit, and view calendarEvents.');

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

		<div class="cmsMainContentWrapper styledText textColorThemeCalendarEvents">

            <div class="desktopOnlyBlock" style="width: 100%; height: 90%;">
                <?php

					if (isset($_GET['main-m'])) {
						$selectedMonth = $_GET['main-m'];
					} else {
						$currentDate = new DateTime();
						$selectedMonth = $currentDate->format('Y-m');
					}

                    require_once '../../../lib/render/calendarEvent/contactCalendarEventCalendar.php';
                    $contactCalendarEventCalendar = new contactCalendarEventCalendar('main', ['rootPathPrefix' => '../../', 'month' => $selectedMonth, 'style' => 'width: 100%; height: 100%;']);
                    $contactCalendarEventCalendar->render();
                    echo $contactCalendarEventCalendar->output;

                ?>
            </div>

            <div class="mobileOnlyBlock">
                <p>Mobile CalendarEvents List....</p>
            </div>
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
