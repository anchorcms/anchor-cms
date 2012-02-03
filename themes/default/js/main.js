$(function() {

	var viewport = $(window),
		body = $('body'),
		
		header = $('#top'),
		search = $('#search');
		
	//  Give a CSS hook
	body.addClass('js');
		
	/**
		Search box
	*/
		
	//  Hide the search
	search.css('margin-top', -search.outerHeight());
	
	//  Append some way of making it come back
	var clicked = 1,
		marginTop = search.css('margin-top');
	
	header.append('<img src="' + base + '/img/search.png">').children('img').click(function() {
		
		//  Move the 
		search.animate({marginTop: clicked % 2 === 0 ? 0 : marginTop});
		
		//  Increment counter
		clicked++;
	});
});