<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', [
		"pageTitle" => "Overview",
		"pageDescription" => 'Overview of your LifeMS Workspace.']);

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
        ?>

        <?php
            require_once '../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../', 'class' => 'styledText spacedText defaultMainShadows']);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

    <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">
            <div style="margin-left: 2em; margin-right: 2em;">
                <?php
                    require_once '../../../lib/table/workspace.php';
                    $workspace = new workspace();
                ?>

                <h2>Overview of <span style="font-size: .8em;"><?php echo htmlspecialchars($workspace->adminDisplayName); ?></span></h2>
                <p>A quick glance at your workspace.</p>
            </div>

            <br><hr>

            <div class="twoColPage-Content-Info">
				<div id="twoColContentWrapper" class="paddingLeftRight90">
					<?php

						// require_once '../../../lib/render/invoice/invoiceTable.php';
						// $invoiceTable = new invoiceTable('main');
						// $invoiceTable->rootPathPrefix = '../../';
						// $invoiceTable->render();
						// echo $invoiceTable->output;

						

					?>
				</div>

				<div id="twoColInfoWrapper" class="paddingLeftRight90">
				
                    <h2>Newest Contacts</h2>
                    <?php

                        require_once '../../../lib/render/contact/contactTable.php';
                        $contactTable = new contactTable('newestContacts', [
                            'rootPathPrefix' => '../../',
                            'maxRows' => 5,
                            'showAdd' => true,
                            'showEmails' => false,
                            'showPhoneNumbers' => false,
                            'showBillingAddress' => false,
                            'showDateAdded' => true,
                            'useSort' => 'newest'
                        ]);
                        $contactTable->render();
                        echo $contactTable->output;

                    ?>

					<br><hr><br>

					<h3 style="color: var(--grayTextColorTheme);">Other Links</h3>

					<br>

					<a style="color: var(--grayTextColorTheme); display: inline-block;" href="#">Export (coming soon)</a>

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
