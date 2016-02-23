/**
 * Drag and Drop upload
 *
 * Allows the drag and drop of single files into posts
 */
$(function() {
	var zone = $(document), body = $('body');

	var allowed = ['text/css',
		'text/javascript', 'application/javascript',
		'text/x-markdown', 'application/pdf',
		'image/jpeg', 'image/gif', 'image/png', 'image/bmp'];

	var debug = function(message) {
		if(window.console) console.log(message);
	}

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
			else {
				debug(file.type + ' not supported');
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
		reader.readAsBinaryString(file);
	};

	var complete = function() {
		if(['text/x-markdown'].indexOf(this.file.type) !== -1) {
			var textarea = $('.main textarea');

			textarea.val(this.result).trigger('keydown');
		}

		if(['text/javascript', 'application/javascript'].indexOf(this.file.type) !== -1) {
			var textarea = $('textarea[name=js]');

			textarea.val(this.result);
		}

		if(['text/css'].indexOf(this.file.type) !== -1) {
			var textarea = $('textarea[name=css]');

			textarea.val(this.result);
		}

		if(['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'application/pdf'].indexOf(this.file.type) !== -1) {
			var path = window.location.pathname, uri, parts = path.split('/');

			if(parts[parts.length - 1] == 'add') {
				uri = path.split('/').slice(0, -2).join('/') + '/upload';
			}
			else {
				uri = path.split('/').slice(0, -3).join('/') + '/upload';
			}

			upload(uri, this.file);
		}
	};

	var upload = function(uri, file) {
		// Uploading - for Firefox, Google Chrome and Safari
		var xhr = new XMLHttpRequest();
		xhr.open("post", uri);

		var formData = new FormData();
		formData.append('file', file);

		xhr.onreadystatechange = function() {
			if(this.readyState == 4) {
				return uploaded(file, this.responseText);
			}
		}

		if(xhr.upload) {
			xhr.upload.onprogress = function(e) {
				upload_progress(e.position || e.loaded, e.totalSize || e.total);
			};
		}
		else {
			xhr.addEventListener('progress', function(e) {
				upload_progress(e.position || e.loaded, e.totalSize || e.total);
			}, false);
		}

		// Send the file (doh)
		xhr.send(formData);
	};

	var upload_progress = function(position, total) {
		if(position == total) {
			$('#upload-file-progress').hide();
		}
		else {
			$('#upload-file-progress').show();

			$('#upload-file-progress progress').prop('value', position);
			$('#upload-file-progress progress').prop('max', total);
		}
	};

	var uploaded = function(file, response) {
		var data = JSON.parse(response);

		if(data.uri) {
			var textarea = $('.main textarea'),
				element = textarea[0],
				start = element.selectionStart,
				value = element.value,
				fileOutput = '[' + file.name + '](' + data.uri + ')' + "\n\n";

			if(['image/jpeg', 'image/gif', 'image/png', 'image/bmp'].indexOf(file.type) !== -1) {
				fileOutput = "\n\n!" + fileOutput;
			} else {
				fileOutput = "\n\n" + fileOutput;
			}

			element.value = value.substring(0, start) + fileOutput + value.substring(start);
			element.selectionStart = element.selectionEnd = start + file.length;
			textarea.trigger('keydown');
		}
	};

	if(window.FileReader && window.FileList && window.File) {
		zone.on('dragover', open);
		zone.on('dragenter', cancel);
		zone.on('drop', drop);
		zone.on('dragleave', cancel);
		zone.on('dragexit', close);

		body.append('<div id="upload-file"><span>Upload your file</span></div>');
		body.append('<div id="upload-file-progress"><progress value="0"></progress></div>');
	}
});
