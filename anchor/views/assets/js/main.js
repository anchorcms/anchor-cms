"use strict";

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * From @TheBrenny:
 *      This JS File holds the functions that are used to determine whether or
 *      not we should turn on/off autosave, and determines whether or not
 *      autosave is active.
 */

$(document).ready(function () {
	var autosaveInterval;
	var maxSeconds = 30;
	var secondsPassed = 0;

	var onInterval = function onInterval() {
		secondsPassed++;
		if (secondsPassed > maxSeconds) {
			secondsPassed = 0;
			submitDocument();
		}
		$(".autosave-label").text("Autosave in " + (maxSeconds - secondsPassed));
	};

	var submitDocument = function submitDocument() {
		$("form").first().trigger("submit");
	};

	var alterAutosaveActionButton = function alterAutosaveActionButton() {
		var pressOn = autosaveInterval !== null;
		$(".autosave-action").toggleClass("green", pressOn);
		$(".autosave-action").toggleClass("autosave-on", pressOn);
		$(".autosave-action").toggleClass("secondary", !pressOn);
		$(".autosave-label").text(pressOn ? "Autosave in 30" : "Autosave: Off");
		/*
  if(pressOn) { // Just turned on autosave
  	$(".autosave-action").addClass("green");
  	$(".autosave-action").removeClass("secondary");
  	$(".autosave-label").text("Autosave in 30");
  } else { // Just turned off autosave
  	$(".autosave-action").addClass("secondary");
  	$(".autosave-action").removeClass("green");
  	$(".autosave-label").text("Autosave: Off");
  }
  */
	};

	$(".autosave-action").click(function () {
		if (autosaveInterval === null) {
			autosaveInterval = setInterval(function () {
				onInterval();
			}, 1000);
		} else {
			clearInterval(autosaveInterval);
			autosaveInterval = null;
			secondsPassed = 0;
		}
		alterAutosaveActionButton();
	});
});
/* Prompts the user if they attempt to leave and there
 * are still unsaved changes on important fields
 */
(function ($) {
	/* first and only argument should be selector for which fields to check within form */
	$.fn.changeSaver = function () {
		var form = $(this);
		var submitted = false;
		var value_store = [];
		var field_selector = arguments[0] || "input[type=text], textarea"; //by default save all text inputs

		form.find(field_selector).forEach(function (item, index) {
			value_store.push({
				element: item,
				original_value: $(item).val()
			});
		});

		function hasDiffs() {
			for (var i = 0; i < value_store.length; i++) {
				var input = value_store[i];
				if (input.original_value != $(input.element).val()) {
					return true;
				}
			}
			return false;
		}

		$(form).on("submit", function () {
			submitted = true;
		});

		$(window).on("beforeunload", function () {
			if (!submitted && hasDiffs()) {
				return "There are unsaved changes";
			}
		});
	};
})(Zepto);

/**
 * Extend attribute selection
 *
 * Show/hide fields depending on type
 */
$(function () {
	var select = $('#label-field'),
	    attrs = $('.hide'),
	    fieldtype = $('#label-type'),
	    pagetype = $('#pagetype');

	var update = function update() {
		var value = select.val();

		attrs.hide();

		if (value == 'image') {
			attrs.show();
		} else if (value == 'file') {
			$('.attributes_type').show();
		}
	};

	select.bind('change', update);

	var typechange = function typechange() {
		var value = fieldtype.val();

		if (value == 'page') {
			pagetype.parent().show();
		} else {
			pagetype.parent().hide();
			pagetype.val('all');
		}
	};

	fieldtype.bind('change', typechange);

	update();
});
/**
 * Drag and Drop upload
 *
 * Allows the drag and drop of single files into posts
 */
$(function () {
	var zone = $(document),
	    body = $('body'),
	    uploader = $('<div id="upload-file"><span>Upload your file</span></div>');

	var allowed = ['text/css', 'text/javascript', 'application/javascript', 'text/x-markdown', 'application/pdf', 'image/jpeg', 'image/gif', 'image/png', 'image/bmp'];

	var debug = function debug(message) {
		if (window.console) console.log(message);
	};

	var cancel = function cancel(event) {
		uploader.hide().removeClass('active');
		event.preventDefault();
		return false;
	};

	var open = function open(event) {
		event.preventDefault();
		uploader.show().addClass('active');
		return false;
	};

	var close = function close(event) {
		event.preventDefault();
		uploader.hide().removeClass('active');
		return false;
	};

	var drop = function drop(event) {
		event.preventDefault();

		var files = event.target.files || event.dataTransfer.files;

		for (var i = 0; i < files.length; i++) {
			var file = files.item(i);

			if (allowed.indexOf(file.type) !== -1) {
				transfer(file);
			} else {
				debug(file.type + ' not supported');
			}
		}

		uploader.hide().removeClass('active');

		return false;
	};

	var transfer = function transfer(file) {
		var reader = new FileReader();
		reader.file = file;
		reader.callback = complete;
		reader.onload = reader.callback;
		reader.readAsBinaryString(file);
	};

	var complete = function complete() {
		if (['text/x-markdown'].indexOf(this.file.type) !== -1) {
			var textarea = $('.main textarea');

			textarea.val(this.result).trigger('keydown');
		}

		if (['text/javascript', 'application/javascript'].indexOf(this.file.type) !== -1) {
			var textarea = $('textarea[name=js]');

			textarea.val(this.result);
		}

		if (['text/css'].indexOf(this.file.type) !== -1) {
			var textarea = $('textarea[name=css]');

			textarea.val(this.result);
		}

		if (['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'application/pdf'].indexOf(this.file.type) !== -1) {
			var path = window.location.pathname,
			    uri,
			    parts = path.split('/');

			if (parts[parts.length - 1] == 'add') {
				uri = path.split('/').slice(0, -2).join('/') + '/upload';
			} else {
				uri = path.split('/').slice(0, -3).join('/') + '/upload';
			}

			upload(uri, this.file);
		}
	};

	var upload = function upload(uri, file) {
		// Uploading - for Firefox, Google Chrome and Safari
		var xhr = new XMLHttpRequest();
		xhr.open("post", uri);

		var formData = new FormData();
		formData.append('file', file);

		xhr.onreadystatechange = function () {
			if (this.readyState == 4) {
				return uploaded(file, this.responseText);
			}
		};

		if (xhr.upload) {
			xhr.upload.onprogress = function (e) {
				upload_progress(e.position || e.loaded, e.totalSize || e.total);
			};
		} else {
			xhr.addEventListener('progress', function (e) {
				upload_progress(e.position || e.loaded, e.totalSize || e.total);
			}, false);
		}

		// Send the file (doh)
		xhr.send(formData);
	};

	var upload_progress = function upload_progress(position, total) {
		if (position == total) {
			$('#upload-file-progress').hide();
		} else {
			$('#upload-file-progress').show();

			$('#upload-file-progress progress').prop('value', position);
			$('#upload-file-progress progress').prop('max', total);
		}
	};

	var uploaded = function uploaded(file, response) {
		var data = JSON.parse(response);

		if (data.uri) {
			var textarea = $('.main textarea'),
			    element = textarea[0],
			    start = element.selectionStart,
			    value = element.value,
			    fileOutput = '[' + file.name + '](' + data.uri + ')' + "\n\n";

			if (['image/jpeg', 'image/gif', 'image/png', 'image/bmp'].indexOf(file.type) !== -1) {
				fileOutput = "\n\n!" + fileOutput;
			} else {
				fileOutput = "\n\n" + fileOutput;
			}

			element.value = value.substring(0, start) + fileOutput + value.substring(start);
			element.selectionStart = element.selectionEnd = start + file.length;
			textarea.trigger('keydown');
		}
	};

	if (window.FileReader && window.FileList && window.File) {
		zone.on('dragover', open);
		zone.on('dragenter', cancel);
		zone.on('drop', drop);
		zone.on('dragleave', cancel);
		zone.on('dragexit', close);

		body.append(uploader);
		body.append('<div id="upload-file-progress"><progress value="0"></progress></div>');
	}
});

/**
 * Zepto plugin to create textareas into markdown editors
 */
;(function ($) {
	$.fn.editor = function () {

		var options = arguments[1] || {};
		var defaults = {};

		var settings = $.extend({}, defaults, options);
		var textarea = $(this),
		    container = textarea.parent();

		var insert = function insert(str) {
			var element = textarea[0];
			var start = element.selectionStart;
			var value = element.value;

			element.value = value.substring(0, start) + str + value.substring(start);

			element.selectionStart = element.selectionEnd = start + str.length;
		};

		var wrap = function wrap(left, right) {
			var element = textarea[0];
			var start = element.selectionStart,
			    end = element.selectionEnd;
			var value = element.value;

			element.value = value.substring(0, start) + left + value.substring(start, end) + right + value.substring(end);

			element.selectionStart = end + left.length + right.length;
		};

		var tab = function tab(event) {
			var element = textarea[0];
			var start = element.selectionStart,
			    end = element.selectionEnd;
			var value = element.value;

			var selections = value.substring(start, end).split("\n");

			for (var i = 0; i < selections.length; i++) {
				selections[i] = "\t" + selections[i];
			}

			element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);

			if (end > start) {
				element.selectionStart = start;
				element.selectionEnd = end + selections.length;
			} else element.selectionStart = element.selectionEnd = start + 1;
		};

		var untab = function untab(event) {
			var element = textarea[0];

			var start = element.selectionStart,
			    end = element.selectionEnd;
			var value = element.value;
			var pattern = new RegExp(/^[\t]{1}/);
			var edits = 0;

			// single line
			if (start == end) {
				// move to the start of the line
				while (start > 0) {
					if (value.charAt(start) == "\n") {
						start++;
						break;
					}

					start--;
				}

				var portion = value.substring(start, end);
				var matches = portion.match(pattern);

				if (matches) {
					element.value = value.substring(0, start) + portion.replace(pattern, '') + value.substring(end);
					end--;
				}

				element.selectionStart = element.selectionEnd = end;
			}
			// multiline
			else {
					var selections = value.substring(start, end).split("\n");

					for (var i = 0; i < selections.length; i++) {
						if (selections[i].match(pattern)) {
							edits++;
							selections[i] = selections[i].replace(pattern, '');
						}
					}

					element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);

					element.selectionStart = start;
					element.selectionEnd = end - edits;
				}
		};

		var controls = {
			bold: function bold() {
				wrap('**', '**');
			},
			italic: function italic() {
				wrap('*', '*');
			},
			code: function code() {
				var wrapping = '`';
				var element = textarea[0];
				var start = element.selectionStart,
				    end = element.selectionEnd;
				var value = element.value;

				var selection = value.substring(start, end);
				if (/\n+.*/gm.test(selection)) {
					wrapping = '\n```\n';
				}
				wrap(wrapping, wrapping);
			},
			link: function link() {
				var element = textarea[0];
				var start = element.selectionStart,
				    end = element.selectionEnd;
				var value = element.value;

				var selection = value.substring(start, end);
				var link = '[' + selection + '](' + selection + ')';

				element.value = value.substring(0, start) + link + value.substring(end);
				element.selectionStart = element.selectionEnd = end + link.length;
			},
			list: function list() {
				var element = textarea[0];
				var start = element.selectionStart,
				    end = element.selectionEnd;
				var value = element.value;

				var selections = value.substring(start, end).split("\n");

				for (var i = 0; i < selections.length; i++) {
					selections[i] = '* ' + selections[i];
				}

				element.value = value.substring(0, start) + "\n" + selections.join("\n") + "\n" + value.substring(end);
			},
			quote: function quote() {
				var element = textarea[0];
				var start = element.selectionStart,
				    end = element.selectionEnd;
				var value = element.value;

				var selections = value.substring(start, end).split("\n");

				for (var i = 0; i < selections.length; i++) {
					selections[i] = '> ' + selections[i];
				}

				element.value = value.substring(0, start) + selections.join("\n") + value.substring(end);
			}
		};

		textarea.on('keydown', function (event) {
			if (event.keyCode === 9) {
				event.preventDefault();
				event.stopPropagation();

				if (event.shiftKey && event.keyCode === 9) {
					untab(event);
				} else {
					tab(event);
				}
			}
		});

		container.on('click', 'nav a', function (event) {
			var a = $(event.target),
			    method = a.attr('href').split('#').pop();

			if (controls[method]) controls[method]();

			return false;
		});
	};
})(Zepto);

/**
 * AJAX form and keyboard shortcuts
 */
;(function ($) {
	var zone = $(document),
	    form = $('form').first(),
	    submit = form.find('button[type=submit]'),
	    submitText = submit.html(),
	    submitProgress = submit.data('loading'),
	    activeMenu = $('.top nav .active a'),
	    wrapper = $('.header .wrap'),
	    notificationWrapper = $('.notifications'),
	    title = document.title;

	// Press `CTRL + S` to `Save`
	zone.on('keydown', function (event) {
		if (event.ctrlKey && event.keyCode == 83 && !event.altKey) {
			form.trigger('submit');
			return false;
		}
	});

	// AJAX form submit
	form.on('submit', function () {
		var data = {};
		$.each($(this).serializeArray(), function (_, kv) {
			data[kv.name] = kv.value;
		});

		var didAutosave = $(".autosave-action").hasClass("autosave-on");
		data.autosave = didAutosave;

		submit.prop('disabled', true).css('cursor', 'wait').html(submitProgress);

		if (submitProgress) {
			document.title = submitProgress;
		}

		$.ajax({
			url: form.attr('action'),
			type: "POST",
			data: data,
			success: function success(data, textStatus, jqXHR) {

				data = JSON.parse(data);

				if (data.notification) {
					document.title = data.notification;

					var notification = $('<p class="success">' + data.notification + '</p>');
					notificationWrapper.append(notification);

					setTimeout(function () {
						notification.animate({
							opacity: 0
						}, 600, "ease-out", function () {
							$(this).remove();
						});
					}, 3000);
				} else if (data.errors) {
					for (index in data.errors) {
						var error = data.errors[index];
						var notification = $('<p class="error">' + error + '</p>');
						notificationWrapper.append(notification);

						setTimeout(function () {
							notification.animate({
								opacity: 0
							}, 600, "ease-out", function () {
								$(this).remove();
							});
						}, 3000);
					};
				}

				if (data.redirect && data.redirect != window.location.href) {
					setTimeout(function () {
						window.location.href = data.redirect;
					}, 1000);
				} else {
					setTimeout(function () {
						document.title = title;
					}, 3000);
				}

				submit.prop('disabled', false).html(submitText).removeAttr('style');
			},
			error: function error(jqXHR, textStatus, errorThrown) {
				var notification = $('<div class="notifications"><p class="error">Error</p></div>');
				wrapper.prepend(notification);

				setTimeout(function () {
					notification.animate({
						opacity: 0
					}, 600, "ease-out", function () {
						$(this).remove();
					});
					document.title = title;
				}, 3000);

				submit.prop('disabled', false).html(submitText).removeAttr('style');
			}
		});

		return false;
	});
})(Zepto);

/**
 * Focus mode for post and page main textarea
 */
$(function () {
	var doc = $(document),
	    html = $('html'),
	    body = html.children('body');

	var Focus = {
		//  Our element to focus
		target: $('textarea[name=html], textarea[name=content]'),
		exitSpan: '#exit-focus',

		enter: function enter() {
			html.addClass('focus');

			if (!body.children(Focus.exitSpan).length) {
				body.append('<span class="btn" id="' + Focus.exitSpan.substr(1) + '">Exit focus mode (ESC)</span>');
			}

			body.children(Focus.exitSpan).css('opacity', 0).animate({ opacity: 1 }, 250);

			//  Set titles and placeholders
			Focus.target.placeholder = (Focus.target.placeholder || '').split('.')[0] + '.';
		},

		exit: function exit() {
			body.children(Focus.exitSpan).animate({ opacity: 0 }, 250);
			html.removeClass('focus');
		}
	};

	//  Bind textarea events
	Focus.target.focus(Focus.enter).blur(Focus.exit);

	//  Bind key events
	doc.on('keyup', function (event) {
		//  Pressing the "f" key
		if (event.keyCode == 70) {
			Focus.enter();
		}

		//  Pressing the Escape key
		if (event.keyCode == 27) {
			Focus.exit();
		}
	});
});
/**
 * Mirrors the page title into the page name field which is use in the menus
 */
$(function (input, output) {
	var input = $('input[name=title]'),
	    output = $('input[name=name]');
	var changed = false;

	output.bind('keyup', function () {
		changed = true;
	});

	input.bind('keyup', function () {
		if (!changed) output.val(input.val());
	});
});
/**
 * Toggles the redirect field in pages
 */
$(function () {
	var fieldset = $('fieldset.redirect'),
	    input = $('input[name=redirect]'),
	    btn = $('button.secondary.redirector');

	var toggle = function toggle() {
		fieldset.toggleClass('show');
		if (fieldset.hasClass('show')) {
			input.removeAttr('tabindex');
		} else {
			input.attr('tabindex', '-1');
		}
		return false;
	};

	btn.bind('click', toggle);

	// Hide the input if you get rid of the content within.
	input.change(function () {
		if (input.val() === '') fieldset.removeClass('show');
	});

	// Show the redirect field if it isn't empty.
	if (input.val() !== '') {
		fieldset.addClass('show');
	}

	//If the input is hidden, it shouldn't be possible to tab to it.
	if (!input.hasClass('show')) {
		input.attr('tabindex', -1);
	}
});

/**
 * Format title into a slug value after each keypress
 * Disabled if the slug is manually changed
 */
$(function () {
	var input = $('input[name=title]'),
	    output = $('input[name=slug]');
	var slugHasValue = output.val(),
	    slugOldValue = false;

	var slugify = function slugify(str) {
		var _characterMap;

		str = String(str);
		var characterMap = (_characterMap = {
			'©': '(c)',
			'Á': 'A',
			'á': 'a',
			'À': 'A',
			'à': 'a',
			'Â': 'A',
			'â': 'a',
			'Å': 'A',
			'å': 'a',
			'Ä': 'Ae',
			'ä': 'ae',
			'Ã': 'A',
			'ã': 'a',
			'Ą': 'A',
			'ą': 'a',
			'Ā': 'A',
			'ā': 'a',
			'Æ': 'AE',
			'æ': 'ae',
			'Ć': 'C',
			'ć': 'c',
			'Č': 'C',
			'č': 'c',
			'Ç': 'C',
			'ç': 'c',
			'Ď': 'D',
			'ď': 'd',
			'Ð': 'D',
			'ð': 'd',
			'É': 'E',
			'é': 'e',
			'È': 'E',
			'è': 'e',
			'Ê': 'E',
			'ê': 'e',
			'Ě': 'E',
			'ě': 'e',
			'Ë': 'E',
			'ë': 'e',
			'Ę': 'e',
			'ę': 'e',
			'Ē': 'E',
			'ē': 'e',
			'Ğ': 'G',
			'ğ': 'g',
			'Ģ': 'G',
			'ģ': 'g',
			'Í': 'I',
			'í': 'i',
			'Ì': 'I',
			'ì': 'i',
			'Î': 'I',
			'î': 'i',
			'Ï': 'I',
			'ï': 'i',
			'İ': 'I',
			'Ī': 'i',
			'ī': 'i',
			'ı': 'i',
			'Ķ': 'k',
			'ķ': 'k',
			'Ļ': 'L',
			'ļ': 'l',
			'Ł': 'L',
			'ł': 'l',
			'Ń': 'N',
			'ń': 'n',
			'Ň': 'N',
			'ň': 'n',
			'Ñ': 'N',
			'ñ': 'n',
			'Ņ': 'N',
			'ņ': 'n',
			'Ó': 'O'
		}, _defineProperty(_characterMap, "\xD3", 'o'), _defineProperty(_characterMap, 'ó', 'o'), _defineProperty(_characterMap, 'Ò', 'O'), _defineProperty(_characterMap, 'ò', 'o'), _defineProperty(_characterMap, 'Ô', 'O'), _defineProperty(_characterMap, 'ô', 'o'), _defineProperty(_characterMap, 'Ö', 'Oe'), _defineProperty(_characterMap, 'ö', 'oe'), _defineProperty(_characterMap, 'Ő', 'O'), _defineProperty(_characterMap, 'ő', 'o'), _defineProperty(_characterMap, 'Õ', 'O'), _defineProperty(_characterMap, 'õ', 'o'), _defineProperty(_characterMap, 'Ø', 'O'), _defineProperty(_characterMap, 'ø', 'o'), _defineProperty(_characterMap, 'Ř', 'R'), _defineProperty(_characterMap, 'ř', 'r'), _defineProperty(_characterMap, 'Ś', 'S'), _defineProperty(_characterMap, 'ś', 's'), _defineProperty(_characterMap, 'Š', 'S'), _defineProperty(_characterMap, 'š', 's'), _defineProperty(_characterMap, 'Ş', 'S'), _defineProperty(_characterMap, 'ş', 's'), _defineProperty(_characterMap, 'ß', 'ss'), _defineProperty(_characterMap, 'Ť', 'T'), _defineProperty(_characterMap, 'ť', 't'), _defineProperty(_characterMap, 'Ú', 'U'), _defineProperty(_characterMap, 'ú', 'u'), _defineProperty(_characterMap, 'Ù', 'U'), _defineProperty(_characterMap, 'ù', 'u'), _defineProperty(_characterMap, 'Û', 'U'), _defineProperty(_characterMap, 'û', 'u'), _defineProperty(_characterMap, 'Ů', 'U'), _defineProperty(_characterMap, 'ů', 'u'), _defineProperty(_characterMap, 'Ü', 'Ue'), _defineProperty(_characterMap, 'ü', 'ue'), _defineProperty(_characterMap, 'Ű', 'U'), _defineProperty(_characterMap, 'ű', 'u'), _defineProperty(_characterMap, 'Ū', 'u'), _defineProperty(_characterMap, 'ū', 'u'), _defineProperty(_characterMap, 'Ý', 'Y'), _defineProperty(_characterMap, 'ý', 'y'), _defineProperty(_characterMap, 'ÿ', 'y'), _defineProperty(_characterMap, 'Ź', 'Z'), _defineProperty(_characterMap, 'ź', 'z'), _defineProperty(_characterMap, 'Ž', 'Z'), _defineProperty(_characterMap, 'ž', 'z'), _defineProperty(_characterMap, 'Ż', 'Z'), _defineProperty(_characterMap, 'ż', 'z'), _defineProperty(_characterMap, 'Þ', 'TH'), _defineProperty(_characterMap, 'þ', 'th'), _defineProperty(_characterMap, 'Α', 'A'), _defineProperty(_characterMap, 'α', 'a'), _defineProperty(_characterMap, 'Ά', 'A'), _defineProperty(_characterMap, 'ά', 'a'), _defineProperty(_characterMap, 'Β', 'B'), _defineProperty(_characterMap, 'β', 'b'), _defineProperty(_characterMap, 'Γ', 'G'), _defineProperty(_characterMap, 'γ', 'g'), _defineProperty(_characterMap, 'Δ', 'D'), _defineProperty(_characterMap, 'δ', 'd'), _defineProperty(_characterMap, 'Ε', 'E'), _defineProperty(_characterMap, 'ε', 'e'), _defineProperty(_characterMap, 'Έ', 'E'), _defineProperty(_characterMap, 'έ', 'e'), _defineProperty(_characterMap, 'Ζ', 'Z'), _defineProperty(_characterMap, 'ζ', 'z'), _defineProperty(_characterMap, 'Η', 'H'), _defineProperty(_characterMap, 'η', 'h'), _defineProperty(_characterMap, 'Ή', 'H'), _defineProperty(_characterMap, 'ή', 'h'), _defineProperty(_characterMap, 'Θ', '8'), _defineProperty(_characterMap, 'θ', '8'), _defineProperty(_characterMap, 'Ι', 'I'), _defineProperty(_characterMap, 'ι', 'i'), _defineProperty(_characterMap, 'Ί', 'I'), _defineProperty(_characterMap, 'ί', 'i'), _defineProperty(_characterMap, 'Ϊ', 'I'), _defineProperty(_characterMap, 'ϊ', 'i'), _defineProperty(_characterMap, 'ΐ', 'i'), _defineProperty(_characterMap, 'Κ', 'K'), _defineProperty(_characterMap, 'κ', 'k'), _defineProperty(_characterMap, 'Λ', 'L'), _defineProperty(_characterMap, 'λ', 'l'), _defineProperty(_characterMap, 'Μ', 'M'), _defineProperty(_characterMap, 'μ', 'm'), _defineProperty(_characterMap, 'Ν', 'N'), _defineProperty(_characterMap, 'ν', 'n'), _defineProperty(_characterMap, 'Ξ', '3'), _defineProperty(_characterMap, 'ξ', '3'), _defineProperty(_characterMap, 'Ο', 'O'), _defineProperty(_characterMap, 'ο', 'o'), _defineProperty(_characterMap, 'Ό', 'O'), _defineProperty(_characterMap, 'ό', 'o'), _defineProperty(_characterMap, 'Π', 'P'), _defineProperty(_characterMap, 'π', 'p'), _defineProperty(_characterMap, 'Ρ', 'R'), _defineProperty(_characterMap, 'ρ', 'r'), _defineProperty(_characterMap, 'Σ', 'S'), _defineProperty(_characterMap, 'ς', 's'), _defineProperty(_characterMap, 'σ', 's'), _defineProperty(_characterMap, 'Τ', 'T'), _defineProperty(_characterMap, 'τ', 't'), _defineProperty(_characterMap, 'Υ', 'Y'), _defineProperty(_characterMap, 'υ', 'y'), _defineProperty(_characterMap, 'Ύ', 'Y'), _defineProperty(_characterMap, 'ύ', 'y'), _defineProperty(_characterMap, 'Ϋ', 'Y'), _defineProperty(_characterMap, 'ϋ', 'y'), _defineProperty(_characterMap, 'ΰ', 'y'), _defineProperty(_characterMap, 'Φ', 'F'), _defineProperty(_characterMap, 'φ', 'f'), _defineProperty(_characterMap, 'Χ', 'X'), _defineProperty(_characterMap, 'χ', 'x'), _defineProperty(_characterMap, 'Ψ', 'PS'), _defineProperty(_characterMap, 'ψ', 'ps'), _defineProperty(_characterMap, 'Ω', 'W'), _defineProperty(_characterMap, 'ω', 'w'), _defineProperty(_characterMap, 'Ώ', 'W'), _defineProperty(_characterMap, 'ώ', 'w'), _defineProperty(_characterMap, 'А', 'A'), _defineProperty(_characterMap, 'а', 'a'), _defineProperty(_characterMap, 'Б', 'B'), _defineProperty(_characterMap, 'б', 'b'), _defineProperty(_characterMap, 'В', 'V'), _defineProperty(_characterMap, 'в', 'v'), _defineProperty(_characterMap, 'Г', 'G'), _defineProperty(_characterMap, 'г', 'g'), _defineProperty(_characterMap, 'Ґ', 'G'), _defineProperty(_characterMap, 'Д', 'D'), _defineProperty(_characterMap, 'д', 'd'), _defineProperty(_characterMap, 'Е', 'E'), _defineProperty(_characterMap, 'е', 'e'), _defineProperty(_characterMap, 'Ё', 'Yo'), _defineProperty(_characterMap, 'ё', 'yo'), _defineProperty(_characterMap, 'Є', 'Ye'), _defineProperty(_characterMap, 'є', 'ye'), _defineProperty(_characterMap, 'Ж', 'Zh'), _defineProperty(_characterMap, 'ж', 'zh'), _defineProperty(_characterMap, 'З', 'Z'), _defineProperty(_characterMap, 'з', 'z'), _defineProperty(_characterMap, 'И', 'I'), _defineProperty(_characterMap, 'и', 'i'), _defineProperty(_characterMap, 'І', 'I'), _defineProperty(_characterMap, 'і', 'i'), _defineProperty(_characterMap, 'Ї', 'Yi'), _defineProperty(_characterMap, 'ї', 'yi'), _defineProperty(_characterMap, 'Й', 'J'), _defineProperty(_characterMap, 'й', 'j'), _defineProperty(_characterMap, 'К', 'K'), _defineProperty(_characterMap, 'к', 'k'), _defineProperty(_characterMap, 'Л', 'L'), _defineProperty(_characterMap, 'л', 'l'), _defineProperty(_characterMap, 'М', 'M'), _defineProperty(_characterMap, 'м', 'm'), _defineProperty(_characterMap, 'Н', 'N'), _defineProperty(_characterMap, 'н', 'n'), _defineProperty(_characterMap, 'О', 'O'), _defineProperty(_characterMap, 'о', 'o'), _defineProperty(_characterMap, 'П', 'P'), _defineProperty(_characterMap, 'п', 'p'), _defineProperty(_characterMap, 'Р', 'R'), _defineProperty(_characterMap, 'р', 'r'), _defineProperty(_characterMap, 'С', 'S'), _defineProperty(_characterMap, 'с', 's'), _defineProperty(_characterMap, 'Т', 'T'), _defineProperty(_characterMap, 'т', 't'), _defineProperty(_characterMap, 'У', 'U'), _defineProperty(_characterMap, 'у', 'u'), _defineProperty(_characterMap, 'Ф', 'F'), _defineProperty(_characterMap, 'ф', 'f'), _defineProperty(_characterMap, 'Х', 'H'), _defineProperty(_characterMap, 'х', 'h'), _defineProperty(_characterMap, 'Ц', 'C'), _defineProperty(_characterMap, 'ц', 'c'), _defineProperty(_characterMap, 'Ч', 'Ch'), _defineProperty(_characterMap, 'ч', 'ch'), _defineProperty(_characterMap, 'Ш', 'Sh'), _defineProperty(_characterMap, 'ш', 'sh'), _defineProperty(_characterMap, 'Щ', 'Sh'), _defineProperty(_characterMap, 'щ', 'sh'), _defineProperty(_characterMap, 'Ы', 'Y'), _defineProperty(_characterMap, 'ы', 'y'), _defineProperty(_characterMap, 'Э', 'E'), _defineProperty(_characterMap, 'э', 'e'), _defineProperty(_characterMap, 'Ю', 'Yu'), _defineProperty(_characterMap, 'ю', 'yu'), _defineProperty(_characterMap, 'Я', 'Ya'), _defineProperty(_characterMap, 'я', 'ya'), _characterMap);

		// remove accents
		var from = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', '·', '/', '_', ',', ':', ';'],
		    to = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '-', '-', '-', '-', '-', '-'];

		for (var i = 0; i < from.length; i++) {
			str = str.replace(new RegExp(from[i], 'g'), to[i]);
		};

		return str.toLowerCase().replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-'); // collapse dashes
	};

	output.bind('focus', function () {
		slugOldValue = output.val();
		slugHasValue = output.val();
	});

	output.bind('blur', function () {
		if (output.val() == '') {
			output.val(slugify(input.val()));
		} else {
			output.val(slugify(output.val()));
		}
	});

	input.bind('keyup', function () {
		if (slugHasValue == '') {
			output.val(slugify(input.val()));
		}
	});
});

/**
 * Zepto sortable plugin using html5 drag and drop api.
 */
;(function ($) {
	$.fn.sortable = function (options) {

		var defaults = {
			element: 'li',
			dropped: function dropped() {}
		};

		var settings = $.extend({}, defaults, options);
		var sortables = $(this).find(settings.element);
		var dragsrc;

		var dragstart = function dragstart(event) {
			$(this).addClass('moving');

			dragsrc = this;

			event.dataTransfer.effectAllowed = 'move';
			event.dataTransfer.setData('text/html', this.innerHTML);
		};

		var dragenter = function dragenter() {
			$(this).addClass('over');
		};

		var dragleave = function dragleave() {
			$(this).removeClass('over');
		};

		var dragover = function dragover(event) {
			event.preventDefault();
			event.stopPropagation();

			event.dataTransfer.dropEffect = 'move';
		};

		var drop = function drop(event) {
			event.preventDefault();
			event.stopPropagation();

			if (dragsrc != this) {
				dragsrc.innerHTML = this.innerHTML;

				this.innerHTML = event.dataTransfer.getData('text/html');
			}

			settings.dropped();
		};

		var dragend = function dragend() {
			$(this).removeClass('moving');
			sortables.removeClass('over');
		};

		sortables.on('dragstart', dragstart);
		sortables.on('dragenter', dragenter);
		sortables.on('dragover', dragover);
		sortables.on('dragleave', dragleave);
		sortables.on('drop', drop);
		sortables.on('dragend', dragend);
	};
})(Zepto);
/**
 * Textarea auto resize
 */
$(function () {
	var $text = $('textarea').first();

	function resize(e) {
		var bodyScrollPos = window.pageYOffset;
		// $text.height('auto');
		$text.height($text.prop('scrollHeight') + 'px');
		window.scrollTo(0, bodyScrollPos);
	}

	/* 0-timeout to get the already changed text */
	function delayedResize(e) {
		window.setTimeout(function () {
			resize(e);
		}, 0);
	}

	$text.on('change', resize);
	$text.on('cut paste drop keydown', delayedResize);

	$text.focus();
	$text.select();
	resize();
});

/**
 * Handle custom field file uploads, need to be ajax, in
 * background and populate form field with path to file, so
 * we can store it in the database. Also handle populating 
 * placeholder in field.
 */
$(function () {

	if (window.XMLHttpRequest) {
		var xhr = new XMLHttpRequest();
	} else {
		if (window.ActiveXObject) {
			try {
				var xhr = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (e) {}
		}
	}

	var basename = function basename(path) {
		return path.replace(/\\/g, '/').replace(/.*\//, '');
	};

	var upload_fields = $('input[type=file]');

	// Grab input fields which handle images/files
	// ajax upload, grab source, inject into form.
	upload_fields.bind('change', function () {

		// Grab the current field
		var field = this;

		// Handle placeholder
		var input = $(field),
		    placeholder = input.parent().parent().find('.current-file');
		placeholder.html(basename(input.val()));

		// Create form data object
		var formData = new FormData();
		var files = field.files;

		// Go over all files for this single upload
		// field. (Usually 1)
		for (var i = 0; i < files.length; i++) {
			var file = files[i];

			if (['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'application/pdf'].indexOf(file.type) !== -1) {
				var path = window.location.pathname,
				    uri,
				    parts = path.split('/');

				if (parts[parts.length - 1] == 'add') {
					var uri = path.split('/').slice(0, -2).join('/') + '/upload';
				} else {
					var uri = path.split('/').slice(0, -3).join('/') + '/upload';
				}

				upload(uri, file, field);
			}
		}
	});

	var upload = function upload(uri, file, field) {
		xhr.open("post", uri);

		var formData = new FormData();
		formData.append('file', file);

		xhr.onreadystatechange = function () {
			if (this.readyState == 4) {
				console.log('Uploaded');
				var data = JSON.parse(this.responseText);
				console.log(data);
				$(field).parent().append('<input type="hidden" name="' + $(field).attr('name') + '" value="' + data.uri + '">');
			}
		};

		if (xhr.upload) {
			xhr.upload.onprogress = function (e) {
				// Progress
				// upload_progress(e.position || e.loaded, e.totalSize || e.total);
				console.log(e.position + ':' + e.total);
			};
		} else {
			xhr.addEventListener('progress', function (e) {
				// Progress
				// upload_progress(e.position || e.loaded, e.totalSize || e.total);
				console.log(e.position + ':' + e.total);
			}, false);
		}

		// Send the file (doh)
		xhr.send(formData);
	};
});
/* Zepto v1.0rc1 - polyfill zepto event detect fx ajax form touch - zeptojs.com/license */
(function (a) {
	String.prototype.trim === a && (String.prototype.trim = function () {
		return this.replace(/^\s+/, "").replace(/\s+$/, "");
	}), Array.prototype.reduce === a && (Array.prototype.reduce = function (b) {
		if (this === void 0 || this === null) throw new TypeError();var c = Object(this),
		    d = c.length >>> 0,
		    e = 0,
		    f;if (typeof b != "function") throw new TypeError();if (d == 0 && arguments.length == 1) throw new TypeError();if (arguments.length >= 2) f = arguments[1];else do {
			if (e in c) {
				f = c[e++];break;
			}if (++e >= d) throw new TypeError();
		} while (!0);while (e < d) {
			e in c && (f = b.call(a, f, c[e], e, c)), e++;
		}return f;
	});
})();var Zepto = function () {
	function A(a) {
		return v.call(a) == "[object Function]";
	}function B(a) {
		return a instanceof Object;
	}function C(b) {
		var c, d;if (v.call(b) !== "[object Object]") return !1;d = A(b.constructor) && b.constructor.prototype;if (!d || !hasOwnProperty.call(d, "isPrototypeOf")) return !1;for (c in b) {}return c === a || hasOwnProperty.call(b, c);
	}function D(a) {
		return a instanceof Array;
	}function E(a) {
		return typeof a.length == "number";
	}function F(b) {
		return b.filter(function (b) {
			return b !== a && b !== null;
		});
	}function G(a) {
		return a.length > 0 ? [].concat.apply([], a) : a;
	}function H(a) {
		return a.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/_/g, "-").toLowerCase();
	}function I(a) {
		return a in i ? i[a] : i[a] = new RegExp("(^|\\s)" + a + "(\\s|$)");
	}function J(a, b) {
		return typeof b == "number" && !k[H(a)] ? b + "px" : b;
	}function K(a) {
		var b, c;return h[a] || (b = g.createElement(a), g.body.appendChild(b), c = j(b, "").getPropertyValue("display"), b.parentNode.removeChild(b), c == "none" && (c = "block"), h[a] = c), h[a];
	}function L(b, d) {
		return d === a ? c(b) : c(b).filter(d);
	}function M(a, b, c, d) {
		return A(b) ? b.call(a, c, d) : b;
	}function N(a, b, d) {
		var e = a % 2 ? b : b.parentNode;e ? e.insertBefore(d, a ? a == 1 ? e.firstChild : a == 2 ? b : null : b.nextSibling) : c(d).remove();
	}function O(a, b) {
		b(a);for (var c in a.childNodes) {
			O(a.childNodes[c], b);
		}
	}var a,
	    b,
	    c,
	    d,
	    e = [],
	    f = e.slice,
	    g = window.document,
	    h = {},
	    i = {},
	    j = g.defaultView.getComputedStyle,
	    k = { "column-count": 1, columns: 1, "font-weight": 1, "line-height": 1, opacity: 1, "z-index": 1, zoom: 1 },
	    l = /^\s*<(\w+|!)[^>]*>/,
	    m = [1, 3, 8, 9, 11],
	    n = ["after", "prepend", "before", "append"],
	    o = g.createElement("table"),
	    p = g.createElement("tr"),
	    q = { tr: g.createElement("tbody"), tbody: o, thead: o, tfoot: o, td: p, th: p, "*": g.createElement("div") },
	    r = /complete|loaded|interactive/,
	    s = /^\.([\w-]+)$/,
	    t = /^#([\w-]+)$/,
	    u = /^[\w-]+$/,
	    v = {}.toString,
	    w = {},
	    x,
	    y,
	    z = g.createElement("div");return w.matches = function (a, b) {
		if (!a || a.nodeType !== 1) return !1;var c = a.webkitMatchesSelector || a.mozMatchesSelector || a.oMatchesSelector || a.matchesSelector;if (c) return c.call(a, b);var d,
		    e = a.parentNode,
		    f = !e;return f && (e = z).appendChild(a), d = ~w.qsa(e, b).indexOf(a), f && z.removeChild(a), d;
	}, x = function x(a) {
		return a.replace(/-+(.)?/g, function (a, b) {
			return b ? b.toUpperCase() : "";
		});
	}, y = function y(a) {
		return a.filter(function (b, c) {
			return a.indexOf(b) == c;
		});
	}, w.fragment = function (b, d) {
		d === a && (d = l.test(b) && RegExp.$1), d in q || (d = "*");var e = q[d];return e.innerHTML = "" + b, c.each(f.call(e.childNodes), function () {
			e.removeChild(this);
		});
	}, w.Z = function (a, b) {
		return a = a || [], a.__proto__ = arguments.callee.prototype, a.selector = b || "", a;
	}, w.isZ = function (a) {
		return a instanceof w.Z;
	}, w.init = function (b, d) {
		if (!b) return w.Z();if (A(b)) return c(g).ready(b);if (w.isZ(b)) return b;var e;if (D(b)) e = F(b);else if (C(b)) e = [c.extend({}, b)], b = null;else if (m.indexOf(b.nodeType) >= 0 || b === window) e = [b], b = null;else if (l.test(b)) e = w.fragment(b.trim(), RegExp.$1), b = null;else {
			if (d !== a) return c(d).find(b);e = w.qsa(g, b);
		}return w.Z(e, b);
	}, c = function c(a, b) {
		return w.init(a, b);
	}, c.extend = function (c) {
		return f.call(arguments, 1).forEach(function (d) {
			for (b in d) {
				d[b] !== a && (c[b] = d[b]);
			}
		}), c;
	}, w.qsa = function (a, b) {
		var c;return a === g && t.test(b) ? (c = a.getElementById(RegExp.$1)) ? [c] : e : a.nodeType !== 1 && a.nodeType !== 9 ? e : f.call(s.test(b) ? a.getElementsByClassName(RegExp.$1) : u.test(b) ? a.getElementsByTagName(b) : a.querySelectorAll(b));
	}, c.isFunction = A, c.isObject = B, c.isArray = D, c.isPlainObject = C, c.inArray = function (a, b, c) {
		return e.indexOf.call(b, a, c);
	}, c.trim = function (a) {
		return a.trim();
	}, c.uuid = 0, c.map = function (a, b) {
		var c,
		    d = [],
		    e,
		    f;if (E(a)) for (e = 0; e < a.length; e++) {
			c = b(a[e], e), c != null && d.push(c);
		} else for (f in a) {
			c = b(a[f], f), c != null && d.push(c);
		}return G(d);
	}, c.each = function (a, b) {
		var c, d;if (E(a)) {
			for (c = 0; c < a.length; c++) {
				if (b.call(a[c], c, a[c]) === !1) return a;
			}
		} else for (d in a) {
			if (b.call(a[d], d, a[d]) === !1) return a;
		}return a;
	}, c.fn = { forEach: e.forEach, reduce: e.reduce, push: e.push, indexOf: e.indexOf, concat: e.concat, map: function map(a) {
			return c.map(this, function (b, c) {
				return a.call(b, c, b);
			});
		}, slice: function slice() {
			return c(f.apply(this, arguments));
		}, ready: function ready(a) {
			return r.test(g.readyState) ? a(c) : g.addEventListener("DOMContentLoaded", function () {
				a(c);
			}, !1), this;
		}, get: function get(b) {
			return b === a ? f.call(this) : this[b];
		}, toArray: function toArray() {
			return this.get();
		}, size: function size() {
			return this.length;
		}, remove: function remove() {
			return this.each(function () {
				this.parentNode != null && this.parentNode.removeChild(this);
			});
		}, each: function each(a) {
			return this.forEach(function (b, c) {
				a.call(b, c, b);
			}), this;
		}, filter: function filter(a) {
			return c([].filter.call(this, function (b) {
				return w.matches(b, a);
			}));
		}, add: function add(a, b) {
			return c(y(this.concat(c(a, b))));
		}, is: function is(a) {
			return this.length > 0 && w.matches(this[0], a);
		}, not: function not(b) {
			var d = [];if (A(b) && b.call !== a) this.each(function (a) {
				b.call(this, a) || d.push(this);
			});else {
				var e = typeof b == "string" ? this.filter(b) : E(b) && A(b.item) ? f.call(b) : c(b);this.forEach(function (a) {
					e.indexOf(a) < 0 && d.push(a);
				});
			}return c(d);
		}, eq: function eq(a) {
			return a === -1 ? this.slice(a) : this.slice(a, +a + 1);
		}, first: function first() {
			var a = this[0];return a && !B(a) ? a : c(a);
		}, last: function last() {
			var a = this[this.length - 1];return a && !B(a) ? a : c(a);
		}, find: function find(a) {
			var b;return this.length == 1 ? b = w.qsa(this[0], a) : b = this.map(function () {
				return w.qsa(this, a);
			}), c(b);
		}, closest: function closest(a, b) {
			var d = this[0];while (d && !w.matches(d, a)) {
				d = d !== b && d !== g && d.parentNode;
			}return c(d);
		}, parents: function parents(a) {
			var b = [],
			    d = this;while (d.length > 0) {
				d = c.map(d, function (a) {
					if ((a = a.parentNode) && a !== g && b.indexOf(a) < 0) return b.push(a), a;
				});
			}return L(b, a);
		}, parent: function parent(a) {
			return L(y(this.pluck("parentNode")), a);
		}, children: function children(a) {
			return L(this.map(function () {
				return f.call(this.children);
			}), a);
		}, siblings: function siblings(a) {
			return L(this.map(function (a, b) {
				return f.call(b.parentNode.children).filter(function (a) {
					return a !== b;
				});
			}), a);
		}, empty: function empty() {
			return this.each(function () {
				this.innerHTML = "";
			});
		}, pluck: function pluck(a) {
			return this.map(function () {
				return this[a];
			});
		}, show: function show() {
			return this.each(function () {
				this.style.display == "none" && (this.style.display = null), j(this, "").getPropertyValue("display") == "none" && (this.style.display = K(this.nodeName));
			});
		}, replaceWith: function replaceWith(a) {
			return this.before(a).remove();
		}, wrap: function wrap(a) {
			return this.each(function () {
				c(this).wrapAll(c(a)[0].cloneNode(!1));
			});
		}, wrapAll: function wrapAll(a) {
			return this[0] && (c(this[0]).before(a = c(a)), a.append(this)), this;
		}, unwrap: function unwrap() {
			return this.parent().each(function () {
				c(this).replaceWith(c(this).children());
			}), this;
		}, clone: function clone() {
			return c(this.map(function () {
				return this.cloneNode(!0);
			}));
		}, hide: function hide() {
			return this.css("display", "none");
		}, toggle: function toggle(b) {
			return (b === a ? this.css("display") == "none" : b) ? this.show() : this.hide();
		}, prev: function prev() {
			return c(this.pluck("previousElementSibling"));
		}, next: function next() {
			return c(this.pluck("nextElementSibling"));
		}, html: function html(b) {
			return b === a ? this.length > 0 ? this[0].innerHTML : null : this.each(function (a) {
				var d = this.innerHTML;c(this).empty().append(M(this, b, a, d));
			});
		}, text: function text(b) {
			return b === a ? this.length > 0 ? this[0].textContent : null : this.each(function () {
				this.textContent = b;
			});
		}, attr: function attr(c, d) {
			var e;return typeof c == "string" && d === a ? this.length == 0 || this[0].nodeType !== 1 ? a : c == "value" && this[0].nodeName == "INPUT" ? this.val() : !(e = this[0].getAttribute(c)) && c in this[0] ? this[0][c] : e : this.each(function (a) {
				if (this.nodeType !== 1) return;if (B(c)) for (b in c) {
					this.setAttribute(b, c[b]);
				} else this.setAttribute(c, M(this, d, a, this.getAttribute(c)));
			});
		}, removeAttr: function removeAttr(a) {
			return this.each(function () {
				this.nodeType === 1 && this.removeAttribute(a);
			});
		}, prop: function prop(b, c) {
			return c === a ? this[0] ? this[0][b] : a : this.each(function (a) {
				this[b] = M(this, c, a, this[b]);
			});
		}, data: function data(b, c) {
			var d = this.attr("data-" + H(b), c);return d !== null ? d : a;
		}, val: function val(b) {
			return b === a ? this.length > 0 ? this[0].value : a : this.each(function (a) {
				this.value = M(this, b, a, this.value);
			});
		}, offset: function offset() {
			if (this.length == 0) return null;var a = this[0].getBoundingClientRect();return { left: a.left + window.pageXOffset, top: a.top + window.pageYOffset, width: a.width, height: a.height };
		}, css: function css(c, d) {
			if (d === a && typeof c == "string") return this.length == 0 ? a : this[0].style[x(c)] || j(this[0], "").getPropertyValue(c);var e = "";for (b in c) {
				typeof c[b] == "string" && c[b] == "" ? this.each(function () {
					this.style.removeProperty(H(b));
				}) : e += H(b) + ":" + J(b, c[b]) + ";";
			}return typeof c == "string" && (d == "" ? this.each(function () {
				this.style.removeProperty(H(c));
			}) : e = H(c) + ":" + J(c, d)), this.each(function () {
				this.style.cssText += ";" + e;
			});
		}, index: function index(a) {
			return a ? this.indexOf(c(a)[0]) : this.parent().children().indexOf(this[0]);
		}, hasClass: function hasClass(a) {
			return this.length < 1 ? !1 : I(a).test(this[0].className);
		}, addClass: function addClass(a) {
			return this.each(function (b) {
				d = [];var e = this.className,
				    f = M(this, a, b, e);f.split(/\s+/g).forEach(function (a) {
					c(this).hasClass(a) || d.push(a);
				}, this), d.length && (this.className += (e ? " " : "") + d.join(" "));
			});
		}, removeClass: function removeClass(b) {
			return this.each(function (c) {
				if (b === a) return this.className = "";d = this.className, M(this, b, c, d).split(/\s+/g).forEach(function (a) {
					d = d.replace(I(a), " ");
				}), this.className = d.trim();
			});
		}, toggleClass: function toggleClass(b, d) {
			return this.each(function (e) {
				var f = M(this, b, e, this.className);(d === a ? !c(this).hasClass(f) : d) ? c(this).addClass(f) : c(this).removeClass(f);
			});
		} }, ["width", "height"].forEach(function (b) {
		c.fn[b] = function (d) {
			var e,
			    f = b.replace(/./, function (a) {
				return a[0].toUpperCase();
			});return d === a ? this[0] == window ? window["inner" + f] : this[0] == g ? g.documentElement["offset" + f] : (e = this.offset()) && e[b] : this.each(function (a) {
				var e = c(this);e.css(b, M(this, d, a, e[b]()));
			});
		};
	}), n.forEach(function (a, b) {
		c.fn[a] = function () {
			var a = c.map(arguments, function (a) {
				return B(a) ? a : w.fragment(a);
			});if (a.length < 1) return this;var d = this.length,
			    e = d > 1,
			    f = b < 2;return this.each(function (c, g) {
				for (var h = 0; h < a.length; h++) {
					var i = a[f ? a.length - h - 1 : h];O(i, function (a) {
						a.nodeName != null && a.nodeName.toUpperCase() === "SCRIPT" && (!a.type || a.type === "text/javascript") && window.eval.call(window, a.innerHTML);
					}), e && c < d - 1 && (i = i.cloneNode(!0)), N(b, g, i);
				}
			});
		}, c.fn[b % 2 ? a + "To" : "insert" + (b ? "Before" : "After")] = function (b) {
			return c(b)[a](this), this;
		};
	}), w.Z.prototype = c.fn, w.camelize = x, w.uniq = y, c.zepto = w, c;
}();window.Zepto = Zepto, "$" in window || (window.$ = Zepto), function (a) {
	function f(a) {
		return a._zid || (a._zid = d++);
	}function g(a, b, d, e) {
		b = h(b);if (b.ns) var g = i(b.ns);return (c[f(a)] || []).filter(function (a) {
			return a && (!b.e || a.e == b.e) && (!b.ns || g.test(a.ns)) && (!d || f(a.fn) === f(d)) && (!e || a.sel == e);
		});
	}function h(a) {
		var b = ("" + a).split(".");return { e: b[0], ns: b.slice(1).sort().join(" ") };
	}function i(a) {
		return new RegExp("(?:^| )" + a.replace(" ", " .* ?") + "(?: |$)");
	}function j(b, c, d) {
		a.isObject(b) ? a.each(b, d) : b.split(/\s/).forEach(function (a) {
			d(a, c);
		});
	}function k(b, d, e, g, i, k) {
		k = !!k;var l = f(b),
		    m = c[l] || (c[l] = []);j(d, e, function (c, d) {
			var e = i && i(d, c),
			    f = e || d,
			    j = function j(a) {
				var c = f.apply(b, [a].concat(a.data));return c === !1 && a.preventDefault(), c;
			},
			    l = a.extend(h(c), { fn: d, proxy: j, sel: g, del: e, i: m.length });m.push(l), b.addEventListener(l.e, j, k);
		});
	}function l(a, b, d, e) {
		var h = f(a);j(b || "", d, function (b, d) {
			g(a, b, d, e).forEach(function (b) {
				delete c[h][b.i], a.removeEventListener(b.e, b.proxy, !1);
			});
		});
	}function p(b) {
		var c = a.extend({ originalEvent: b }, b);return a.each(o, function (a, d) {
			c[a] = function () {
				return this[d] = m, b[a].apply(b, arguments);
			}, c[d] = n;
		}), c;
	}function q(a) {
		if (!("defaultPrevented" in a)) {
			a.defaultPrevented = !1;var b = a.preventDefault;a.preventDefault = function () {
				this.defaultPrevented = !0, b.call(this);
			};
		}
	}var b = a.zepto.qsa,
	    c = {},
	    d = 1,
	    e = {};e.click = e.mousedown = e.mouseup = e.mousemove = "MouseEvents", a.event = { add: k, remove: l }, a.proxy = function (b, c) {
		if (a.isFunction(b)) {
			var d = function d() {
				return b.apply(c, arguments);
			};return d._zid = f(b), d;
		}if (typeof c == "string") return a.proxy(b[c], b);throw new TypeError("expected function");
	}, a.fn.bind = function (a, b) {
		return this.each(function () {
			k(this, a, b);
		});
	}, a.fn.unbind = function (a, b) {
		return this.each(function () {
			l(this, a, b);
		});
	}, a.fn.one = function (a, b) {
		return this.each(function (c, d) {
			k(this, a, b, null, function (a, b) {
				return function () {
					var c = a.apply(d, arguments);return l(d, b, a), c;
				};
			});
		});
	};var m = function m() {
		return !0;
	},
	    n = function n() {
		return !1;
	},
	    o = { preventDefault: "isDefaultPrevented", stopImmediatePropagation: "isImmediatePropagationStopped", stopPropagation: "isPropagationStopped" };a.fn.delegate = function (b, c, d) {
		var e = !1;if (c == "blur" || c == "focus") a.iswebkit ? c = c == "blur" ? "focusout" : c == "focus" ? "focusin" : c : e = !0;return this.each(function (f, g) {
			k(g, c, d, b, function (c) {
				return function (d) {
					var e,
					    f = a(d.target).closest(b, g).get(0);if (f) return e = a.extend(p(d), { currentTarget: f, liveFired: g }), c.apply(f, [e].concat([].slice.call(arguments, 1)));
				};
			}, e);
		});
	}, a.fn.undelegate = function (a, b, c) {
		return this.each(function () {
			l(this, b, c, a);
		});
	}, a.fn.live = function (b, c) {
		return a(document.body).delegate(this.selector, b, c), this;
	}, a.fn.die = function (b, c) {
		return a(document.body).undelegate(this.selector, b, c), this;
	}, a.fn.on = function (b, c, d) {
		return c == undefined || a.isFunction(c) ? this.bind(b, c) : this.delegate(c, b, d);
	}, a.fn.off = function (b, c, d) {
		return c == undefined || a.isFunction(c) ? this.unbind(b, c) : this.undelegate(c, b, d);
	}, a.fn.trigger = function (b, c) {
		return typeof b == "string" && (b = a.Event(b)), q(b), b.data = c, this.each(function () {
			"dispatchEvent" in this && this.dispatchEvent(b);
		});
	}, a.fn.triggerHandler = function (b, c) {
		var d, e;return this.each(function (f, h) {
			d = p(typeof b == "string" ? a.Event(b) : b), d.data = c, d.target = h, a.each(g(h, b.type || b), function (a, b) {
				e = b.proxy(d);if (d.isImmediatePropagationStopped()) return !1;
			});
		}), e;
	}, "focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout change select keydown keypress keyup error".split(" ").forEach(function (b) {
		a.fn[b] = function (a) {
			return this.bind(b, a);
		};
	}), ["focus", "blur"].forEach(function (b) {
		a.fn[b] = function (a) {
			if (a) this.bind(b, a);else if (this.length) try {
				this.get(0)[b]();
			} catch (c) {}return this;
		};
	}), a.Event = function (a, b) {
		var c = document.createEvent(e[a] || "Events"),
		    d = !0;if (b) for (var f in b) {
			f == "bubbles" ? d = !!b[f] : c[f] = b[f];
		}return c.initEvent(a, d, !0, null, null, null, null, null, null, null, null, null, null, null, null), c;
	};
}(Zepto), function (a) {
	function b(a) {
		var b = this.os = {},
		    c = this.browser = {},
		    d = a.match(/WebKit\/([\d.]+)/),
		    e = a.match(/(Android)\s+([\d.]+)/),
		    f = a.match(/(iPad).*OS\s([\d_]+)/),
		    g = !f && a.match(/(iPhone\sOS)\s([\d_]+)/),
		    h = a.match(/(webOS|hpwOS)[\s\/]([\d.]+)/),
		    i = h && a.match(/TouchPad/),
		    j = a.match(/Kindle\/([\d.]+)/),
		    k = a.match(/Silk\/([\d._]+)/),
		    l = a.match(/(BlackBerry).*Version\/([\d.]+)/);if (c.webkit = !!d) c.version = d[1];e && (b.android = !0, b.version = e[2]), g && (b.ios = b.iphone = !0, b.version = g[2].replace(/_/g, ".")), f && (b.ios = b.ipad = !0, b.version = f[2].replace(/_/g, ".")), h && (b.webos = !0, b.version = h[2]), i && (b.touchpad = !0), l && (b.blackberry = !0, b.version = l[2]), j && (b.kindle = !0, b.version = j[1]), k && (c.silk = !0, c.version = k[1]), !k && b.android && a.match(/Kindle Fire/) && (c.silk = !0);
	}b.call(a, navigator.userAgent), a.__detect = b;
}(Zepto), function (a, b) {
	function l(a) {
		return a.toLowerCase();
	}function m(a) {
		return d ? d + a : l(a);
	}var c = "",
	    d,
	    e,
	    f,
	    g = { Webkit: "webkit", Moz: "", O: "o", ms: "MS" },
	    h = window.document,
	    i = h.createElement("div"),
	    j = /^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i,
	    k = {};a.each(g, function (a, e) {
		if (i.style[a + "TransitionProperty"] !== b) return c = "-" + l(a) + "-", d = e, !1;
	}), k[c + "transition-property"] = k[c + "transition-duration"] = k[c + "transition-timing-function"] = k[c + "animation-name"] = k[c + "animation-duration"] = "", a.fx = { off: d === b && i.style.transitionProperty === b, cssPrefix: c, transitionEnd: m("TransitionEnd"), animationEnd: m("AnimationEnd") }, a.fn.animate = function (b, c, d, e) {
		return a.isObject(c) && (d = c.easing, e = c.complete, c = c.duration), c && (c /= 1e3), this.anim(b, c, d, e);
	}, a.fn.anim = function (d, e, f, g) {
		var h,
		    i = {},
		    l,
		    m = this,
		    n,
		    o = a.fx.transitionEnd;e === b && (e = .4), a.fx.off && (e = 0);if (typeof d == "string") i[c + "animation-name"] = d, i[c + "animation-duration"] = e + "s", o = a.fx.animationEnd;else {
			for (l in d) {
				j.test(l) ? (h || (h = []), h.push(l + "(" + d[l] + ")")) : i[l] = d[l];
			}h && (i[c + "transform"] = h.join(" ")), !a.fx.off && (typeof d === "undefined" ? "undefined" : _typeof(d)) == "object" && (i[c + "transition-property"] = Object.keys(d).join(", "), i[c + "transition-duration"] = e + "s", i[c + "transition-timing-function"] = f || "linear");
		}return n = function n(b) {
			if (typeof b != "undefined") {
				if (b.target !== b.currentTarget) return;a(b.target).unbind(o, arguments.callee);
			}a(this).css(k), g && g.call(this);
		}, e > 0 && this.bind(o, n), setTimeout(function () {
			m.css(i), e <= 0 && setTimeout(function () {
				m.each(function () {
					n.call(this);
				});
			}, 0);
		}, 0), this;
	}, i = null;
}(Zepto), function ($) {
	function triggerAndReturn(a, b, c) {
		var d = $.Event(b);return $(a).trigger(d, c), !d.defaultPrevented;
	}function triggerGlobal(a, b, c, d) {
		if (a.global) return triggerAndReturn(b || document, c, d);
	}function ajaxStart(a) {
		a.global && $.active++ === 0 && triggerGlobal(a, null, "ajaxStart");
	}function ajaxStop(a) {
		a.global && ! --$.active && triggerGlobal(a, null, "ajaxStop");
	}function ajaxBeforeSend(a, b) {
		var c = b.context;if (b.beforeSend.call(c, a, b) === !1 || triggerGlobal(b, c, "ajaxBeforeSend", [a, b]) === !1) return !1;triggerGlobal(b, c, "ajaxSend", [a, b]);
	}function ajaxSuccess(a, b, c) {
		var d = c.context,
		    e = "success";c.success.call(d, a, e, b), triggerGlobal(c, d, "ajaxSuccess", [b, c, a]), ajaxComplete(e, b, c);
	}function ajaxError(a, b, c, d) {
		var e = d.context;d.error.call(e, c, b, a), triggerGlobal(d, e, "ajaxError", [c, d, a]), ajaxComplete(b, c, d);
	}function ajaxComplete(a, b, c) {
		var d = c.context;c.complete.call(d, b, a), triggerGlobal(c, d, "ajaxComplete", [b, c]), ajaxStop(c);
	}function empty() {}function mimeToDataType(a) {
		return a && (a == htmlType ? "html" : a == jsonType ? "json" : scriptTypeRE.test(a) ? "script" : xmlTypeRE.test(a) && "xml") || "text";
	}function appendQuery(a, b) {
		return (a + "&" + b).replace(/[&?]{1,2}/, "?");
	}function serializeData(a) {
		isObject(a.data) && (a.data = $.param(a.data)), a.data && (!a.type || a.type.toUpperCase() == "GET") && (a.url = appendQuery(a.url, a.data));
	}function serialize(a, b, c, d) {
		var e = $.isArray(b);$.each(b, function (b, f) {
			d && (b = c ? d : d + "[" + (e ? "" : b) + "]"), !d && e ? a.add(f.name, f.value) : (c ? $.isArray(f) : isObject(f)) ? serialize(a, f, c, b) : a.add(b, f);
		});
	}var jsonpID = 0,
	    isObject = $.isObject,
	    document = window.document,
	    key,
	    name,
	    rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
	    scriptTypeRE = /^(?:text|application)\/javascript/i,
	    xmlTypeRE = /^(?:text|application)\/xml/i,
	    jsonType = "application/json",
	    htmlType = "text/html",
	    blankRE = /^\s*$/;$.active = 0, $.ajaxJSONP = function (a) {
		var b = "jsonp" + ++jsonpID,
		    c = document.createElement("script"),
		    d = function d() {
			$(c).remove(), b in window && (window[b] = empty), ajaxComplete("abort", e, a);
		},
		    e = { abort: d },
		    f;return a.error && (c.onerror = function () {
			e.abort(), a.error();
		}), window[b] = function (d) {
			clearTimeout(f), $(c).remove(), delete window[b], ajaxSuccess(d, e, a);
		}, serializeData(a), c.src = a.url.replace(/=\?/, "=" + b), $("head").append(c), a.timeout > 0 && (f = setTimeout(function () {
			e.abort(), ajaxComplete("timeout", e, a);
		}, a.timeout)), e;
	}, $.ajaxSettings = { type: "GET", beforeSend: empty, success: empty, error: empty, complete: empty, context: null, global: !0, xhr: function xhr() {
			return new window.XMLHttpRequest();
		}, accepts: { script: "text/javascript, application/javascript", json: jsonType, xml: "application/xml, text/xml", html: htmlType, text: "text/plain" }, crossDomain: !1, timeout: 0 }, $.ajax = function (options) {
		var settings = $.extend({}, options || {});for (key in $.ajaxSettings) {
			settings[key] === undefined && (settings[key] = $.ajaxSettings[key]);
		}ajaxStart(settings), settings.crossDomain || (settings.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(settings.url) && RegExp.$2 != window.location.host);var dataType = settings.dataType,
		    hasPlaceholder = /=\?/.test(settings.url);if (dataType == "jsonp" || hasPlaceholder) return hasPlaceholder || (settings.url = appendQuery(settings.url, "callback=?")), $.ajaxJSONP(settings);settings.url || (settings.url = window.location.toString()), serializeData(settings);var mime = settings.accepts[dataType],
		    baseHeaders = {},
		    protocol = /^([\w-]+:)\/\//.test(settings.url) ? RegExp.$1 : window.location.protocol,
		    xhr = $.ajaxSettings.xhr(),
		    abortTimeout;settings.crossDomain || (baseHeaders["X-Requested-With"] = "XMLHttpRequest"), mime && (baseHeaders.Accept = mime, mime.indexOf(",") > -1 && (mime = mime.split(",", 2)[0]), xhr.overrideMimeType && xhr.overrideMimeType(mime));if (settings.contentType || settings.data && settings.type.toUpperCase() != "GET") baseHeaders["Content-Type"] = settings.contentType || "application/x-www-form-urlencoded";settings.headers = $.extend(baseHeaders, settings.headers || {}), xhr.onreadystatechange = function () {
			if (xhr.readyState == 4) {
				clearTimeout(abortTimeout);var result,
				    error = !1;if (xhr.status >= 200 && xhr.status < 300 || xhr.status == 304 || xhr.status == 0 && protocol == "file:") {
					dataType = dataType || mimeToDataType(xhr.getResponseHeader("content-type")), result = xhr.responseText;try {
						dataType == "script" ? (1, eval)(result) : dataType == "xml" ? result = xhr.responseXML : dataType == "json" && (result = blankRE.test(result) ? null : JSON.parse(result));
					} catch (e) {
						error = e;
					}error ? ajaxError(error, "parsererror", xhr, settings) : ajaxSuccess(result, xhr, settings);
				} else ajaxError(null, "error", xhr, settings);
			}
		};var async = "async" in settings ? settings.async : !0;xhr.open(settings.type, settings.url, async);for (name in settings.headers) {
			xhr.setRequestHeader(name, settings.headers[name]);
		}return ajaxBeforeSend(xhr, settings) === !1 ? (xhr.abort(), !1) : (settings.timeout > 0 && (abortTimeout = setTimeout(function () {
			xhr.onreadystatechange = empty, xhr.abort(), ajaxError(null, "timeout", xhr, settings);
		}, settings.timeout)), xhr.send(settings.data ? settings.data : null), xhr);
	}, $.get = function (a, b) {
		return $.ajax({ url: a, success: b });
	}, $.post = function (a, b, c, d) {
		return $.isFunction(b) && (d = d || c, c = b, b = null), $.ajax({ type: "POST", url: a, data: b, success: c, dataType: d });
	}, $.getJSON = function (a, b) {
		return $.ajax({ url: a, success: b, dataType: "json" });
	}, $.fn.load = function (a, b) {
		if (!this.length) return this;var c = this,
		    d = a.split(/\s/),
		    e;return d.length > 1 && (a = d[0], e = d[1]), $.get(a, function (a) {
			c.html(e ? $(document.createElement("div")).html(a.replace(rscript, "")).find(e).html() : a), b && b.call(c);
		}), this;
	};var escape = encodeURIComponent;$.param = function (a, b) {
		var c = [];return c.add = function (a, b) {
			this.push(escape(a) + "=" + escape(b));
		}, serialize(c, a, b), c.join("&").replace("%20", "+");
	};
}(Zepto), function (a) {
	a.fn.serializeArray = function () {
		var b = [],
		    c;return a(Array.prototype.slice.call(this.get(0).elements)).each(function () {
			c = a(this);var d = c.attr("type");this.nodeName.toLowerCase() != "fieldset" && !this.disabled && d != "submit" && d != "reset" && d != "button" && (d != "radio" && d != "checkbox" || this.checked) && b.push({ name: c.attr("name"), value: c.val() });
		}), b;
	}, a.fn.serialize = function () {
		var a = [];return this.serializeArray().forEach(function (b) {
			a.push(encodeURIComponent(b.name) + "=" + encodeURIComponent(b.value));
		}), a.join("&");
	}, a.fn.submit = function (b) {
		if (b) this.bind("submit", b);else if (this.length) {
			var c = a.Event("submit");this.eq(0).trigger(c), c.defaultPrevented || this.get(0).submit();
		}return this;
	};
}(Zepto), function (a) {
	function d(a) {
		return "tagName" in a ? a : a.parentNode;
	}function e(a, b, c, d) {
		var e = Math.abs(a - b),
		    f = Math.abs(c - d);return e >= f ? a - b > 0 ? "Left" : "Right" : c - d > 0 ? "Up" : "Down";
	}function h() {
		g = null, b.last && (b.el.trigger("longTap"), b = {});
	}function i() {
		g && clearTimeout(g), g = null;
	}var b = {},
	    c,
	    f = 750,
	    g;a(document).ready(function () {
		var j, k;a(document.body).bind("touchstart", function (e) {
			j = Date.now(), k = j - (b.last || j), b.el = a(d(e.touches[0].target)), c && clearTimeout(c), b.x1 = e.touches[0].pageX, b.y1 = e.touches[0].pageY, k > 0 && k <= 250 && (b.isDoubleTap = !0), b.last = j, g = setTimeout(h, f);
		}).bind("touchmove", function (a) {
			i(), b.x2 = a.touches[0].pageX, b.y2 = a.touches[0].pageY;
		}).bind("touchend", function (a) {
			i(), b.isDoubleTap ? (b.el.trigger("doubleTap"), b = {}) : b.x2 && Math.abs(b.x1 - b.x2) > 30 || b.y2 && Math.abs(b.y1 - b.y2) > 30 ? (b.el.trigger("swipe") && b.el.trigger("swipe" + e(b.x1, b.x2, b.y1, b.y2)), b = {}) : "last" in b && (b.el.trigger("tap"), c = setTimeout(function () {
				c = null, b.el.trigger("singleTap"), b = {};
			}, 250));
		}).bind("touchcancel", function () {
			c && clearTimeout(c), g && clearTimeout(g), g = c = null, b = {};
		});
	}), ["swipe", "swipeLeft", "swipeRight", "swipeUp", "swipeDown", "doubleTap", "tap", "singleTap", "longTap"].forEach(function (b) {
		a.fn[b] = function (a) {
			return this.bind(b, a);
		};
	});
}(Zepto);