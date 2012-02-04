$(function() {
    var body = $('body'), form = $('form'), notes = $('.notes');
        
    // Do some fancy fading in, and get rid of that damn error.
    body.hide().fadeIn().children('.nojs').remove();
    
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
		        'url': '/install/diagnose.php',
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
    	// remove previous notifications
    	remove_notes();

		$.ajax({
			type: 'POST',
			url: '/install/installer.php',
			data: form.serialize(),
			success: submit_result
		});

		return false;
    };
    
    var submit_result = function(data) {
		if(data == 'good') {
			form.fadeOut(500, function() {
				window.location = '../';
			});
		} else {
			notes.show().append('<p class="error">' + data + '</p>').fadeIn();
		}
    };
    
    // Bind normal form submit
    form.bind('submit', submit);
    
    // Bind db check
    $('a[href$=#check]').bind('click', check);
});
