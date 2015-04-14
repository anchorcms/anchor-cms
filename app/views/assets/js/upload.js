(function($) {
	'use strict';

	var defaults = {className: 'hover'},
		options = {};

	var holder = $('.collection');
	var progress = $('<span class="progress">0</span>');
	$('.media .wrap').append(progress);

	var upload = function(form) {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '/admin/upload');

		xhr.onload = function() {
			progress.html(100);
		};

		xhr.upload.onprogress = function(event) {
			if(event.lengthComputable) {
				var loaded = event.loaded / event.total, complete = parseInt(loaded * 100, 10);
				progress.html(complete);
			}
		};

		xhr.send(form);
	};

	var preview = function(file) {
		var reader = new FileReader();

		reader.onload = function(event) {
			var image = new Image();
			image.src = event.target.result;

			var fig = $('<figure>');
			fig.append(image);

			holder.append(fig);
		};

		reader.readAsDataURL(file);
	};

	var readfiles = function(files) {
		var formData = new FormData();

		for (var i = 0; i < files.length; i++) {
			formData.append(i, files[i]);
			preview(files[i]);
		}

		upload(formData);
	};

	$.fn.dnd = function(settings) {
		options = $.extend(defaults, settings);

		this.on('dragover', function(event) {
			$(this).addClass(options.className);
		});

		this.on('dragleave', function(event) {
			$(this).removeClass(options.className);
		});

		this.on('drop', function(event) {
			event.preventDefault();

			$(this).removeClass(options.className);

			readfiles(event.originalEvent.dataTransfer.files);
		});

		return this;
	};
})(jQuery);
