
(function() {
	var tabs = $$('.tab'), 
		a = $$('.tabs a');

	var hide = function() {
		tabs.each(function() {
			this.css('display', 'none');
		});

		a.each(function() {
			this.removeClass('active');
		});
	};

	var show = function(id) {
		$('div[data-tab=' + id + ']').css('display', 'block');
		$('a[href$=' + id + ']').addClass('active');
	};

	var has = function(id) {
		var arr = [];

		tabs.each(function() {
			this.push(this.get('data-tab'));
		});

		return arr.indexOf(id) != -1;
	};

	var tab = function(event) {
		var id = this.get('href').split('#').pop();
		hide();
		show(id);
	};

	// hide all
	hide();

	// show first
	var hash = window.location.hash, def = 'post';

	if(hash.length) {
		var t = hash.split('#').pop();

		if(has(t)) {
			show(t);
		} else {
			show(def);
		}
	} else {
		show(def);
	}

	// bind to menu
	a.each(function() {
		this.bind('click', tab);
	});
	
}());
