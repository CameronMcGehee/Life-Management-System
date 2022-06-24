function clearFormErrors() {
    // For each element with the .underInputError class, hide it
    $('.underInputError').each(function(i, obj) {
        $(this).hide(0);
    });
}
