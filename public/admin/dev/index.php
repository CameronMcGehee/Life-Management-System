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
            // $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', "popups" => ['businessCreated'], 'class' => 'styledText spacedText defaultMainShadows', 'style' => 'width: 500em;']);
            // $popupsHandler->render();
            // echo $popupsHandler->output;
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php

                    // require_once '../../../lib/table/job.php';
                    // require_once '../../../lib/table/customerEmailAddress.php';
                    // require_once '../../../lib/table/customerPhoneNumber.php';
                    // $lastNames = require_once '../../../lib/arrays/lastNames.php';
                    // $firstNames = require_once '../../../lib/arrays/firstNames.php';

                    // for ($x = 0; $x <= 1500; $x++) {
                    //     $job = new job();

                    //     $job->linkedToCustomerId = NULL;
                    //     $job->linkedToPropertyId = NULL;
                    //     $job->name = 'Mowing'.uniqid();
                    //     $job->description = NULL;
                    //     $job->privateNotes = NULL;
                    //     $job->price = NULL;
                    //     $job->estHours = NULL;
                    //     $job->isPrepaid = 0;
                    //     $job->frequencyInterval = 'none';
                    //     $job->frequency = 1;
                    //     $job->weekday = '5';
                    //     $job->startDateTime = '2022-02-18 00:00:00';
                    //     $job->endDateTime = NULL;
    
                    //     $job->set();
    
    
                    //     $customerEmailAddress = new customerEmailAddress();
    
                    //     $customerEmailAddress->customerId = $customer->customerId;
                    //     $customerEmailAddress->email = 'email@example.com';
    
                    //     $customerEmailAddress->set();
    
    
                    //     $customerPhoneNumber = new customerPhoneNumber();
    
                    //     $customerPhoneNumber->customerId = $customer->customerId;
                    //     $customerPhoneNumber->phonePrefix = '1';
                    //     $customerPhoneNumber->phone1 = (string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9).(string)rand(0,9);
    
                    //     $customerPhoneNumber->set();
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

                    // require_once '../../../lib/table/business.php';

                    // $business = new business('691661a305e702984');

                    // $business->displayName = "C's Test Business";
                    // $business->adminDisplayName = 'Test Business';

                    // $business->set();

                    // echo '<p>'.htmlspecialchars($business->businessId).'</p>';
                    // echo '<p>'.htmlspecialchars($business->displayName).' '.htmlspecialchars($business->adminDisplayName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    // require_once '../../../lib/render/customer/customerTable.php';

                    // $customerTable = new customerTable();

                    // $customerTable->rootPathPrefix = '../';

                    // $customerTable->render();

                    // echo $customerTable->output;

                    // // echo '<p>'.htmlspecialchars($business->businessId).'</p>';
                    // // echo '<p>'.htmlspecialchars($business->displayName).' '.htmlspecialchars($business->adminDisplayName).'</p>';

                    // -----------------------------------------------------------------------------------------------------------------------------------------------

                    require_once '../../../lib/table/business.php';
                    $currentBusiness = new business($_SESSION['ultiscape_businessId']);
                    $currentBusiness->pullNotes();

                    require_once '../../../lib/table/note.php';

                    $note = new note($currentBusiness->notes[0]);
                    echo $note->convertMarkdownToHtml();
                    // var_dump($note);

                    // echo '<p>'.htmlspecialchars($business->businessId).'</p>';
                    // // echo '<p>'.htmlspecialchars($business->displayName).' '.htmlspecialchars($business->adminDisplayName).'</p>';


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
