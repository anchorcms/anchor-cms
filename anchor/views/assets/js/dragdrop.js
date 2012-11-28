/*
	Drag and Drop upload
*/

/*
(function() {
	// Set some useful elements to, uh, use later
	var doc = $(document), html = $('html'), body = $('body');

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

			e.stopPropagation();
			e.preventDefault();
		}
	};

	Draggy.supported && Draggy.init();
}());
*/