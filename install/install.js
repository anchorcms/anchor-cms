$(function() {
    var body = $('body'),
        form = $('form'),
        success = false;
        
    //  Do some fancy fading in, and get rid of that damn error.
    body.hide().fadeIn().children('#error').remove();
    
    //  Check when the MySQL form has been submitted, and AJAX a request off.
    var check = function() {

        var me = $(this),
            parent = me.parents('fieldset');
                
        $.ajax({
            url: 'functions/query.php?ajax',
            data: me.parents('form').serialize(),
            success: function(data) {
            
                console.log(data);
            
                if(data == 'good') {
                    success = true;
                    
                    //  We don't need errors, foo'
                    if(parent.children('.error').length) {
                        parent.children('.error').fadeOut();    
                    }
                    
                    //  Show a success message
                    parent.prepend('<p class="success">&#10003;</p>').animate({opacity: .5});
                } else {
                    if(parent.children('.error').length < 1) {
                        parent.prepend('<p style="display: none;" class="error">Incorrect database details.</p>').children('p:first').fadeIn();
                    }
                }
            }
        });
            
        return false;
    };
    
    //  Normal form submit
    form.submit(function() {
    
        var me = $(this);
    
        if(success === false) {
            $(this).prepend('<p style="display: none;" class="error">Those database details don\'t look right&hellip;</p>').children('p:first').fadeIn();
        } else {
            $.ajax({
                url: 'run.php',
                data: me.serialize(),
                success: function(data) {
                
                    console.log(data);
                
                    if(data == 'good') {
                        me.fadeOut(500, function() {
                            window.location = '../';
                        });
                    } else {
                        alert('An unexpected error has occured. Please contact iam@visualidiot.com with details of how you managed to get this error prompt. Thanks, and sorry!');
                    }
                }
            });
        }
    
        return false;
    });
    
    //  Bind our link. Lovely!
    form.find('fieldset:first > a').bind('click', check);
    
    //  I know I should be able to do this better, somehow. Feels hacky.
    form.find('fieldset:first input').keyup(function(e) {
        var key = e.keyCode || e.which;
        
        if(key == 13) {
            check.call(this);
        }
    });
});