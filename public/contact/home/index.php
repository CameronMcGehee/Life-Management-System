<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/contactHeaderRedirect.php';
    contactHeaderRedirect('../', '../');

    require_once '../../../lib/contactUIRender.php';
    $contactUIRender = new contactUIRender();

    echo $contactUIRender->renderContactHtmlTop('../../', 'Home', 'What would you like to do?');

    echo $contactUIRender->renderContactUIMenuToggleScripts('../../');

    require_once '../../../lib/table/contact.php';
    require_once '../../../lib/table/workspace.php';
    $currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);
    $currentContact = new contact($_SESSION['lifems_contactId']);

?>

</head>

<body>
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

    <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div class="twoColPage-Content-Info">
				<div id="twoColContentWrapper" class="paddingLeftRight90">

                    <h2 class="centered">Hi, <?php echo htmlspecialchars($currentContact->firstName); ?>!</h2>
                    <p class="centered">Thanks for choosing <?php echo htmlspecialchars($currentWorkspace->displayName); ?>!</p>
                    
                    <hr>
                    <br>
					
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-around;">

                        <a href="../myaccount" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/lifems/icons/user_male.svg" style="width: 80%;">
                                <p>My Account</p>
                            </div>
                        </a>

                        <a href="../services" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/lifems/icons/calendar_month.svg" style="width: 80%;">
                                <p>My Services</p>
                            </div>
                        </a>

                        <a href="../invoices" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/lifems/icons/document.svg" style="width: 80%;">
                                <p>My Invoices</p>
                            </div>
                        </a>

                        <a href="../estimates" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/lifems/icons/user.svg" style="width: 80%;">
                                <p>My Estimates</p>
                            </div>
                        </a>
                    </div>
				</div>

				<div id="twoColInfoWrapper" class="paddingLeftRight90">
				
                    <h2>Latest Activity</h2>
                    <?php

                        // require_once '../../../lib/render/contact/activityList.php';
                        // $activityList = new activityList('activityList', [
                        //     'rootPathPrefix' => '../../',
                        //     'maxRows' => 5,
                        //     'showAdd' => true
                        // ]);
                        // $activityList->render();
                        // echo $activityList->output;

                    ?>

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
