<?php

    // Start Session
    require_once '../../../php/startSession.php';

    // If not signed in, just redirect to the login page
    require_once '../../../../lib/etc/adminHeaderRedirect.php';
    adminHeaderRedirect('../', '../');

    require_once '../../../../lib/adminUIRender.php';
    $adminUIRender = new adminUIRender();

    echo $adminUIRender->renderAdminHtmlTop('../../../', [
		"type" => "notes",
        "pageTitle" => "Notes",
		"pageDescription" => 'Create, edit, and view notes.']);

    echo $adminUIRender->renderAdminUIMenuToggleScripts('../../../');

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>

<script type="text/javascript">
    
    $(function() {
        
    });
    
</script>

</head>

<body>
    <div style="display: none;" id="scriptLoader"></div>
    <div class="adminBodyWrapper">

        <?php 
            echo $adminUIRender->renderAdminTopBar('../../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminSideBar('../../../');
        ?>

        <?php
            require_once '../../../../lib/render/ui/popupsHandler.php';
            $popupsHandler = new popupsHandler('mainPopupsHandler', ["rootPathPrefix" => '../../../', 'class' => 'styledText spacedText defaultMainShadows']);
            $popupsHandler->render();
            echo $popupsHandler->output;
        ?>

        <div class="cmsMainContentWrapper styledText spacedText textColorThemeGray">

            <div class="mobileOnlyBlock xyCenteredFlex centered" style="position: sticky; top: 0px; width: 100%; padding-top: .3em; padding-bottom: .3em; border-bottom: .1em solid gray; background-color: white; z-index: 99;">
                <div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
                <img style="display: none; width: 2em;" src="../../../../images/ultiscape/etc/loading.gif" class="loadingGif">
            </div>

            <div class="twoColPage-Content-InfoSmall maxHeight">
            
                <div id="twoColContentWrapper" class="marginLeftRight90 maxHeight" style="overflow: auto;">
                    <h2 class="">Note name...</h2>
                    <br>

                    <textarea class="noteEditor"></textarea>
                    <script>
                        const easyMDE = new EasyMDE({
                            autofocus: true,
                            autosave: {
                                enabled: false,
                                uniqueId: "MyUniqueID",
                                delay: 1000,
                                submit_delay: 5000,
                                timeFormat: {
                                    locale: 'en-US',
                                    format: {
                                        year: 'numeric',
                                        month: 'long',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                    },
                                },
                                text: "Autosaved: "
                            },
                            blockStyles: {
                                bold: "__",
                                italic: "_",
                            },
                            unorderedListStyle: "-",
                            // element: document.getElementById("noteEditor"),
                            forceSync: true,
                            hideIcons: ["guide", "heading"],
                            indentWithTabs: false,
                            initialValue: "Hello world!",
                            insertTexts: {
                                horizontalRule: ["", "\n\n-----\n\n"],
                                image: ["![](http://", ")"],
                                link: ["[", "](https://)"],
                                table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
                            },
                            lineWrapping: true,
                            minHeight: "500px",
                            parsingConfig: {
                                allowAtxHeaderWithoutSpace: false,
                                strikethrough: false,
                                underscoresBreakWords: true,
                            },
                            placeholder: "Type here...",

                            // previewClass: "my-custom-styling",
                            // previewClass: ["my-custom-styling", "more-custom-styling"],

                            previewRender: (plainText) => customMarkdownParser(plainText), // Returns HTML from a custom parser
                            previewRender: (plainText, preview) => { // Async method
                                setTimeout(() => {
                                    preview.innerHTML = customMarkdownParser(plainText);
                                }, 250);

                                // If you return null, the innerHTML of the preview will not
                                // be overwritten. Useful if you control the preview node's content via
                                // vdom diffing.
                                // return null;

                                return "Loading...";
                            },
                            promptURLs: true,
                            promptTexts: {
                                image: "Custom prompt for URL:",
                                link: "Custom prompt for URL:",
                            },
                            renderingConfig: {
                                singleLineBreaks: false,
                                codeSyntaxHighlighting: true,
                                sanitizerFunction: (renderedHTML) => {
                                    // Using DOMPurify and only allowing <b> tags
                                    return DOMPurify.sanitize(renderedHTML, {ALLOWED_TAGS: ['b']})
                                },
                            },
                            shortcuts: {
                                drawTable: "Cmd-Alt-T"
                            },
                            // showIcons: ["code", "table"],
                            spellChecker: false,
                            status: false,
                            status: ["autosave", "lines", "words", "cursor"], // Optional usage
                            status: ["autosave", "lines", "words", "cursor", {
                                className: "keystrokes",
                                defaultValue: (el) => {
                                    el.setAttribute('data-keystrokes', 0);
                                },
                                onUpdate: (el) => {
                                    const keystrokes = Number(el.getAttribute('data-keystrokes')) + 1;
                                    el.innerHTML = `${keystrokes} Keystrokes`;
                                    el.setAttribute('data-keystrokes', keystrokes);
                                },
                            }], // Another optional usage, with a custom status bar item that counts keystrokes
                            styleSelectedText: false,
                            sideBySideFullscreen: false,
                            syncSideBySidePreviewScroll: false,
                            tabSize: 4
                            // toolbar: true,
                            // toolbarTips: false
                            // toolbarButtonClassPrefix: "mde"
                        });
                    </script>

                </div>

                <div id="twoColInfoWrapper" class="paddingLeftRight90 paddingTopBottom90">
                    <br class="desktopOnlyBlock">
                    <span class="desktopOnlyBlock">
                        <div class="changesMessage"><span style="color: green;">Up to date ✔</span></div>
                        <img style="display: none; width: 2em;" src="../../../../images/ultiscape/etc/loading.gif" class="loadingGif">
                    </span>

                    <br><hr><br>

                    <h3>Other Info</h3>

                    <?php
                        $addedDate = new DateTime(); // INSERT the current note's dateTimeAdded
                    ?>

                    <p>Added on <?php echo $addedDate->format('D, M d Y'); ?></p>
                </div>
        
            </div>

        </div>
        
        <?php 
            echo $adminUIRender->renderAdminFooter('../../../');
        ?>

        <?php 
            echo $adminUIRender->renderAdminMobileNavBar('../../../');
        ?>

    </div>

    <?php
		echo $adminUIRender->renderAdminTopBarDropdowns('../../../');
	?>
</body>

<?php 
    echo $adminUIRender->renderAdminHtmlBottom('../../../');
?>
