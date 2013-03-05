/*
	Drag and Drop upload
*/
$(function() {
	var zone = $(document), body = $('body');
	var allowed = ['text/css', 'text/javascript', 'application/javascript'];

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
		var type = (this.file.type == 'text/css') ? 'css' : 'js';

		$('textarea[name=' + type + ']').val(this.result);
	};

	if(window.FileReader && window.FileList && window.File) {
		zone.on('dragover', open);
		zone.on('dragenter', cancel);
		zone.on('drop', drop);
		zone.on('dragleave', cancel);
		zone.on('dragexit', close);

		body.append('<div id="upload-file"><span>Upload your file</span></div>');
	}
});