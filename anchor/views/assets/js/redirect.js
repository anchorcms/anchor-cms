/**
 * Toggles the redirect field in pages
 */
$(function() {
    var fieldset = $('fieldset.redirect'),
        input = $('input[name=redirect]'),
        btn = $('button.secondary.redirector');

    var toggle = function() {
        fieldset.toggleClass('show');
        if (fieldset.hasClass('show')) {
            input.removeAttr('tabindex');
        } else {
            input.attr('tabindex', '-1');
        }
        return false;
    };

    btn.bind('click', toggle);

    // Hide the input if you get rid of the content within.
    input.change(function(){
        if(input.val() === '') fieldset.removeClass('show');
    });

    // Show the redirect field if it isn't empty.
    if(input.val() !== '') {
        fieldset.addClass('show');
    }

    //If the input is hidden, it shouldn't be possible to tab to it.
    if (!input.hasClass('show')) {
        input.attr('tabindex', -1);
    }
});
