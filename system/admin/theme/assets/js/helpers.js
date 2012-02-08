function formatSlug(slug, output) {
	var val = slug.get('value');

	// replace spaces
	val = val.replace(/\s+/, '-');

	// replace crazy characters
	val = val.replace(/[^0-9a-z\-]/i, '');
	
	// convert to lower case 
	val = val.toLowerCase();

	output.set('html', (val.length ? val : 'slug'));
	slug.set('value', val);
}

function formatTwitter(tweet, output) {
	var val = tweet.get('value');

	// replace crazy characters
	val = val.replace(/[^0-9a-z\_]/i, '');

	output.set('html', (val.length ? val : 'example'));
	tweet.set('value', val);
}
