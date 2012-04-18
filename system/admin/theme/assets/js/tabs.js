
(function() {
	var tabs = $$('.tab'), 
		a = $$('.tabs a');

	var hide = function() {
		tabs.each(function(itm) {
			itm.css('display', 'none');
		});

		a.each(function(itm) {
			itm.removeClass('active');
		});
	};

	var show = function(id) {
		$('div[data-tab=' + id + ']').css('display', 'block');
		$('a[href$=' + id + ']').addClass('active');
	};

	var has = function(id) {
		var arr = [];

		tabs.each(function(tab) {
			arr.push(tab.get('data-tab'));
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
	a.each(function(itm) {
		itm.bind('click', tab);
	});

}());
