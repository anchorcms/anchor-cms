
var Comments = (function() {

	var p = new Popup();

	var publish = function(event) {
		var a = this, 
			li = a.parent(),
			url = Base_url + 'comments/status',
			parent = li.parent().parent(),
			id = parent.get('id').split('c').pop(),
			span = parent.find('.status');

		new Request.post(url, {'id': id, 'status': 'published'}, function() {
			li.remove();
			span.html(Lang.get('published'));
		});

		event.end();
	};
	
	var edit = function(event) {
		var a = this,
			li = a.parent().parent().parent();

		var id = li.get('id').split('c').pop(),
			text = li.find('.comment').html(),
			status = li.find('.status').html();

		var url = Base_url + 'comments/update';

		var html = '<fieldset><legend>' + Lang.get('edit_comment') + '</legend><em>' + Lang.get('edit_comment_explain') + '</em>';
		html +='<p><label>Text</label><textarea name="comment_text">' + text + '</textarea></p>';
		html +='<p><label>Status</label><select name="comment_status">';

			html += '<option value="published"' + (status == 'published' ? ' selected' : '') + '>' + Lang.get('published') + '</option>';
			html += '<option value="pending"' + (status == 'pending' ? ' selected' : '') + '>' + Lang.get('pending') + '</option>';
			html += '<option value="spam"' + (status == 'spam' ? ' selected' : '') + '>' + Lang.get('spam') + '</option>';

		html += '</select></p>';
		html += '</fieldset>';
		html +='<p class="buttons"><button name="update" type="button">' + Lang.get('update') + '</button> ';
		html +='<a href="#close">' + Lang.get('close') + '</a></p>';
		
		var content = new Element('div', {
			'class': 'popup_wrapper'			
		});
		content.html(html);

		p.open({
			'content': content
		});

		// bind functions
		$('button[name=update]').bind('click', function() {
			update(id);
		});

		$('a[href$=close]').bind('click', function(event) {
			p.close();
			event.end();
		});

		event.end();
	};

	var update = function(id) {
		var url = Base_url + 'comments/update',
			li = $('#c' + id),
			comment_text_input = $('textarea[name=comment_text]'),
			comment_status_input = $('select[name=comment_status]');

		// get values
		var text = comment_text_input.val(),
			status = comment_status_input.val();

		// get elements
		var	comment_text_output = li.find('.comment'),
			comment_status_output = li.find('.status');

		new Request.post(url, {'id': id, 'text': text, 'status': status}, function() {
			comment_text_output.html(text);
			comment_status_output.html(Lang.get(status));

			// get publish button if it exists
			var btn = li.find('a[href$=publish]');

			// hide publish button
			if(btn) {
				if(status == 'published') {
					btn.remove();
				}
			} else {
				if(status == 'pending') {
					var ul = li.find('ul');
					btn = new Element('li');
					var a = new Element('a', {
						'href': '#publish'
					});
					a.html(Lang.get('publish'));
					a.bind('click', publish);
					btn.append(a);
					ul.append(btn, 'top');
				}
			}

			p.close();
		});
	};
	
	var remove = function() {
		var a = this, li = a.parent().parent().parent();
		var url = Base_url + 'comments/remove';
		var id = li.get('id').split('c').pop();
		
		new Request.post(url, {'id': id}, function() {
			li.remove();
		});

		return false;
	};

	return {
		'init': function() {
			// bindings
			$$('#comments a[href$=publish]').each(function(itm) {
				itm.bind('click', publish);
			});
			$$('#comments a[href$=edit]').each(function(itm) {
				itm.bind('click', edit);
			});
			$$('#comments a[href$=delete]').each(function(itm) {
				itm.bind('click', remove);
			});
		}
	};

}());
