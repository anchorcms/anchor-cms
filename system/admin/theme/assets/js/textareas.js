
// Just bunging in my textarea thing for now
document.ready(function() {
	var format = function(event) {
		var me = this, start = me.selectionStart, code = event.keyCode,
			actions = {
				9: function() { // Tab
					me.value = me.value.slice(0, start) + '\t' + me.value.slice(start, me.value.length);
					me.focus();
					event.end();
					return;
				}
			};
											
		if(actions[code]) {
			return actions[code]();
		}

		return false;
	};

	$$('textarea').each(function(item) {
		item.bind('keydown', format);
	});
});
