window.Media = (function() {

	var options = {
		container: document.body,
		endpoint: '/',
		render: function(file) {
			return file.name;
		}
	};

	var append = function(files) {
		for(var i = 0; i < files.length; i++) {
			var html = options.render(files[i]);
			options.container.innerHTML += html;
		}
	};

	var prepend = function(files) {
		for(var i = files.length; i > 0; i--) {
			var html = options.render(files[i]);
			options.container.innerHTML = html + options.container.innerHTML;
		}
	};

	var fetch = function() {
		$.get(options.endpoint, function(response) {
			if(response.result) append(response.files);
		});
	};

	var update = function(date) {
		$.get(options.endpoint, {since: date}, function(response) {
			if(response.result) prepend(response.files);
		});
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
		},
		fetch: fetch,
		update: update
	};

})();
