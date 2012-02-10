(function(base_url) {

	var p = new Popup();

	var publish = function() {
		var a = this, li = a.getParent();
		var url = base_url + 'comments/status';
		var id = a.getParent('li[data-id]').get('data-id');
		
		new Request.JSON({
			'url': url,
			'method': 'post',
			'data': {'id': id, 'status': 'published'},
			'onRequest': function() {
				li.setStyle('opacity', 0.5);
				a.removeEvent('click', publish);
			},
			'onSuccess': function() {
				li.dispose();
				$$('[data-status=' + id + ']').set('html', 'Published');
			}
		}).send();

		return false;
	};
	
	var edit = function() {
		var a = this;
		var url = base_url + 'comments/update';
		var id = a.getParent('li[data-id]').get('data-id');
		var text = $$('[data-text=' + id + ']').get('html');
		var status = $$('[data-status=' + id + ']').get('html');

		var html = '<fieldset><legend>Edit Comment</legend><em>Update the comment text here.</em>';
		html +='<p><label>Text</label><textarea name="comment_text">' + text + '</textarea></p>';
		html +='<p><label>Status</label><select name="comment_status">';

			html += '<option value="published"' + (status == 'published' ? ' selected' : '') + '>Published</option>';
			html += '<option value="pending"' + (status == 'pending' ? ' selected' : '') + '>Pending</option>';
			html += '<option value="spam"' + (status == 'spam' ? ' selected' : '') + '>Spam</option>';

		html += '</select></p>';
		html += '</fieldset>';
		html +='<p class="buttons"><button name="update" type="button">Update</button> <a href="#close">Close</a></p>';
		
		var content = new Element('div', {
			'class': 'popup_wrapper',
			'html': html			
		});

		p.open({
			'content': content
		});

		// bind functions
		$$('button[name=update]').addEvent('click', function() {
			update(id);
		});
		$$('a[href$=#close]').addEvent('click', function() {
			p.close();
			return false;
		});

		return false;
	};

	var update = function(id) {
		var url = base_url + 'comments/update', 
			comment_text_input = $$('textarea[name=comment_text]').pop(),
			comment_status_input = $$('select[name=comment_status]').pop();

		// get values
		var text = comment_text_input.get('value'),
			status = comment_status_input.get('value');

		// get elements
		var	comment_text_output = $$('[data-text=' + id + ']').pop(),
			comment_status_output = $$('[data-status=' + id + ']').pop(),
			li = $$('li[data-id=' + id + ']').pop();
		
		new Request.JSON({
			'url': url,
			'method': 'post',
			'data': {'id': id, 'text': text, 'status': status},
			'onRequest': function() {
				li.setStyle('opacity', 0.5);
			},
			'onSuccess': function() {
				li.setStyle('opacity', 1);
				comment_text_output.set('html', text);
				comment_status_output.set('html', status);

				// get publish button if it exists
				var btn = li.getElement('a[href$=#publish]');

				// hide publish button
				if(btn) {
					if(status == 'published') {
						btn.dispose();
					}
				} else {
					if(status == 'pending') {
						var ul = li.getElement('ul');
						btn = new Element('li');
						btn.grab(new Element('a', {
							'html': 'Publish',
							'href': '#publish',
							'events': {
								'click': publish
							}
						}));
						ul.grab(btn, 'top');
					}
				}

				p.close();
			}
		}).send();
	};
	
	var remove = function() {
		var a = this, li = a.getParent('li[data-id]');
		var url = base_url + 'comments/remove';
		var id = li.get('data-id');
		
		new Request.JSON({
			'url': url,
			'method': 'post',
			'data': {'id': id},
			'onRequest': function() {
				li.setStyle('opacity', 0.5);
			},
			'onSuccess': function() {
				li.dispose();
			}
		}).send();

		return false;
	};

	// bindings
	$$('#comments a[href$=publish]').addEvent('click', publish);
	$$('#comments a[href$=edit]').addEvent('click', edit);
	$$('#comments a[href$=delete]').addEvent('click', remove);

}('../../'));
