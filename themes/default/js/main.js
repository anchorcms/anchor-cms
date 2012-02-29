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
    
        var body = d.body,
            search = $('search'),
            header = $('top').childNodes[1],
            props = ['webkitTransition', 'mozTransition', 'msTransition', 'oTransition', 'transition'];
        
        body.className += 'js';
        
        header.innerHTML += '<img src="' + base + 'img/search.gif" id="label">';
        
        setTimeout(function() {
            for(var i = 0; i < props.length; i++) {
            	search.style[props[i]] = 'margin .25s linear';
            }
        }, 1);
        
        var label = $('label'),
            goodToGo = true,
            count = 0;
        
        if(goodToGo) {
            label.onclick = function() {
                var opened = count % 2 == 1;
                goodToGo = false;
                
                label.className = 'invisible';
                search.className = !opened ? 'opened' : '';
                
                setTimeout(function() {
                
                    label.src = base + 'img/' + (opened ? 'search' : 'close') + '.gif';
                    label.className = '';
                
                    goodToGo = true;
                }, 250);
                
                count++;
            };
        }
    });
    
})(document, window);