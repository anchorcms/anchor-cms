$(function() {
	var timer, title = $('title'), message = title.html();

	$(window).on('blur', function() {
		timer = setTimeout(function() {
			title.html('Are you still there?');
		}, 30000);
	}).on('focus', function() {
		clearTimeout(timer);
		title.html(message);
	});
});
