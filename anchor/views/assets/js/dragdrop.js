/**
 * Drag and Drop upload
 *
 * Allows the drag and drop of single files into posts
 */
$(function() {
	var zone = $(document), body = $('body');
	var allowed = ['text/css', 'text/javascript', 'application/javascript', 'text/x-markdown'];

	var cancel = function(event) {
		event.preventDefault();
		return false;
	};

	var open = function(event) {
		event.preventDefault();
		body.addClass('draggy');
		return false;
	};

	var close = function(event) {
		event.preventDefault();
		body.removeClass('draggy');
		return false;
	};

	var drop = function(event) {
		event.preventDefault();

		var files = event.target.files || event.dataTransfer.files;

		for(var i = 0; i < files.length; i++) {
			var file = files.item(i);

			if(allowed.indexOf(file.type) !== -1) {
				transfer(file);
			}
		}

		body.removeClass('draggy');

		return false;
	};

	var transfer = function(file) {
		var reader = new FileReader();
		reader.file = file;
		reader.callback = complete;
		reader.onload = reader.callback;
		reader.readAsText(file);
	};

	var complete = function() {
		if(['text/css'].indexOf(this.file.type) !== -1) {
			$('textarea[name=css]').val(this.result).parent().show();
		}

		if(['text/javascript', 'application/javascript'].indexOf(this.file.type) !== -1) {
			$('textarea[name=js]').val(this.result).parent().show();
		}

		if(['text/x-markdown'].indexOf(this.file.type) !== -1) {
			var textarea = $('textarea[name=html]'), value = textarea.val();

			textarea.val(this.result).trigger('keydown');
		}
	};

	if(window.FileReader && window.FileList && window.File) {
		zone.on('dragover', open);
		zone.on('dragenter', cancel);
		zone.on('drop', drop);
		zone.on('dragleave', cancel);
		zone.on('dragexit', close);

		body.append('<div id="upload-file"><span>Upload your file</span></div>');

		// hide drag/drop inputs until populated
		$('textarea[name=css],textarea[name=js]').each(function(index, item) {
			var element = $(item);

			if(element.val() == '') {
				element.parent().hide();
			}
		});
	}
});