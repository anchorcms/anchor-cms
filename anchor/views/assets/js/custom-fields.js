/**
 * Extend attribute selection
 *
 * Show/hide fields depending on type
 */
$(function() {
	var select = $('#label-field'), attrs = $('.hide'), fieldtype = $('#label-type'), pagetype = $('#pagetype');

	var update = function() {
		var value = select.val();

		attrs.hide();

		if(value == 'image') {
			attrs.show();
		}
		else if(value == 'file') {
			$('.attributes_type').show();
		}
	};

	select.bind('change', update);

	var typechange = function() {
		var value = fieldtype.val();

		if (value == 'page') {
			pagetype.parent().show();
		}
		else {
			pagetype.parent().hide();
			pagetype.val('all');
		}
	};

	fieldtype.bind('change', typechange);

	update();
});