/**
 * Textarea auto resize
 */
$(function() {
	$text = $('textarea').first();
	
	function resize () {
		$text.css({height: 'auto'});
		$text.css({height: $text.prop('scrollHeight') + 'px'});
	}
	
	/* 0-timeout to get the already changed text */
	function delayedResize () {
		window.setTimeout(resize, 0);
	}
	
	$text.on('change', resize);
	$text.on('cut paste drop keydown', delayedResize);
	
	$text.focus();
	$text.select();
	resize();
});