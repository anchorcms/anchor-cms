function addEvent(obj, type, fn) {
	if(obj.attachEvent) {
		obj['e' + type + fn] = fn;
		obj[type + fn] = function(){
			obj['e' + type + fn](window.event);
		}
		obj.attachEvent('on' + type, obj[type + fn]);
	} else {
		obj.addEventListener(type, fn, false);
	}
}

function removeEvent(obj, type, fn) {
	if(obj.detachEvent) {
		obj.detachEvent('on' + type, obj[type + fn]);
		obj[type + fn] = null;
	} else {
		obj.removeEventListener(type, fn, false);
	}
}

function formatSlug(slug, output) {
	var val = slug.value;

	// replace spaces
	val = val.replace(/\s+/, '-');

	// replace crazy characters
	val = val.replace(/[^0-9a-z\-]/i, '');
	
	// convert to lower case 
	val = val.toLowerCase();

	output.innerHTML = val.length ? val : 'slug';
	slug.value = val;
}

function formatTwitter(tweet, output) {
	var val = tweet.value;

	// replace crazy characters
	val = val.replace(/[^0-9a-z\_]/i, '');

	output.innerHTML = val.length ? val : 'example';
	tweet.value = val;
}
