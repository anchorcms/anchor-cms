/**
 * Handle custom field file uploads, need to be ajax, in
 * background and populate form field with path to file, so
 * we can store it in the database. Also handle populating 
 * placeholder in field.
 */
$(function() {

	if (window.XMLHttpRequest) {
		var xhr = new XMLHttpRequest();
	} else {
		if (window.ActiveXObject) {
			try {
				var xhr = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (e) { }
		}
	}

	var basename = function(path) {
		return path.replace(/\\/g,'/').replace(/.*\//, '');
	};

	var upload_fields = $('input[type=file]');

	// Grab input fields which handle images/files
	// ajax upload, grab source, inject into form.
	upload_fields.bind('change', function() {
			
		// Grab the current field
		var field = this; 

		// Handle placeholder
		var input = $(field), placeholder = input.parent().parent().find('.current-file');
		placeholder.html(basename(input.val()));

		// Create form data object
		var formData = new FormData();
		var files = field.files;

		// Go over all files for this single upload
		// field. (Usually 1)
		for (var i = 0; i < files.length; i++) {
			var file = files[i];

			if (['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'application/pdf'].indexOf(file.type) !== -1) {
				var path = window.location.pathname, uri, parts = path.split('/');

				if (parts[parts.length - 1] == 'add') {
					var uri = path.split('/').slice(0, -2).join('/') + '/upload';
				} else {
					var uri = path.split('/').slice(0, -3).join('/') + '/upload';
				}

				upload(uri, file, field);
			}
		}
	});

	var upload = function(uri, file, field) {
		xhr.open("post", uri);

		var formData = new FormData();
		formData.append('file', file);

		xhr.onreadystatechange = function() {
			if(this.readyState == 4) {
				console.log('Uploaded');
				var data = JSON.parse(this.responseText);
				console.log(data);
				$(field).parent().append('<input type="hidden" name="' + $(field).attr('name') + '" value="' + data.uri + '">');
			}
		}

		if(xhr.upload) {
			xhr.upload.onprogress = function(e) {
				// Progress
				// upload_progress(e.position || e.loaded, e.totalSize || e.total);
				console.log(e.position + ':' + e.total);
			};
		}
		else {
			xhr.addEventListener('progress', function(e) {
				// Progress
				// upload_progress(e.position || e.loaded, e.totalSize || e.total);
				console.log(e.position + ':' + e.total);
			}, false);
		}

		// Send the file (doh)
		xhr.send(formData);
	};
});