(function() {
	var textarea = document.querySelector('.content-editor textarea');

	var resize = function() {
		textarea.style.height = textarea.scrollHeight + 'px';

		var event = document.createEvent('HTMLEvents');
		event.initEvent('resize', true, true);
		event.eventName = 'resize';
		textarea.dispatchEvent(event);
	};

	textarea.addEventListener('input', resize, false);

	resize();

	var getCaretPos = function(node) {
		if(node.selectionStart) {
			return node.selectionStart;
		}
		else if ( ! document.selection) {
			return 0;
		}

		var c = "\001",
			sel = document.selection.createRange(),
			dul = sel.duplicate(),
			len = 0;

		dul.moveToElementText(node);
		sel.text = c;
		len = dul.text.indexOf(c);
		sel.moveStart('character', -1);
		sel.text = '';

		return len;
	}

	var setCaretPos = function(node, pos) {
		if(node.createTextRange) {
			var range = node.createTextRange();
			range.move('character', pos);
			range.select();
		}
		else {
			if(node.selectionStart) {
				node.focus();
				node.setSelectionRange(pos, pos);
			}
			else {
				node.focus();
			}
		}
	}

	textarea.addEventListener('blur', function() {
		var pos = getCaretPos(this);
		this.setAttribute('data-caret-pos', pos);
	}, false);
})();
