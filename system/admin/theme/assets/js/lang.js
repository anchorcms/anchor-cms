
var Lang = {

	'lines': {},

	'load': function(obj) {
		for(var key in obj) {
			this.lines[key] = obj[key];
		}
	},

	'get': function(key) {
		return this.lines[key] || 'Translation not found: ' + key;
	}

};