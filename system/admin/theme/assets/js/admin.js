$(document).ready(function() {
    /**
     *   Set some useful elements to, uh, use later
     */
    var doc = $(document);
    var html = $('html');
    var body = $('body');
    var titleCache = document.title;
    
    var textarea = $('#post-content');
    
    /**
     *   Focus mode
     */
    var Focus = {
        //  Our element to focus
        target: $('#post-content, .header input'),
        
        enter: function() {
            html.addClass('focus');
            
            //  Set titles and placeholders
            document.title = '☯';
            Focus.target.placeholder = (Focus.target.placeholder || '').split('.')[0] + '.';
        },
        
        exit: function() {
            html.removeClass('focus');
            document.title = titleCache;
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
    
    /**
     *   Drag-n-drop upload
     */
    var Draggy = {
        supported: window.FileReader && window.File,
        allowed: ['text/css', 'text/javascript'],
        
        init: function() {            
            $('.media-upload').remove();
            
            body.append('<div id="upload-file"><span>Upload your file</span></div>');
            
            doc.on('dragover dragleave', function() {
                html.toggleClass('draggy');
            }).on('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();
            });
        }
    };
    
    Draggy.supported && Draggy.init();
    
    /**
     *   Miscellaneous nice touches on edit screens
     */
    var title = $('.header input');
    var slug = $('#slug');
    var changedSlug = false;
    
    slug.keyup(function() {
        changedSlug = true;
    });
    
    title.keyup(function() {
        if(!changedSlug) {
            var s = title.val().toLowerCase();
                s = s.replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-');
                
            //  Strip firstlast slash
            if(s.charAt(s.length - 1) === '-') s = s.substr(0, s.length - 1);
            if(s.charAt(0) === '-') s = s.substr(1);
            
            slug.val(s);
        }
    });
    
    //  Autosave
    var autosave = function() {
        var key = slug.val();
        var val = textarea.val();
        
        if(key && val && window.localStorage) {
            localStorage.setItem('anchor-' + key, val);
            
            if(!body.children('.piggy').length) {
                body.append('<div class="piggy" style="opacity: 0" />');
            }
            
            var piggy = body.children('.piggy').animate({opacity: 1}, 150);
            
            setTimeout(function() {
                piggy.animate({opacity: 0}, 150);
            }, 800);
        }
    };
    
    setInterval(autosave, 5000);
    
    /**
     *   Post previewing
     */
    var buttons = $('.header .buttons');
    var prevue = $('.prevue');
    
    buttons.append('<a href="#" class="secondary btn disabled">Preview</a>').children('.secondary').click(function(e) {
        var html = textarea.val();
        var me = $(this);
        
        if(html) {
            me.removeClass('disabled');
            
            $.getJSON('/admin/markdown?wut=' + encodeURIComponent(html), function(data) {
                console.log(data.html, prevue.hasClass('active'));
                
                if(data.html && !prevue.hasClass('active')) {
                    prevue.children('.wrap').html(data.html);
                }
                
                me.toggleClass('blue');
                prevue.toggleClass('active');
            });
        } else {
            me.addClass('disabled');
        }
        
        return false;
    });
    
    textarea.keyup(function() {
        if(textarea.val() !== '') {
            buttons.children('.disabled').removeClass('disabled');
        } else {
            buttons.children('.secondary').addClass('disabled');
        }
    });
});

(function(d) {
	false && d.addEventListener('DOMContentLoaded', function() {
        
        //  Handle drag-n-drop
        if(window.FileReader && window.File) {
            
            var modalInner = '<span>Upload your file</span>';
            d.body.innerHTML += '<div id="upload-file">' + modalInner + '</div>';
            
            var allowedTypes = ['text/css', 'text/javascript'];
            
            d.addEventListener('dragover', function() {
                setClass('draggy');
            }, false);
            
            d.addEventListener('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                var close = function() {
                    setClass('');
                    
                    modal.className = '';
                    modal.innerHTML = modalInner;
                };
                
                var files = (e.target.files || e.dataTransfer.files)[0];
                
                if(allowedTypes.contains(files.type)) {
                    var reader = new FileReader;
                    
                    reader.onloadend = function(e) {
                        if(e.target.readyState == FileReader.DONE) {
                            var type = files.type === 'text/css' ? 'css' : 'js';
                            
                            d.getElementById(type).value = e.target.result;
                            
                            var modal = d.getElementById('upload-file');
                                modal.className = 'success';
                                modal.innerHTML = '<span>Custom ' + type.toUpperCase() + ' added!</span>';
                            
                            setTimeout(close, 750);
                        }
                    };
                
                    reader.readAsBinaryString(files);
                } else {
                    close();
                }
            }, false);
        }
	}, false);
})(document);