(function() {
	//  Drop IE support
	if(document.addEventListener) {
	
	    var position = function(arr, key) {
    	    var me = arr, len = me.length;
    	        
    	    while(len--) {
    	        if(me[len] === key) return len;
    	    }
    	    
    	    return -1;
	    };
	
		document.addEventListener('DOMContentLoaded', function(event) {
			//  Make the big textarea play nicely
			var carousel = document.querySelector('.carousel'),
			    textarea = document.getElementById('post-content'),
				textareaFix = function() {
				
				if(textarea) {
					var winHeight = document.documentElement.clientHeight,
						height = winHeight - 207,
						error = document.getElementsByClassName('notification');
					
					if(error[0]) {
						height -= error[0].clientHeight;
					}
					
					//  Set the height
					textarea.style.height = (height - 11) + 'px';
					carousel.style.height = height + 'px';
				}
			};
			
			//  Bind and shit
			textareaFix();
			window.onresize = textareaFix;
					
			//  Handle tabs on the "add post" page
			var tabs = document.querySelectorAll('.content header nav a') || [];
			var length = tabs.length;
			
			while(length--) {
				var me = tabs[length];
				
				me.addEventListener('click', function(e) {
				    var me = this;
				    
				    e.preventDefault();
				    
				    //  Set the class names
				    var siblings = me.parentNode.parentNode.childNodes;
				    for(var e = 0; e < siblings.length; e++) {
				        siblings[e].className = '';
				    }
				    me.parentNode.className = 'active';
				    
				    //  And move the box
				    var pos = position(tabs, me);
				    
				    //  Move the textarea
				    carousel.style.left = '-' + pos * 100 + '%';
				}, false);
			}
		}, false);
		
		//  Stop the dickish alert
		return false;
	}
	
	alert('Your browser sucks. Go get a better one.');
})();