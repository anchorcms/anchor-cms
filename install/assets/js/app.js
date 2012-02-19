$(function() {
    var body = $('body'), form = $('form'), notes = $('.notes');
        
    // remove no js error
    body.find('.nojs').remove();
        
    // Do some fancy fading in, and get rid of that damn error.
    body.hide().fadeIn()
    
	// remove previous notifications
	var remove_notes = function() {
    	notes.find('p').remove();
		notes.hide();
	};
    
    // Check when the MySQL form has been submitted, and AJAX a request off.
    var check = function() {  
    	// remove previous notifications
    	remove_notes();
    	
    	// dim fieldset
    	$('#diagnose').animate({'opacity': 0.5}, 250, function() {
		    $.ajax({
		    	'type': 'POST',
		        'url': 'diagnose.php',
		        'data': form.serialize(),
		        'success': check_result
		    });
    	});
            
        return false;
    };
    
	var check_result = function(data) {
		$('#diagnose').animate({'opacity': 1});

		if(data == 'good') {
			notes.show().append('<p class="success">&#10003; Database test successful.</p>').fadeIn();
		} else {
			notes.show().append('<p class="error">' + data + '</p>').fadeIn();
		}
	};
    
    var submit = function() {
		$.ajax({
			'type': 'POST',
			'url': 'installer.php',
			'data': form.serialize(),
			'dataType': 'json',
			'success': submit_result
		});

		return false;
    };
    
    var submit_result = function(data) {
    	// remove previous notifications
    	remove_notes();

		if(data.installed) {
			var content = $('.content');
			
			content.animate({'opacity': 0}, function() {
				var btn_text = 'Continue to your site.';

				var html = '<h2>Thanks for installing!</h2>';
				html += '<p>We created an account for you.<br>The username is <b>admin</b>, and the password is <strong>' + data.password + '</strong>.</p>';

				if(data.warnings.length) {
					btn_text = 'OK, I understand, Continue to your site.';

					html += '<ul style="padding-bottom: 1em;">';

					for(var i = 0; i < data.warnings.length; i++) {
						var warn = data.warnings[i];
						html += '<li>' + warn + '</li>';
					}

					html += '</ul>';
				}

				html += '<p><a href="../" class="button" style="float: none; display: inline-block;">' + btn_text + '</a></p>';

				content.html(html).animate({'opacity': 1});
			});
		} else {
			notes.show().append('<p class="error">' + data.errors.join(', ') + '</p>').fadeIn();
		}
    };
    
    // Bind normal form submit
    form.bind('submit', submit);
    
    // Bind db check
    $('a[href$=#check]').bind('click', check);
});
