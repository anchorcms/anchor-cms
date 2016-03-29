(function() {
	var elements = document.querySelectorAll('.media-library-toggle'),
		overlay = document.querySelector('.media-overlay'),
		container = document.querySelector('.media-container'),
		sidebar = container.querySelector('.media-sidebar'),
		editor = document.querySelector('.content-editor textarea');

	var timer, inProgress = 0, fileQueue = [];

	var upload = function(file) {
		// set a file id
		file.id = 'file-' + file.size;

		var request = new XMLHttpRequest();
		request.open('POST', '/admin/media/upload', true);

		request.onload = function() {
			var response;

			try {
				response = JSON.parse(request.responseText);
			}
			catch(e) {}

			completed(file, response);
		};

		request.upload.onprogress = function(event) {
			if(event.lengthComputable) {
				progress(file, event.loaded, event.total);
			}
		};

		var form = new FormData();
		form.append('file', file);

		request.send(form);
		start(file);
	};

	var start = function(file) {
		inProgress += 1;

		var div = document.createElement('div');
		div.setAttribute('data-file', file.id);
		div.classList.add('media-queue-item');
		div.innerHTML = '<span class="media-queue-item-progress">0%</span>';
		sidebar.querySelector('.media-queue').appendChild(div);
	};

	var progress = function(file, sofar) {
		var progress = sidebar.querySelector('[data-file='+file.id+'] .media-queue-item-progress');
		progress.innerHTML = Math.round((sofar / file.size) * 100) + '%';
	};

	var completed = function(file, response) {
		inProgress -= 1;

		append(response.path);

		setTimeout(function() {
			var item = sidebar.querySelector('[data-file='+file.id+']');
			item.parentNode.removeChild(item);
		}, 1000);
	};

	var finished = function() {
		setTimeout(function() {
			sidebar.classList.remove('media-sidebar--extended');
		}, 1000);
	};

	var append = function(path) {
		var name = path.split('/').pop();
		var div = document.createElement('div');
		div.classList.add('media-list-item');
		div.innerHTML = '<a class="media-list-item-link" href="#"><img src="'+path+'"><span>'+name+'</span></a>';
		container.querySelector('.media-list').appendChild(div);
	};

	var checkQueue = function() {
		clearTimeout(timer);

		if(inProgress < 1 && fileQueue.length > 0) {
			upload(fileQueue.shift());
		}

		if(fileQueue.length) {
			timer = setTimeout(checkQueue, 500);
		}
		else {
			finished();
		}
	};

	var queue = function(file) {
		fileQueue.push(file);
		checkQueue();
	};

	var queueFiles = function(files) {
		for(var i = 0; i < files.length; i++) {
			queue(files[i]);
		}
	};

	var drop = function(event) {
		event.preventDefault();
		queueFiles(event.dataTransfer.files);
	};

	var over = function(event) {
		event.preventDefault();
		sidebar.classList.add('media-sidebar--extended');
	};

	var leave = function(event) {
		event.preventDefault();
		sidebar.classList.remove('media-sidebar--extended');
	};

	var bind = function(element) {
		element.addEventListener('dragover', over, false);
		element.addEventListener('dragenter', over, false);
		element.addEventListener('dragleave', leave, false);
		element.addEventListener('drop', drop, false);
	};

	bind(container);

	var open = function() {
		overlay.classList.remove('display--hidden');
		container.classList.remove('display--hidden');
		populate();
	};

	var close = function() {
		overlay.classList.add('display--hidden');
		container.classList.add('display--hidden');
	};

	var toggle = function(event) {
		event.preventDefault();
		if(overlay.classList.contains('display--hidden')) {
			open();
		}
		else {
			close();
		}
	};

	Array.prototype.forEach.call(elements, function(element) {
		element.addEventListener('click', toggle, false);
	});

	var pick = function(element) {
		var src = element.querySelector('img').getAttribute('src'),
			alt = element.querySelector('span').innerText;

		var text = '!['+alt+']('+src+')';

		if(editor.hasAttribute('data-caret-pos')) {
			var content = editor.value,
				pos = editor.getAttribute('data-caret-pos');

			editor.value = content.slice(0, pos) + text + content.slice(pos);
		}
		else {
			editor.value += '!['+alt+']('+src+')';
		}

		close();
	};

	var select = function(event) {
		for(var i = 0; i < event.path.length; i++) {
			var element = event.path[i];

			if(element.classList.contains('media-list-item-link')) {
				event.preventDefault();
				return pick(element);
			}
		}
	};

	container.querySelector('.media-body').addEventListener('click', select, false);

	var populated = false;

	var populate = function() {
		if(populated) return;

		var request = new XMLHttpRequest();
		request.open('GET', '/admin/media', true);
		request.onload = function() {
			try {
				var response = JSON.parse(request.responseText);
				Array.prototype.forEach.call(response.files, function(file) {
					append('/content/' + file.name);
				});
				populated = true;
			}
			catch(e) {}
		};
		request.send();
	};

})();
