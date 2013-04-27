/**
 * Extend attribute selection
 *
 * Show/hide fields depending on type
 */
$(function() {
	var select = $('#field'), attrs = $('.hide');

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

	update();
});