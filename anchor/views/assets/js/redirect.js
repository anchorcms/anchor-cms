/**
 * Toggles the redirect field in pages
 */
$(function() {
	var fieldset = $('fieldset.redirect'),
		input = $('input[name=redirect]'),
		btn = $('button.secondary.redirector');

	var toggle = function() {
		fieldset.toggleClass('show');
		return false;
	};

	btn.bind('click', toggle);

	// Hide the input if you get rid of the content within.
	input.change(function(){
		if(input.val() === '') fieldset.removeClass('show');
	});

	// Show the redirect field if it isn't empty.
	if(input.val() !== '') fieldset.addClass('show');
});