
var Popup = function() {

	var $id = Number.random(100, 1000);
	
	var overlay = new Element('div', {
		'id': 'popup_overlay_' + $id,
		'class': 'popup_overlay'
	});
	
	var box = new Element('div', {
		'id': 'popup_box_' + $id,
		'class': 'popup_box',
		'styles': {
			'opacity': 0
		}
	});
	
	var position = function() {
		var body = $$('body').pop(), 
			offset = body.getScroll(), 
			screen = body.getScrollSize(), 
			size = box.getSize();
	
		return {
			'left': (screen.x / 2) - (size.x / 2),
			'top': offset.y + 50
		};
	};
	
	var open = function() {
		var body = $$('body'), options = arguments[0] || {};
		
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
		body.grab(overlay);
		
		// apply box styles
		box.setStyles({
			'width': options.width
		});
		
		// append box
		body.grab(box);
		
		// add content
		box.empty();
		box.grab(options.content);

		// position box and show
		var pos = position();

		box.setStyles({
			'top': pos.top,
			'left': pos.left
		});
		
		box.fade('in');
		
		// bind events
		overlay.addEvent('click', close);
		
		if(options.handle.addEvent) {
			options.handle.addEvent('click', close);
		}
	};
	
	var close = function() {
		overlay.dispose();
		box.dispose();
		return false;
	};
	
	return {
		'open': open,
		'close': close
	};
	
};

