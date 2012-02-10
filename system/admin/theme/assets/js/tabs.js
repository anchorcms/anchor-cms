(function() {
	var hide = function() {
		$$('.tab').setStyles({
			'display': 'none'
		});

		$$('.tabs a').removeClass('active');
	};

	var show = function(id) {
		$$('[data-tab=' + id + ']').setStyles({
			'display': 'block'
		});

		$$('a[href$=#' + id + ']').addClass('active');
	};

	var tab = function() {
		var id = this.get('href').split('#').pop();

		hide();
		show(id);
	};

	// hide all
	hide();

	// show first
	var hash = window.location.hash, first = hash.length ? hash.split('#').pop() : 'post';
	show(first);

	// bind to menu
	$$('.tabs a').addEvent('click', tab);
}());