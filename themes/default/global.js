/*
 *		global.js by @visualidiot
 */

//	When page is loaded
window.onload = function() {

	//	Check for a keypress 
	window.onkeypress = function(e) {
		//	Simple cross-browser key to get the ASCII key number
		if(window.event) { key = e.keyCode; } else { key = e.which; }
		
		if(key == 91) {	//	[
			alert('Go to previous article');
		} else if(key == 93) {	//	]
			alert('Go to next article');
		}
	}

}