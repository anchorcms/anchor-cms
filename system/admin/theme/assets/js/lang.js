
var Lang = {

	'lines': {},

	'load': function(file) {
		var url = Base_url + 'lang/build/' + file, callback = arguments[1] || function() {};

		Request.get(url, function(text) {
			Lang.populate(JSON.decode(text));
			callback();
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