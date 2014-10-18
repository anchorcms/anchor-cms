/**
 * Zepto plugin to create textareas into markdown editors
 */
;(function($) {
	$.fn.editor = function() {

		var
			options = arguments[1] || {},
			defaults = {},

			settings = $.extend({}, defaults, options),

			textarea = $(this),
			container = textarea.parent(),
			buttons   = $('.buttons'),

			insert = function(str) {
				var
					element = textarea[0],
					start = element.selectionStart,
					value = element.value
				;

				element.value = value.substring(0, start) + str + value.substring(start);

				element.selectionStart = element.selectionEnd = start + str.length;
			},

			wrap = function(left, right) {
				var
					element = textarea[0],
					start = element.selectionStart,
					end = element.selectionEnd,
					value = element.value
				;

				element.value = value.substring(0, start) + left + value.substring(start, end) + right + value.substring(end);

				element.selectionStart = end + left.length + right.length;
			},

			tab = function(event) {
				var
					element = textarea[0],
					start = element.selectionStart,
					end = element.selectionEnd,
					value = element.value,
					selections = value.substring(start, end).split("\n")
				;

				for (var i = 0, j = selections.length; i < j; i++) {
					selections[i] = "\t" + selections[i];
				}

				element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);

				if(end > start) {
					element.selectionStart = start;
					element.selectionEnd = end + selections.length;
				}
				else element.selectionStart = element.selectionEnd = start + 1;
			},

			untab = function(event) {
				var
					element = textarea[0],
					start = element.selectionStart,
					end = element.selectionEnd,
					value = element.value,
					pattern = new RegExp(/^[\t]{1}/),
					edits = 0
				;

				// single line
				if(start == end) {
					// move to the start of the line
					while(start > 0) {
						if(value.charAt(start) == "\n") {
							start++;
							break;
						}

						start--;
					}

					var
						portion = value.substring(start, end),
						matches = portion.match(pattern)
					;

					if(matches) {
						element.value = value.substring(0, start) + portion.replace(pattern, '') + value.substring(end);
						end--;
					}

					element.selectionStart = element.selectionEnd = end;
				}
				// multiline
				else {
					var selections = value.substring(start, end).split("\n");

					for(var i = 0, j = selections.length; i < j; i++) {
						if(selections[i].match(pattern)) {
							edits++;
							selections[i] = selections[i].replace(pattern, '');
						}
					}

					element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);

					element.selectionStart = start;
					element.selectionEnd = end - edits;
				}
			},

			controls = {

				bold: function() {
					wrap('**', '**');
				},

				italic: function() {
					wrap('*', '*');
				},

				code: function() {
					wrap('`', '`');
				},

				link: function() {
					var
						element = textarea[0],
						start = element.selectionStart,
						end = element.selectionEnd,
						value = element.value,
						selection = value.substring(start, end),
						link = '[' + selection + '](' + selection + ')'
					;

					element.value = value.substring(0, start) + link + value.substring(end);
					element.selectionStart = element.selectionEnd = end + link.length;
				},

				list: function() {
					var
						element = textarea[0],
						start = element.selectionStart,
						end = element.selectionEnd,
						value = element.value,
						selections = value.substring(start, end).split("\n")
					;

					for(var i = 0, j = selections.length; i < j; i++) {
						selections[i] = '* ' + selections[i];
					}

					element.value = value.substring(0, start) + "\n" + selections.join("\n") + "\n" + value.substring(end);
				},

				quote: function() {
					var
						element = textarea[0],
						start = element.selectionStart,
						end = element.selectionEnd,
						value = element.value,
						selections = value.substring(start, end).split("\n")
					;

					for(var i = 0, j = selections.length; i < j; i++) {
						selections[i] = '> ' + selections[i];
					}

					element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);
				},

				image: function() {
					var
						element = textarea[0],
						start = element.selectionStart,
						end   = element.selectionEnd,
						value = element.value,
						selection = value.substring(start, end),
						image = '![' + selection + '](' + selection + ')'
					;

					element.value = value.substring(0, start) + image + value.substring(end);
					element.selectionStart = element.selectionEnd = end + image.length;

				}
			}
		;

		textarea.on('keydown', function(event) {
			var key = event.keyCode || event.which;

			if(key === 9) {
				event.preventDefault();
				event.stopPropagation();

				if(event.shiftKey && key === 9) {
					untab(event);
				}
				else {
					tab(event);
				}
			}
		});

		/**
		 * Any of the editor buttons who's href start w/#
		 */
		container.on('click', 'nav a[href^="#"]', function(event) {
			var
				a = $(event.target),
				method = a.attr('href').split('#').pop()
			;

			if (controls[method] !== undefined) controls[method]();

			return false;
		});

		buttons.on('click', '.preview', function(event) {
			var
				that = $(event.target),
				container = $('.main'),
				token = $('input[name="token"]').val(),
				html = $('textarea[name="html"]').val(),

				/**
				 * Show a preview of the current writing,
				 * switch the button text to "Continue Writing"
				 *
				 * @param content
				 */
				show = function(content) {

					that
						.text('Continue Writing')
						.removeClass('show')
						.addClass('hide')
					;

					$('.wrap', container).eq(0).css('display', 'none');
					container.append(content);

				},

				/**
				 * Hide the preview, switch the button text back to "preview"
				 */
				hide = function() {

					that
						.text('Preview')
						.removeClass('hide')
						.addClass('show')
					;

					$('.wrap', container).eq(0).css('display', 'block');
					$('.main .wrap.preview').remove();

				}
			;

			if (that.hasClass('hide')) {

				hide();

			} else {

				$.ajax({
					url: '/admin/posts/edit/preview',
					data: {
						token: token,
						html: html
					},
					success: function(response) {
						show(response);
					}
				});

			}
		})
	};
}(Zepto));

/**
 * AJAX form and keyboard shortcuts
 */
;(function($) {
	var
		zone = $(document),
		form = $('form').first(),
		submit = form.find('button[type=submit]'),
		submitText = submit.html(),
		submitProgress = submit.data('loading'),
		logo = $('.logo a'),
		wrapper = $('.header .wrap'),
		title = document.title
	;

	// Press `CTRL + S` to `Save`
	zone.on('keydown', function(event) {
		var key = event.keyCode || event.which;
		if (event.ctrlKey && key == 83) {
			form.trigger('submit');
			return false;
		}
	});

	// AJAX form submit
	form.on('submit', function() {
		var data = $(this).serializeArray();

		submit.prop('disabled', true).css('cursor', 'wait').html(submitProgress);

		document.title = submitProgress;

		$.ajax({
			url: form.attr('action'),
			type: "POST",
			data: data,
			success: function(data, textStatus, jqXHR) {
				var notification = $(data).find('.notifications').clone(true),
					message = notification.children().first().text();

				wrapper.prepend(notification);

				document.title = message;

				setTimeout(function() {
					notification.animate({
						opacity: 0
					}, 600, "ease-out", function() {
						if (/\/add/i.test(form.attr('action')) && $(this).find('.error').length === 0) {
							// redirect to posts list on success if we are not in edit/update mode
							window.location.href = logo.attr('href');
						}
						$(this).remove();
					});
					document.title = title;
				}, 3000);

				submit.prop('disabled', false).html(submitText).removeAttr('style');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var notification = $('<div class="notifications"><p class="error">Error</p></div>');

				wrapper.prepend(notification);

				document.title = "Error";

				setTimeout(function() {
					notification.animate({
						opacity: 0
					}, 600, "ease-out", function() {
						$(this).remove();
					});
					document.title = title;
				}, 3000);

				submit.prop('disabled', false).html(submitText).removeAttr('style');
			}
		});

		return false;
	});
}(Zepto));
