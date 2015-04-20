window.Upload = (function() {

	var options = {
		bind: document.body,
		endpoint: '/',
		start: function() {},
		progress: function() {},
		complete: function() {}
	};

	var support = function() {
		return 'DataTransfer' in window;
	}

	var cancel = function(event) {
		event.stopPropagation();
		event.preventDefault();
	};

	var upload = function(file) {
		var form = new FormData();
		form.append('file', file);

		options.start(file);

		var xhr = new XMLHttpRequest();
		xhr.open('POST', options.endpoint, true);

		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				options.progress(file, 100);
			}
		};

		xhr.onload = function() {
			var response;

			try {
				response = JSON.parse(xhr.responseText);
			}
			catch(e) {}

			options.complete(response);
		};

		xhr.upload.onprogress = function(event) {
			if(event.lengthComputable) {
				var loaded = event.loaded / event.total, complete = parseInt(loaded * 100, 10);
				options.progress(file, complete);
			}
		};

		xhr.send(form);
	};

	var readfiles = function(files) {
		for (var i = 0; i < files.length; i++) {
			upload(files[i]);
		}
	};

	var drop = function(event) {
		event.preventDefault();
		readfiles(event.dataTransfer.files);
	};

	var bind = function(element) {
		element.addEventListener('dragover', cancel, false);
		element.addEventListener('dragenter', cancel, false);
		element.addEventListener('drop', drop, false);
	};

	var extend = function(ext, obj) {
		for (var i in obj) {
			if (obj.hasOwnProperty(i)) {
				ext[i] = obj[i];
			}
		}
	};

	return {
		setup: function() {
			extend(options, arguments[0] || {});
			bind(options.bind);
		}
	};

})();
