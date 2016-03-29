(function() {
	var timer;

	var toolbar = document.querySelector('.content-toolbar'),
		editor = document.querySelector('.content-editor'),
		body = document.querySelector('body');

	var bodyRect = body.getBoundingClientRect(),
		toolbarRect = toolbar.getBoundingClientRect(),
		editorRect = editor.getBoundingClientRect(),
		min = toolbarRect.top - bodyRect.top,
		max = editorRect.bottom;

	editor.querySelector('textarea').addEventListener('resize', function() {
		editorRect = editor.getBoundingClientRect();
		max = editorRect.bottom;
	});

	var scroll = function() {
		var winOffset = window.pageYOffset;

		if(winOffset > min && winOffset < max) {
			toolbar.classList.add('content-toolbar--float');
			editor.style.paddingTop = toolbar.offsetHeight + 'px';
		}
		else {
			toolbar.classList.remove('content-toolbar--float');
			editor.style.paddingTop = 0;
		}
	};

	window.addEventListener('scroll', function() {
		clearTimeout(timer);
		timer = setTimeout(scroll, 10);
	}, false);
})();
