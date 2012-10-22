$(document).ready(function() {
    /**
     *   Set some useful elements to, uh, use later
     */
    var doc = $(document);
    var html = $('html');
    var body = $('body');
    var win = $(window);
    
    var titleCache = document.title;
    
    var textarea = $('#post-content');
    
    /**
     *   Dropdown menu fix
     */
    $('select').after('<span class="arrow" />');
    
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
    
    $('[name=delete]').click(function() {
        return confirm('Are you sure you want to delete? This can’t be undone!');
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
    
    //  Disabling the preview button
    textarea.keyup(function() {
        if(textarea.val() !== '') {
            buttons.children('.disabled').removeClass('disabled');
        } else {
            buttons.children('.secondary').addClass('disabled');
        }
    });
    
    
    //  Fix textarea heights
    var buildHeights = function(a) {
        var r = 0;
    
        for(var i = 0; i < a.length; i++) {
            r += $(a[i]).height() || 0;
        }
        
        return r;
    }
    
    var textareaHeight = function() {
        if(win.height() > doc.height() || true) {
            var others = buildHeights(['#top', '.header', '#post-data']);
            textarea.css('height', win.height() - others);
        } else {
            textarea.css('height', 'auto');
        }
    };
    
    textareaHeight();
    win.resize(textareaHeight);
    
    //  Autofocusing first input
    if(!$('[autofocus]').length) {
        $('input:first-child').attr('autofocus', 'autofocus').focus();
    }
});