(function() {
	var elements = document.querySelectorAll('[data-confirm]');

	var restore = function() {
		// confirm has expired
		this.classList.remove('confirm');

		// restore html
		this.innerHTML = this.getAttribute('data-html');
	};

	var confirm = function(event) {
		if(false === this.classList.contains('confirm')) {
			event.preventDefault();

			// copy contents
			this.setAttribute('data-html', this.innerHTML);

			// add confirm class to test for next click
			this.classList.add('confirm');

			// update message
			this.innerHTML = this.getAttribute('data-confirm');

			// start timer to restore
			clearTimeout(this.confirmTimer);
			this.confirmTimer = setTimeout(restore.bind(this), 3000);
		}
	};

	Array.prototype.forEach.call(elements, function(element, index) {
		element.addEventListener('click', confirm, false);
	});

	var idleTimer,
		idle = 45, // secs
		title = document.getElementsByTagName('title')[0],
		message = title.innerHTML;

	window.addEventListener('blur', function() {
		idleTimer = setTimeout(function() {
			title.innerHTML = 'Are you still there?';
		}, idle * 1000);
	}, false);

	window.addEventListener('focus', function() {
		clearTimeout(idleTimer);
		title.innerHTML = message;
	}, false);
})();
