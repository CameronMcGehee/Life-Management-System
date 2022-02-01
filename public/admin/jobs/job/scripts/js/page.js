var formData;
var formOutput;
var url = new URL(window.location.href);

var lastChange = new Date();
var changesSaved = true;
var waitingForError = false;

$(function() {

    function setUnsaved() {
        $(".changesMessage").each(function () {
            $(this).html('<span style="color: gray; width: 10em;">⏳ Saving changes...</span>');
        });
        $(".changesMessage").each(function () {
            $(this).shake(50);
        });
        changesSaved = false;
    }

    function setWaitingForError() {
        $(".changesMessage").each(function () {
            $(this).html('<span style="color: red;">Uh oh, fix the error!</span>');
        });
        $(".changesMessage").each(function () {
            $(this).shake(50);
        });
        waitingForError = true;
    }

    function setSaved() {
        $(".changesMessage").each(function () {
            $(this).html('<span style="color: green;">Up to date ✔</span>');
        });
        changesSaved = true;
        waitingForError = false;
    }

    if ($.isNumeric(url.searchParams.get('wsl'))) {
        $(".cmsMainContentWrapper").scrollTop(url.searchParams.get('wsl'));
    }

    function checkChanges() {
        $('.loadingGif').each(function() {
            $(this).fadeIn(100);
        });
        formData = $("#customerForm").serialize();
        
        $("#scriptLoader").load("./scripts/async/editcustomer.script.php", {
            formData: formData
        }, function () {
            formOutput = $("#scriptLoader").html();
            clearFormErrors();
            
            if (formOutput == 'success') {
                setSaved();
            } else {
                setWaitingForError();
                showFormError("#"+formOutput+"Error", "#"+formOutput);
                $("#"+formOutput).shake(50);

                $('.loadingGif').each(function() {
                    $(this).fadeOut(100);
                });
            }

            $('.loadingGif').each(function() {
                $(this).fadeOut(100);
            });
        });
        changesSaved = true;
    }
    
    $("#customerForm input, #customerForm select").change(function () {
        setUnsaved();
        lastChange = new Date();
    });


    var interval = setInterval(function() {
        if (changesSaved == false && (new Date() - lastChange) / 1000 > .5) {
            checkChanges();
        }
    }, 1000);

    window.onbeforeunload = function() {
        if (changesSaved == false || waitingForError == true) {
            return "Changes have not been saved yet. Are you sure you would like to leave?";
        } else {
            return;
        }
    };
});
