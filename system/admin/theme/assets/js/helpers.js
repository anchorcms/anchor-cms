
/**
	String filter
*/
function filter(type, str) {

	if(type == 'slug') {
		// replace spaces
		str = str.replace(/\s+/, '-');

		// replace crazy characters
		str = str.replace(/[^0-9a-z\-]/i, '');
		
		// convert to lower case 
		str = str.toLowerCase();
	}

	if(type == 'twitter') {
		// replace crazy characters
		str = str.replace(/[^0-9a-z\_]/i, '');

		// convert to lower case 
		str = str.toLowerCase();
	}

	return str;
}