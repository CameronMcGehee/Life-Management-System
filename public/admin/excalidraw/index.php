<?php

    // Start Session
    require_once '../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../', [
		"type" => "excalidraw",
        "pageTitle" => "Excalidraw",
		"pageDescription" => 'Create, edit, and view Excalidraw drawings.']);

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
            <iframe style="height: 98.5%; width: 99%;" src="https://excalidraw.com/" title="Excalidraw"></iframe>
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
