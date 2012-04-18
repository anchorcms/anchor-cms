
(function() {

	var checkbox = $('#redirect'), 
		redirect = $('#redirect_url').parent(),
		content = $('#content').parent();

	var set = function() {
		var display = checkbox.get('checked') ? 'block' : 'none';
		redirect.css('display', display);

		display = checkbox.get('checked') ? 'none' : 'block';
		content.css('display', display);

		if(!checkbox.get('checked')) {
			$('#redirect_url').val('');
		}
	};

	// bind to input
	checkbox.bind('change', set);

	set();

}());
