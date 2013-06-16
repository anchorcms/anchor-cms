/**
 * Textarea auto resize
 */
$(function() {
	var $text = $('textarea').first();
	
	function resize(e) {
		var bodyScrollPos = $('body').prop('scrollTop');
		$text.height('auto');
		$text.height($text.prop('scrollHeight') + 'px');
		$('body').prop('scrollTop', bodyScrollPos);
	}
	
	/* 0-timeout to get the already changed text */
	function delayedResize (e) {
		window.setTimeout(function(){
			resize(e);
		}, 0);
	}
	
	$text.on('change', resize);
	$text.on('cut paste drop keydown', delayedResize);
	
	$text.focus();
	$text.select();
	resize();
});