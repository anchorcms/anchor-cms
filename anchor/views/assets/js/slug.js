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
		
		var cyr = new Array ('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		var lat = new Array ('a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'y', 'f', 'h', 'c', 'ch', 'sh', 'sch', '"', 'y', "'", 'e', 'yu', 'ya');

		for (j = 0, lj = cyr.length; j < lj; j++) {
   			str = str.replace(new RegExp (cyr[j], 'g'), lat[j]);
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
