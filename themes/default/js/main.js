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
	var slidey = document.getElementsByClassName('slidey')[0];
	
	//  And move it up
	slidey.style.marginTop = '-' + slidey.clientHeight + 'px';
	
	//  Add a class for da CSS
	setTimeout(function() {
		document.body.className = 'js-enabled';
	}, 10);
});