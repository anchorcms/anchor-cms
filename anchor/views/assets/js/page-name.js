(function(input, output) {
	var changed = false;

	output.bind('keyup', function() {
		changed = true;
	});

	input.bind('keyup', function() {
		if( ! changed) output.val(input.val());
	});
}($('input[name=title]'), $('input[name=name]')));