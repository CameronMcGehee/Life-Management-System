<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/customerHeaderRedirect.php';
    customerHeaderRedirect('../', '../');

    require_once '../../../lib/customerUIRender.php';
    $customerUIRender = new customerUIRender();

    echo $customerUIRender->renderCustomerHtmlTop('../../', 'Home', 'What would you like to do?');

    echo $customerUIRender->renderCustomerUIMenuToggleScripts('../../');

    require_once '../../../lib/table/customer.php';
    require_once '../../../lib/table/business.php';
    $currentBusiness = new business($_SESSION['ultiscape_businessId']);
    $currentCustomer = new customer($_SESSION['ultiscape_customerId']);

?>

</head>

<body>
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

    <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div class="twoColPage-Content-Info">
				<div id="twoColContentWrapper" class="paddingLeftRight90">

                    <h2 class="centered">Hi, <?php echo htmlspecialchars($currentCustomer->firstName); ?>!</h2>
                    <p class="centered">Thanks for choosing <?php echo htmlspecialchars($currentBusiness->displayName); ?>!</p>
                    
                    <hr>
                    <br>
					
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-around;">

                        <a href="../myaccount" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/ultiscape/icons/user_male.svg" style="width: 80%;">
                                <p>My Account</p>
                            </div>
                        </a>

                        <a href="../services" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/ultiscape/icons/calendar_month.svg" style="width: 80%;">
                                <p>My Services</p>
                            </div>
                        </a>

                        <a href="../invoices" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/ultiscape/icons/document.svg" style="width: 80%;">
                                <p>My Invoices</p>
                            </div>
                        </a>

                        <a href="../estimates" style="border-bottom: none;">
                            <div class="xyCenteredFlex flexDirectionColumn" style="width: 10em; border: 1px solid gray; border-radius: 1em;">
                                <img src="../../images/ultiscape/icons/user.svg" style="width: 80%;">
                                <p>My Estimates</p>
                            </div>
                        </a>
                    </div>
				</div>

				<div id="twoColInfoWrapper" class="paddingLeftRight90">
				
                    <h2>Latest Activity</h2>
                    <?php

                        // require_once '../../../lib/render/customer/activityList.php';
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
