/**
 * Format title into a slug value after each keypress
 * Disabled if the slug is manually changed
 */
$(function() {
	var input = $('input[name=title]'), output = $('input[name=slug]');
	var changed = false;

	var slugify = function(str) {
		str = str.replace(/^\s+|\s+$/g, '').toLowerCase();

		// remove accents
		var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;", to = "aaaaeeeeiiiioooouuuunc------";

		for(var i = 0, l = from.length; i < l; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		}

		return str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by -
			.replace(/-+/g, '-'); // collapse dashes
	}

	output.bind('keyup', function() {
		changed = true;
	});

	input.bind('keyup', function() {
		if( ! changed) output.val(slugify(input.val()));
	});
});
