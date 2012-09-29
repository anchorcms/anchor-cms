(function(d) {
	d.addEventListener && d.addEventListener('DOMContentLoaded', function() {
	    var body = d.body;
        var papa = body.parentNode;
        var setClass = function(what) {
            console.log('called');
            papa.className = what;
        };
        
        //  Handle drag-n-drop
        if(window.FileReader && window.File) {
            d.body.innerHTML += '<div id="upload-file"><span>Upload your file</span></div>';
            
            var draggy = function(e) {
                setClass(document.hasFocus() ? 'draggy' : 'no-drag');
            };
            
            d.addEventListener('dragover', draggy, false);  
            
            d.addEventListener('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                setClass('');
                
                console.log(e, e.target.files || e.dataTransfer.files);
            }, false);
        }
	    
	    //  Main textarea
	    var textarea = d.getElementById('post-content');
	    var placeholder = textarea.placeholder;
	    
	    var toggle = function(e) {	
            if(e.type === 'focus') {                
                setClass('focus');
                textarea.placeholder = (textarea.placeholder || 'Start typing.').split('.')[0] + '.';
            } else {
	            setClass('');
	            textarea.placeholder = placeholder;
	        }
        };
	        
        textarea.addEventListener('focus', toggle, false);
        textarea.addEventListener('blur', toggle, false);
        
        //  Handle "get-out-of-focus" mode
        d.addEventListener('click', function() {
            if(papa.className = 'focus') {
                setClass('');
            }
        }, false);
	}, false);
})(document);