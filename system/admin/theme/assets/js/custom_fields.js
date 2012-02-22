
(function() {
	var a = $('create'), fields = $('fields');
	
	var p = new Popup();
	
	var create_field = function(key, label) {
		var p = new Element('p', {
				'id': 'field_' + key
			}), 
			l = new Element('label', {
				'html': label + ':'
			}), 
			i = new Element('input', {
				'name': 'field[' + key + ':' + label + ']',
				'type': 'text'
			});

		p.grab(l);
		p.grab(i);
		
		fields.grab(p);
	};
	
	var add_field = function() {
		// get data
		var label = $$('input[name=field_label]').pop().get('value'),
			key = $$('input[name=field_key]').pop().get('value'),
			errors = [];
			
		if(label.length == 0) {
			errors.push(Lang.get('missing_label'));
		}
		
		if(key.length == 0) {
			errors.push(Lang.get('missing_key'));
		}
		
		if(errors.length) {
			// show errors
			return false;
		}
		
		create_field(key, label);
		
		p.close();
	};

	var show_add_field = function() {
		var html = '<fieldset><legend>' + Lang.get('custom_field') + '</legend><em>' + Lang.get('custom_field_explain') + '</em>';
		html +='<p><label>' + Lang.get('label') + '</label><input name="field_label" type="text"></p>';
		html +='<p><label>' + Lang.get('key') + '</label><input name="field_key" type="text"></p>';
		html += '</fieldset>';
		html +='<p class="buttons"><button name="create" type="button">' + Lang.get('create') + '</button> <a href="#close">' + Lang.get('close') + '</a></p>';
		
		var box = new Element('div', {
			'class': 'popup_wrapper', 
			'html': html
		});
		
		p.open({
			'content': box
		});
		
		// bind popup events
		$$('button[name=create]').addEvent('click', add_field);
		$$('a[href$=#close]').addEvent('click', function() {
			p.close();
			return false;
		});
		
		return false;
	};

	// bind create method
	a.addEvent('click', show_add_field);
}());

