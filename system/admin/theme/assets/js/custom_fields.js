
(function() {

	var a = $('#create'), fields = $('#fields');
	
	var p = new Popup();
	
	var create_field = function(key, label) {
		var p = new Element('p', {
				'id': 'field_' + key
			}), 
			l = new Element('label'), 
			i = new Element('input', {
				'name': 'field[' + key + ':' + label + ']',
				'type': 'text'
			});

		l.html(label + ':');

		p.append(l);
		p.append(i);
		
		fields.append(p);
	};
	
	var add_field = function(event) {
		// get data
		var label = $('input[name=field_label]'),
			key = $('input[name=field_key]'),
			errors = [];

		if(!label.val()) {
			errors.push(Lang.get('missing_label'));
		}
		
		if(!key.val()) {
			errors.push(Lang.get('missing_key'));
		}
		
		if(errors.length) {
			// show errors
			alert(errors.join("\n"));
			return;
		}
		
		create_field(key.val(), label.val());
		
		p.close();

		event.end();
	};

	var show_add_field = function() {
		var html = '<fieldset><legend>' + Lang.get('custom_field') + '</legend><em>' + Lang.get('custom_field_explain') + '</em>';
		html +='<p><label>' + Lang.get('label') + '</label><input name="field_label" type="text"></p>';
		html +='<p><label>' + Lang.get('key') + '</label><input name="field_key" type="text"></p>';
		html += '</fieldset>';
		html +='<p class="buttons"><button name="create" type="button">' + Lang.get('create') + '</button> <a href="#close">' + Lang.get('close') + '</a></p>';
		
		var box = new Element('div');
		box.addClass('popup_wrapper');
		box.html(html);
		
		p.open({
			'content': box
		});
		
		// bind popup events
		$('button[name=create]').bind('click', add_field);
		$('a[href$=close]').bind('click', function(event) {
			p.close();
			event.end();
		});
	};


	// bind create method
	a.bind('click', show_add_field);

}());
