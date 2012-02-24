
var Lang = {

	'lines': {},

	'load': function(file) {
		Request.post('../../lang/build', {'file': file}, function(text) {
			Lang.populate(JSON.decode(text));
		});
	},

	'populate': function(obj) {
		for(var key in obj) {
			this.lines[key] = obj[key];
		}
	},

	'get': function(key) {
		return this.lines[key] || 'Translation not found: ' + key;
	}

};