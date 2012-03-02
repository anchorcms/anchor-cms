
/**
	String filter
*/
function filter(type, str) {

	var regexes = {
		//  Strip spaces
		spaces: /\s+/,
		
		//  Strip non A-Z, 0-9 and dash
		slug: /[^0-9a-z\-]/i,
		
		//  Same, but underscores
		twitter: /[^0-9a-z\_]/i
	};
	
	str = str.toLowerCase().replace(spaces, '');
	
	if(regexes[type]) {
		return str.replace(regexes[type], '');
	}

	return str;
}