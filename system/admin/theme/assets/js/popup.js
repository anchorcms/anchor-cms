
var Popup = function() {

	var overlay = new Element('div');
	overlay.addClass('popup_overlay');

	var box = new Element('div');
	box.addClass('popup_box');

	var open = function() {
		var body = $('body'), options = arguments[0] || {};
		
		// default options
		var defaults = {
			'content': new Element('p'),
			'handle': false,
			'width': 600
		}
		
		for(var key in defaults) {
			options[key] = (options[key] === undefined) ? defaults[key] : options[key];
		}
		
		// append overlay
		body.append(overlay);
		
		// apply box styles
		box.css('width', options.width + 'px');
		
		// append box
		body.append(box);
		
		// add content
		box.empty();
		box.append(options.content);
		box.css('left', ((window.innerWidth / 2) - (parseInt(box.css('width'), 10) / 2)) + 'px')
		
		// bind events
		overlay.bind('click', close);
		
		if(options.handle.bind) {
			options.handle.bind('click', close);
		}
	};
	
	var close = function() {
		overlay.remove();
		box.remove();
		return false;
	};
	
	return {
		'open': open,
		'close': close
	};
	
};

