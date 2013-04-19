/**
 * Drag and Drop upload
 *
 * Allows the drag and drop of single files into posts
 */
$(function() {
	var zone = $(document), body = $('body');
	var allowed = ['text/css', 'text/javascript', 'application/javascript', 'text/x-markdown',
		'image/jpeg', 'image/gif', 'image/png'];

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
			else if(window.console) {
				console.log(file.type + ' not supported');
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
		console.log('progress: ' + position + ' / ' + total);

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

		if(data.result) {
			var textarea = $('.main textarea'),
				element = textarea[0],
				start = element.selectionStart,
				value = element.value,
				img = "\n\n" + '![' + file.name + '](' + data.uri + ')' + "\n\n";

			element.value = value.substring(0, start) + img + value.substring(start);
			element.selectionStart = element.selectionEnd = start + img.length;
			textarea.trigger('keydown');
		}
	};

	var complete = function() {
		if(['text/css'].indexOf(this.file.type) !== -1) {
			var element = $('textarea[name=css]');

			if(element.size()) element.val(this.result).parent().show();
		}

		if(['text/javascript', 'application/javascript'].indexOf(this.file.type) !== -1) {
			var element = $('textarea[name=js]');

			if(element.size()) element.val(this.result).parent().show();
		}

		if(['text/x-markdown'].indexOf(this.file.type) !== -1) {
			var textarea = $('.main textarea'), value = textarea.val();

			textarea.val(this.result).trigger('keydown');
		}

		if(['image/jpeg', 'image/gif', 'image/png'].indexOf(this.file.type) !== -1) {
			var path = window.location.pathname, uri, parts = path.split('/');

			if(parts[parts.length - 1] == 'add') {
				uri = path.split('/').slice(0, -1).join('/') + '/upload';
			}
			else {
				uri = path.split('/').slice(0, -2).join('/') + '/upload';
			}

			upload(uri, this.file);
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

		// hide drag/drop inputs until populated
		$('textarea[name=css],textarea[name=js]').each(function(index, item) {
			var element = $(item);

			if(element.val() == '') {
				element.parent().hide();
			}
		});
	}
});