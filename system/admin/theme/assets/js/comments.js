(function(base_url) {

	var p = new Popup();

	var publish = function() {
		var a = this, li = a.getParent();
		var url = base_url + 'admin/comments/status';
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
			}
		}).send();

		return false;
	};
	
	var edit = function() {
		var a = this;
		var url = base_url + 'admin/comments/update';
		var id = a.getParent('li[data-id]').get('data-id');
		var text = $$('p[data-text=' + id + ']').get('html');

		var html = '<fieldset><legend>Edit Comment</legend><em>Update the comment text here.</em>';
		html +='<p><label>Text</label><textarea data-id="' + id + '" name="comment_text">' + text + '</textarea></p>';
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
		$$('button[name=update]').addEvent('click', update);
		$$('a[href$=#close]').addEvent('click', function() {
			p.close();
			return false;
		});

		return false;
	};

	var update = function() {
		var url = base_url + 'admin/comments/update', 
			input = $$('textarea[name=comment_text]').pop();

		// get values
		var id = input.get('data-id'), 
			text = input.get('value');

		// get elements
		var	output = $$('p[data-text=' + id + ']').pop(),
			li = $$('li[data-id=' + id + ']').pop();
		
		new Request.JSON({
			'url': url,
			'method': 'post',
			'data': {'id': id, 'text': text},
			'onRequest': function() {
				li.setStyle('opacity', 0.5);
			},
			'onSuccess': function() {
				li.setStyle('opacity', 1);
				output.set('html', text);
				p.close();
			}
		}).send();
	};
	
	var remove = function() {
		var a = this, li = a.getParent('li[data-id]');
		var url = base_url + 'admin/comments/remove';
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

}('/index.php/'));
