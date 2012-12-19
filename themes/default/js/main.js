var Anchor = {
	init: function() {
		//  Set up the slidey panel
		Anchor.hideSlidey();
		
		//  Set up the toggle link
		Anchor.linky = $('.linky').click(Anchor.toggleSlidey);
			  
		//  Hide the thingymabob
		setTimeout(function() {
			$('body').addClass('js-enabled');
		}, 10);
		
		//  Listen for search links
		$('a[href="#search"]').click(function() {
			if(!Anchor.linky.hasClass('active')) {
				return Anchor.toggleSlidey.call(Anchor.linky);
			}
		});
	},
	
	hideSlidey: function() {
		this.slidey = $('.slidey');
		this._slideyHeight = -(this.slidey.height() + 1);
		
		this.slidey.css('margin-top', this._slideyHeight);
		
		return this;
	},
	
	toggleSlidey: function() {
		var self = Anchor;
		var me = $(this);
			
		me.toggleClass('active');
		self.slidey.css('margin-top', me.hasClass('active') ? 0 : self._slideyHeight);
		
		return false;
	}
};

//  And bind loading
$(Anchor.init);