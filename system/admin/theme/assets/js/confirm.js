
var Confirm = (function() {

	var popup = new Popup({
		'width': 480
	});
	
	return {
		'open': function(callback) {
			var html = '<p>' + Lang.get('delete_confirm') + '</p>';
			html +='<p class="buttons"><button name="cancel" type="button">' + Lang.get('delete_confirm_cancel') + '</button> ';
			html +='<a href="#confim">' + Lang.get('delete_confirm_submit') + '</a></p>';

			var content = new Element('div', {
				'class': 'popup_wrapper'			
			});
			content.html(html);

			popup.open({
				'content': content
			});

			// bind functions
			$('button[name=cancel]').bind('click', function() {
				popup.close();
			});

			$('a[href$=confim]').bind('click', function(event) {
				callback();
				event.end();
			});
		}

	};

}());
