(function() {
	var fieldset = $('fieldset.redirect'),
		input = $('input[name=redirect]'),
		btn = $('button.secondary');

	var toggle = function() {
		fieldset.toggleClass('show');

		return false;
	};

	btn.bind('click', toggle);

	if(input.val() == '') fieldset.removeClass('show');
}());