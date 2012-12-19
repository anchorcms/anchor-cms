//  window.attachEvent polyfill
window.attachEvent = window.attachEvent || function(x, callback) {
	document.addEventListener('DOMContentLoaded', callback, false)
}

document.ready = function(callback) {
	//  If the page is already loaded
	if(document.readyState == 'complete') {
		return callback();
	}
	
	//  And get ready for document load
	return window.attachEvent('onload', callback);
};

//  When the DOM is ready
document.ready(function() {	
	//  Get the slidey height
	var slidey = document.getElementsByClassName('slidey')[0],
		height = '-' + (slidey.clientHeight + 1) + 'px';
	
	//  And move it up
	slidey.style.marginTop = height;
	
	//  Add a class for da CSS
	setTimeout(function() {
		document.body.className = 'js-enabled';
	}, 10);
	
	//  Store the links
	var links = document.getElementsByClassName('linky');
	
	for(var i = 0; i < links.length; i++) {
		var me = links[i];
		me.addEventListener('click', function(e) {
			e.preventDefault();
			
			var me = this;
			var opened = slidey.style.marginTop == '0px';
			
			if(me.href.indexOf('search') > 0 && !opened) {
				document.getElementById('term').focus();
			}
			
			me.className = opened ? 'linky' : 'active linky';
			slidey.style.marginTop = !opened ? '0px' : height;
		});
	}	
});