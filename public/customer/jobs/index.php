<?php

	// Start Session
	require_once '../../php/startSession.php';

	// If not signed in, just redirect to the login page
	require_once '../../../lib/etc/customerHeaderRedirect.php';
	customerHeaderRedirect('../', '../');

	require_once '../../../lib/customerUIRender.php';
	$customerUIRender = new customerUIRender();

	echo $customerUIRender->renderCustomerHtmlTop('../../', 'Jobs', 'Create, edit, and view jobs.');

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

		<div class="cmsMainContentWrapper styledText textColorThemeJobs">

            <div class="desktopOnlyBlock" style="width: 100%; height: 90%;">
                <?php

					if (isset($_GET['main-m'])) {
						$selectedMonth = $_GET['main-m'];
					} else {
						$currentDate = new DateTime();
						$selectedMonth = $currentDate->format('Y-m');
					}

                    require_once '../../../lib/render/job/customerJobCalendar.php';
                    $customerJobCalendar = new customerJobCalendar('main', ['rootPathPrefix' => '../../', 'month' => $selectedMonth, 'style' => 'width: 100%; height: 100%;']);
                    $customerJobCalendar->render();
                    echo $customerJobCalendar->output;

                ?>
            </div>

            <div class="mobileOnlyBlock">
                <p>Mobile Jobs List....</p>
            </div>
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
