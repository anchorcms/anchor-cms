(function() {
	var preview, button = document.querySelector('.content-preview-toggle'),
		editor = document.querySelector('.content-editor textarea');

	var parse = function(content, ready) {
		var request = new XMLHttpRequest();
		request.open('POST', '/admin/content/preview', true);
		request.onload = function() {
			ready(JSON.parse(request.responseText));
		};

		var form = new FormData();
		form.append('content', content);

		request.send(form);
	};

	var toggle = function(event) {
		button.classList.toggle('button--danger');

		event.preventDefault();

		if(preview = editor.parentNode.querySelector('.content-preview')) {
			editor.classList.remove('display--hidden');
			return editor.parentNode.removeChild(preview);
		}

		var div = document.createElement('div');
		div.className = 'content-preview';
		div.innerHTML = '<p class="text--center">Loading...</p>';

		parse(editor.value, function(response) {
			div.innerHTML = response.html;
		});

		editor.classList.add('display--hidden');
		editor.parentNode.appendChild(div);
	};

	button.addEventListener('click', toggle, false);
})();
