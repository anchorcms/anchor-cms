/**
 * Textarea auto resize
 */
$(function() {
	var textarea = $('textarea').first(), limit = 1080;

	var resize = function() {
		textarea.height(textarea[0].scrollHeight);
	};

	textarea.bind('keydown', resize).trigger('keydown');
});