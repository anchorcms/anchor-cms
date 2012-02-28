(function(d,w) {
    
    var load = function(callback) {
            if(w.addEventListener) {
                w.addEventListener('DOMContentLoaded', callback);
            } else if(w.attachEvent) {
                w.attachEvent('onload', callback);
            }
        };
        
    load(function() {
    
        var body = document.body,
            search = document.getElementById('search'),
            header = document.getElementById('top').childNodes[1],
            props = {
                webkitTransition: 'margin .4s',
                mozTransition: 'margin .4s',
                msTransition: 'margin .4s',
                oTransition: 'margin .4s',
                transition: 'margin .4s',
            };
        
        body.className += 'js';
        
        header.innerHTML += '<img src="' + base + 'img/search.gif" id="label">';
        
        setTimeout(function() {
            for(var i in props) {
                search.style[i] = props[i];
            }
        }, 1);
        
        var label = document.getElementById('label'),
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
    
})(document,window);