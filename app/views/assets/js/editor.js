$(function() {
	var target = $('[data-editor]'),
		md = $('[name=markdown]'),
		body = $('body'),
		val;
	
	var editor = new Pen({
		editor: target[0],
		list: ['blockquote', 'h2', 'h3', /*'p',*/ 'insertorderedlist', 'insertunorderedlist',
			   /*'indent', 'outdent',*/ 'bold', 'italic', /*'underline',*/ 'createlink'],
		stay: false /* Ask before quitting, turn back on once dev is done */
	});
	
	target.addClass('initial').on({
		focus: function() {
			target.removeClass('initial');
			body.addClass('editing');
		},

		blur: function() {
			console.log('blurry');
			body.removeClass('editing');
		},

		keyup: function() {
			var text = target.text();
		
			md.val(text);

			var characters = text.length;
			var words = text.replace(/\s{2,}/g, '').split(' ').length;
		
			var readingTime = words / 175;
			var readingMin = Math.round(readingTime);
			var readingSec = Math.round((readingTime % 60) * 60);
		
			// need sleep
			if(readingSec > 60) readingSec = 59;
			
			$('.characters').text(characters);
			$('.words').text(words);
			$('.min').text(readingMin);
			$('.sec').text(readingSec);
		}
	});

	//  Prefill our editor if it exists
	if(val = md.val()) {
		target.removeClass('initial').html(val);
	}
	
	var slug = $('[data-slugify]'),
		slugify = function(input) {
			if(input.charAt(0) === '-') {
				input = input.substr(1);
			}
			
			return input.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
		};
	
	slug.on('keyup', function(e) {
		slug.val(slugify(slug.val()));
	});
	
	var tabs = $('.tabs a');
	
	tabs.click(function() {
		var me = $(this);
		var target = me.text().split(' ')[0].toLowerCase();
		
		me.addClass('active').siblings().removeClass('active');
		
		$('.tab-' + target).show().siblings().hide();
		
		return false;
	});
	
	if(location.hash) {
		tabs.filter(':contains("' + location.hash[1].toUpperCase() + location.hash.substr(2) + '")')
			.click();
	}
	
	function toggleFocusMode(e) {
		e.preventDefault();
		
		body.toggleClass('editing');
	};
	
	$('.focus-toggle').click(toggleFocusMode);
	//$(document).bind('keydown', 'meta+e', toggleFocusMode);
});