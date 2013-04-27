/**
 * Populate placeholder when user selects a file to upload
 */
$(function() {
	var basename = function(path) {
		return path.replace(/\\/g,'/').replace(/.*\//, '');
	};

	$('input[type=file]').bind('change', function() {
		var input = $(this), placeholder = input.parent().parent().find('.current-file');

		placeholder.html(basename(input.val()));
	});
});