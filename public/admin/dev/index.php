<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => 'dev',
		"pageDescription" => 'dev test space']);

    echo $adminUIRender->renderAdminUIMenuToggleScripts('../../');

?>

</head>

<body>
    <div class="adminBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../../');

            // require_once '../../../lib/render/ui/popupsHandler.php';
            // $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', "popups" => ['workspaceCreated'], 'class' => 'styledText spacedText defaultMainShadows', 'style' => 'width: 500em;']);
            // $popupsHandler->render();
            // echo $popupsHandler->output;
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php

                    // require_once '../../../lib/table/calendarEvent.php';
                    // require_once '../../../lib/table/contactEmailAddress.php';
                    // require_once '../../../lib/table/contactPhoneNumber.php';
                    // $lastNames = require_once '../../../lib/arrays/lastNames.php';
                    // $firstNames = require_once '../../../lib/arrays/firstNames.php';

                    // for ($x = 0; $x <= 1500; $x++) {
                    //     $calendarEvent = new calendarEvent();

                    //     $calendarEvent->linkedToContactId = NULL;
                    //     $calendarEvent->linkedToPropertyId = NULL;
                    //     $calendarEvent->name = 'Mowing'.uniqid();
                    //     $calendarEvent->description = NULL;
                    //     $calendarEvent->privateNotes = NULL;
                    //     $calendarEvent->price = NULL;
                    //     $calendarEvent->estHours = NULL;
                    //     $calendarEvent->isPrepaid = 0;
                    //     $calendarEvent->frequencyInterval = 'none';
                    //     $calendarEvent->frequency = 1;
                    //     $calendarEvent->weekday = '5';
                    //     $calendarEvent->startDateTime = '2022-02-18 00:00:00';
                    //     $calendarEvent->endDateTime = NULL;
    
                    //     $calendarEvent->set();
    
    
                    //     $contactEmailAddress = new contactEmailAddress();
    
                    //     $contactEmailAddress->contactId = $contact->contactId;
                    //     $contactEmailAddress->email = 'email@example.com';
    
                    //     $contactEmailAddress->set();
    
    
                    //     $contactPhoneNumber = new contactPhoneNumber();
    
                    //     $contactPhoneNumber->contactId = $contact->contactId;
                    //     $contactPhoneNumber->phonePrefix = '1';
                    //     $contactPhoneNumber->phone1 = (string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9);
    
                    //     $contactPhoneNumber->set();
                    // }

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/table/admin.php';

                    // $admin = new admin('40261a2e60e2309b');

                    // $admin->firstName = 'Cameron';
                    // $admin->lastName = 'McGehee';

                    // $admin->set();

                    // echo '<p>'.htmlspecialchars($admin->adminId).'</p>';
                    // echo '<p>'.htmlspecialchars($admin->firstName).' '.htmlspecialchars($admin->lastName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/table/workspace.php';

                    // $workspace = new workspace('691661a305e702984');

                    // $workspace->displayName = "C's Test Workspace";
                    // $workspace->adminDisplayName = 'Test Workspace';

                    // $workspace->set();

                    // echo '<p>'.htmlspecialchars($workspace->workspaceId).'</p>';
                    // echo '<p>'.htmlspecialchars($workspace->displayName).' '.htmlspecialchars($workspace->adminDisplayName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/render/contact/contactTable.php';

                    // $contactTable = new contactTable();

                    // $contactTable->rootPathPrefix = '../';

                    // $contactTable->render();

                    // echo $contactTable->output;

                    // // echo '<p>'.htmlspecialchars($workspace->workspaceId).'</p>';
                    // // echo '<p>'.htmlspecialchars($workspace->displayName).' '.htmlspecialchars($workspace->adminDisplayName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    require_once '../../../lib/table/workspace.php';
                    $currentWorkspace = new workspace($_SESSION['lifems_workspaceId']);
                    $currentWorkspace->pullNotes();

                    require_once '../../../lib/table/note.php';

                    $note = new note($currentWorkspace->notes[0]);
                    echo $note->convertMarkdownToHtml();
                    // var_dump($note);

                    // echo '<p>'.htmlspecialchars($workspace->workspaceId).'</p>';
                    // // echo '<p>'.htmlspecialchars($workspace->displayName).' '.htmlspecialchars($workspace->adminDisplayName).'</p>';


                ?>

                <!-- <a class="hoverTip">Tooltip Text<span>This is the text that will display in the tooltip pop-up.</span></a> -->
            </div>
        </div>
        
        <?php 
            echo $adminUIRender->renderAdminFooter();
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
