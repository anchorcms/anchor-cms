$(function() {

	var viewport = $(window),
		body = $('body'),
		
		header = $('#top div.wrap'),
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
	
	header.append('<img src="' + base + 'img/search.gif" id="search">').children('#search').click(function() {
		
		//  Move the 
		search.animate({marginTop: clicked % 2 === 0 ? marginTop : 0});
		
		if(clicked % 2 === 0) {
			$(this).animate({opacity: 0}, 200, function() {
				$(this).attr('src', base + 'img/search.gif').animate({opacity: 1}, 200);
			});
			
			search.animate({marginTop: marginTop});
		} else {
		
			$(this).animate({opacity: 0}, 200, function() {
				$(this).attr('src', base + 'img/close.gif').animate({opacity: 1}, 200);
			});
		
			search.animate({marginTop: 0});
		}
		
		//  Increment counter
		clicked++;
	});
});