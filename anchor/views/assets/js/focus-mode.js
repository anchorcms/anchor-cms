/**
 * Focus mode for post and page main textarea
 */
$(function() {
	var doc = $(document), html = $('html'), body = html.children('body');

	var Focus = {
		//  Our element to focus
		target: $('textarea[name=html], textarea[name=content]'),
		exitSpan: '#exit-focus',

		enter: function() {
			html.addClass('focus');

			if( ! body.children(Focus.exitSpan).length) {
				body.append('<span class="btn" id="' + Focus.exitSpan.substr(1) + '">Exit focus mode (ESC)</span>');
			}

			body.children(Focus.exitSpan).css('opacity', 0).animate({opacity: 1}, 250);

			//  Set titles and placeholders
			Focus.target.placeholder = (Focus.target.placeholder || '').split('.')[0] + '.';
		},

		exit: function() {
			body.children(Focus.exitSpan).animate({opacity: 0}, 250);
			html.removeClass('focus');
		}
	};

	//  Bind textarea events
	Focus.target.focus(Focus.enter).blur(Focus.exit);

	//  Bind key events
	doc.on('keyup', function(event) {
		//  Pressing the "f" key
		if(event.keyCode == 70) {
			Focus.enter();
		}

		//  Pressing the Escape key
		if(event.keyCode == 27) {
			Focus.exit();
		}
	});
});