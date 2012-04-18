(function(d,w) {
    
    var load = function(callback) {
            if(w.addEventListener) {
                w.addEventListener('DOMContentLoaded', callback);
            } else if(w.attachEvent) {
                w.attachEvent('onload', callback);
            }
        },
        $ = function(el) {
        	return d.getElementById(el);
        };
        
    load(function() {
    
        var search = $('search'),
            header = $('top').childNodes[1],
            props = ['webkitTransition', 'MozTransition', 'MsTransition', 'OTransition', 'transition'];
        
        d.body.className += 'js';
        
        header.innerHTML += '<img src="' + base + 'img/search.gif" id="label">';
        
        setTimeout(function() {
            for(var i = 0; i < props.length; i++) {
            	search.style[props[i]] = 'margin .25s linear';
            }
        }, 1);
        
        var label = $('label'),
            count = 0;
        
        label.onclick = function() {
            var opened = count % 2 == 1;
            label.className = 'invisible';
            search.className = !opened ? 'opened' : '';
            
            search.childNodes[1][opened ? 'blur' : 'focus']();
            
            setTimeout(function() {
                label.src = base + 'img/' + (opened ? 'search' : 'close') + '.gif';
                label.className = '';
            }, 250);
            
            count++;
        };
    });
    
})(document, window);