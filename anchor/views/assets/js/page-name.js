/**
 * Mirrors the page title into the page name field which is use in the menus
 */
$(function(input, output) {
	var input = $('input[name=title]'), output = $('input[name=name]');
	var changed = false;

	output.bind('keyup', function() {
		changed = true;
	});

	input.bind('keyup', function() {
		if( ! changed) output.val(input.val());
	});
});