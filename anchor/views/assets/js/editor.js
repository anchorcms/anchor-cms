/**
 * AJAX form and keyboard shortcuts
 */
;(function($) {
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
	zone.on('keydown', function(event) {
		if(event.ctrlKey && event.keyCode == 83 && !(event.altKey)) {
			form.trigger('submit');
			return false;
		}
	});

	// AJAX form submit
	form.on('submit', function(e) {
		e.preventDefault();
		var data = {};
		$.each($(this).serializeArray(), function(_, kv) {
		  data[kv.name] = kv.value;
		});
		
		var didAutosave = $(".autosave-action").hasClass("autosave-on");
		data.autosave = didAutosave;
		
		submit.prop('disabled', true).css('cursor', 'wait').html(submitProgress);

		if (submitProgress) {
			document.title = submitProgress;
		}

		// SimpleMDE
		data.markdown = smdEditor.value();

		$.ajax({
			url: form.attr('action'),
			type: "POST",
			data: data,
			success: function(data, textStatus, jqXHR) {

				data = JSON.parse(data);

				if (data.notification) {				
					document.title = data.notification;

					var notification = $('<p class="success">' + data.notification + '</p>');
					notificationWrapper.append(notification);

					setTimeout(function() {
						notification.animate({
							opacity: 0
						}, 600, "ease-out", function() {
							$(this).remove();
						});
					}, 3000);
				} else if (data.errors) {
					for(index in data.errors) {
						var error = data.errors[index];
						var notification = $('<p class="error">' + error + '</p>');
						notificationWrapper.append(notification);

						setTimeout(function() {
							notification.animate({
								opacity: 0
							}, 600, "ease-out", function() {
								$(this).remove();
							});
						}, 3000);
					};
				}

				if (data.redirect && data.redirect != window.location.href) {
					setTimeout(function() {
						window.location.href = data.redirect;
					}, 1000);
				} else {
					setTimeout(function() {
						document.title = title;
					}, 3000);
				}

				submit.prop('disabled', false).html(submitText).removeAttr('style');
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var notification = $('<div class="notifications"><p class="error">Error</p></div>');
				wrapper.prepend(notification);

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
