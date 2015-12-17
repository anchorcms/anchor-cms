(function(d, w) {
	var upload = function(file, callback) {
		var token = d.querySelector('input[name=token]').getAttribute('value');

		var request = new XMLHttpRequest();
		request.open('POST', '/admin/media/upload?t='+token, true);

		request.onload = function() {
			try {
				var response = JSON.parse(request.responseText);
				callback(response);
			}
			catch(e) {}
		};

		var form = new FormData();
		form.append('file', file);

		request.send(form);
	};

	var selected = function(event) {
		var input = event.target,
			name = input.getAttribute('name').substring(1),
			target = d.querySelector('input[name=' + name + ']');

		if(input.files.length) {
			upload(input.files[0], function(response) {
				target.setAttribute('value', response.path);
				preview(input);
			});
		}
	};

	var preview = function(input) {
		var name = input.getAttribute('name').substring(1),
			target = d.querySelector('input[name=' + name + ']'),
			value = target.getAttribute('value');

		if(value.length) {
			var img = new Image();
			img.src = value;
			input.parentNode.parentNode.appendChild(img);
		}
	};

	var inputs = d.querySelectorAll('input[type=file]');

	w.addEventListener('DOMContentLoaded', function() {
		Array.prototype.forEach.call(inputs, function(input) {

			input.addEventListener('change', selected);

			preview(input);

		});
	});

})(document, window);
