function showFormError(inputErrorId, inputId) {
    // Show the actual error message below the input
    $(inputErrorId).fadeIn(100);
    // Outline the input in red
    // $("#"+inputId).css('border-color', 'red');
    // Focus to the element
    $(inputId).focus();
}
