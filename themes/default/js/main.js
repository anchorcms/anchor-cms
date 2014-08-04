var Anchor = {
	init: function() {
		Anchor.slidey = $('.slidey');
		Anchor.keys = [];

		//  Uh, bind to the resizing of the window?
		$(window).resize(Anchor.bindResize).trigger('resize');

		// Re-/Set keys
		$(window).on('keyup', Anchor.keyup);
		$(window).on('keydown', Anchor.keydown);

		//  Set up the toggle link
		Anchor.linky = $('.linky').on('click', Anchor.toggleSlidey);

		//  Hide the thingymabob
		setTimeout(function() {
			//  Set up the slidey panel
			Anchor.hideSlidey();

			$('body').addClass('js-enabled');
		}, 10);

		//  Listen for search link
		$('a[href="#search"]').click(function() {
			if(!Anchor.linky.hasClass('active')) {
				return Anchor.toggleSlidey.call(Anchor.linky);
			}
		});
	},

	keyup: function(event) {
		Anchor.keys[event.keyCode] = false;
	},

	keydown: function(event) {
		Anchor.keys[event.keyCode] = true;

		// ctrl + shift + f => show Slidey and/or focus search bar
		if(Anchor.keys[17] && Anchor.keys[16] && Anchor.keys[70]) {
			event.preventDefault();

			Anchor.showSlidey.call(Anchor.linky);
			$('input[type="search"]').focus();
		}

		// esc => hide Slidey
		if(Anchor.keys[27]) {
			event.preventDefault();

			Anchor.hideSlidey();
			$('input[type="search"]').blur();
		}
	},

	hideSlidey: function() {
		Anchor.slidey.css('margin-top', this._slideyHeight);
		Anchor.linky && Anchor.linky.removeClass('active');

		return this;
	},

	showSlidey: function() {
		Anchor.slidey.css('margin-top', 0);
		Anchor.linky && Anchor.linky.addClass('active');

		return this;
	},

	toggleSlidey: function() {
		var self = Anchor;
		var me = $(this);

		me.toggleClass('active');
		self.slidey.css('margin-top', me.hasClass('active') ? 0 : self._slideyHeight);

		return false;
	},

	bindResize: function() {
		Anchor._slideyHeight = -(Anchor.slidey.height() + 1);
		Anchor.hideSlidey();
	}
};

//  And bind loading
$(Anchor.init);
