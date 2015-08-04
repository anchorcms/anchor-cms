$(function() {
	var timer, message = document.title;

	//  We use some CSS hooks to check whether JavaScript is
	//  enabled or not.
	$('html').addClass('js').removeClass('no-js');

	$(window).on({
		blur: function() {
			timer = setTimeout(function() {
				document.title = 'Are you still there?';
			}, 30000);
		},
		focus: function() {
			clearTimeout(timer);
			document.title = message;
		}
	});

	//  There's an "auto-update" filter in the top nav bar, we need to make that work.
	var au = $('select.autoupdate');
	au.on('change', function() {
		var current = au.children('option:selected'),
			location = window.location.toString(),
			strip = location.substring(0, location.indexOf('?')),
			append = '';

		if(!current.attr('data-reset').length && !window.location.search) {
			append = '?' + au.attr('data-append') + '=' + au.val();
		}

		window.location = strip + append;
	});
});


if(typeof window['console'] === 'undefined') {
	window.console = {
		log: function(val) {},
		warn: function(val) {}
	}
}