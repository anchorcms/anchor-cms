
/*
	Confirm any deletions
*/
$('.delete').click(function() {
	return confirm('Are you sure you want to delete? This can’t be undone!');
});

/*
	Dropdown fix
*/
$('select').after('<span class="arrow" />');

/*
	Autofocusing first input
*/
if( ! $('[autofocus]').length) {
	$('input:first-child').attr('autofocus', 'autofocus').focus();
}

/*
	Drag and Drop upload
*/
(function() {
	// Set some useful elements to, uh, use later
	var doc = $(document), html = $('html'), body = $('body');

	/*
		Drag-n-drop upload
	*/
	var Draggy = {
		supported: window.FileReader && window.File,
		allowed: ['text/css', 'text/javascript', 'application/javascript'],

		defaultText: 'Upload your file',

		init: function() {
			$('.media-upload').hide();

			Draggy.el = body.append('<div id="upload-file"><span>' + Draggy.defaultText + '</span></div>').children('#upload-file');

			doc.on('dragover', Draggy.hover);

			doc.on('dragleave dragexit', function(e) {
				if(e.pageX == 0) {
					Draggy.close();
				}
			});

			doc.on('drop', Draggy.handle);
		},

		close: function() {
			html.removeClass('draggy');
			Draggy.el.removeClass('success').children('span').text(Draggy.defaultText);
		},

		hover: function() {
			html.addClass('draggy');
		},

		handle: function(e) {
			e.stopPropagation();
			e.preventDefault();

			var files = (e.target.files || e.dataTransfer.files)[0];

			if($.inArray(files.type, Draggy.allowed) !== -1) {
				var reader = new FileReader;

				reader.onloadend = function(e) {
					if(e.target.readyState == FileReader.DONE) {
						var type = files.type === 'text/css' ? 'css' : 'js';

						$('#' + type).val(e.target.result);
						Draggy.el.addClass('success').children('span').text('Custom ' + type.toUpperCase() + ' added!');

						setTimeout(Draggy.close, 1250);
					}
				};

				reader.readAsBinaryString(files);
			} else {
				Draggy.close();
			}
		}
	};

	Draggy.supported && Draggy.init();
}());

/*
	Autosave
*/

/*
(function() {
	// Set some useful elements to, uh, use later
	var body = $('body'), textarea = $('#post-content'), slug = $('input[name=slug]');

	var autosave = function() {
		var key = slug.val();
		var val = textarea.val();

		if(key && val && window.localStorage) {
			localStorage.setItem('anchor-' + key, val);

			if( ! body.children('.piggy').length) {
				body.append('<div class="piggy" style="opacity: 0" />');
			}

			var piggy = body.children('.piggy').animate({opacity: 1}, 150);

			setTimeout(function() {
				piggy.animate({opacity: 0}, 150);
			}, 800);
		}
	};

	setInterval(autosave, 5000);
}());
*/

/*
	Focus mode
*/
(function() {
	var doc = $(document), html = $('html');

	var Focus = {
		//  Our element to focus
		target: $('#post-content, .header input'),

		enter: function() {
			html.addClass('focus');

			//  Set titles and placeholders
			Focus.target.placeholder = (Focus.target.placeholder || '').split('.')[0] + '.';
		},

		exit: function() {
			html.removeClass('focus');
		}
	};

	//  Bind textarea events
	Focus.target.focus(Focus.enter).blur(Focus.exit);

	//  Bind key events
	doc.keyup(function(e) {
		//  Pressing the "f" key
		if($.inArray(e.target.nodeName, ['INPUT', 'TEXTAREA']) === -1 && e.which == 70) {
			Focus.enter();
		}

		//  Pressing the Escape key
		if(e.which == 27) {
			Focus.exit();
		}
	});
}());

/*
	Post previewing
*/
(function() {
	var buttons = $('.header .buttons'), prevue = $('.prevue'), textarea = $('#post-content'),
		post = $('#post-data'), content = $('#content');

	var load = function(e) {
		var html = textarea.val(), me = $(this);

		// already in preview mode
		if(prevue.hasClass('active')) {
			me.toggleClass('blue');
			prevue.toggleClass('active');
			post.toggle();
			content.toggle();

			return false;
		}

		if(html) {
			// build uri
			var parts = document.location.pathname.split('/');
			while(parts[parts.length - 1] != 'posts') parts.pop();
			var uri = parts.join('/') + '/preview';

			me.removeClass('disabled');

			$.post(uri, {'html': html}, function(response) {
				prevue.children('.wrap').html(response.html);
				me.toggleClass('blue');
				prevue.toggleClass('active');
				post.toggle();
				content.toggle();
			});
		} else {
			me.addClass('disabled');
		}

		return false;
	};

	buttons
		.append('<a href="#" class="secondary btn disabled">Preview</a>')
		.children('.secondary')
		.bind('click', load);

	//  Disabling the preview button
	textarea.keyup(function() {
		if(textarea.val() !== '') {
			buttons.children('.disabled').removeClass('disabled');
		} else {
			buttons.children('.secondary').addClass('disabled');
		}
	});
}());

/*
	Slugs
*/
(function() {
	var title = $('input[name=title]'), slug = $('input[name=slug]');
	var changedSlug = false;

	var string_to_slug = function(str) {
		str = str.replace(/^\s+|\s+$/g, ''); // trim
		str = str.toLowerCase();

		// remove accents, swap ñ for n, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		var to   = "aaaaeeeeiiiioooouuuunc------";

		for(var i = 0, l = from.length; i < l; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		}

		return str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by -
			.replace(/-+/g, '-'); // collapse dashes
	}

	slug.bind('keyup', function() {
		changedSlug = true;
	});

	title.bind('keyup', function() {
		if( ! changedSlug) {
			slug.val(string_to_slug(title.val()));
		}
	});
}());

/*
	Extend attirubte selection
*/
(function() {
	var select = $('#field'), attrs = $('.hide');

	var update = function() {
		var value = select.val();

		attrs.hide();

		if(value == 'image') {
			attrs.show();
		}
		else if(value == 'file') {
			$('.attributes_type').show();
		}
	};

	select.bind('change', update);

	update();
}());

/*
	Show redirect url on Pages
*/
(function() {
	var c = $('#redirect'), r = $('#redirect_url').parent();

	var update = function() {
		if(c.prop('checked')) {
			r.show();
		}
		else {
			r.hide();
		}
	};

	c.bind('change', update);
	update();
}());