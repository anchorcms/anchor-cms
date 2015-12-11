$(function() {
	var link = $('.linky'), top = $('.slidey'), height = top.outerHeight() * -1;

	link.on('click', function() {
		top.css('margin-top', link.hasClass('active') ? 0 : height);
		link.toggleClass('active');
		return false;
	}).trigger('click');

	setTimeout(function() {
		$('body').addClass('js-enabled');
	}, 10);
});
