
/*
	Confirm any deletions
*/
$('.delete').click(function() {
	return confirm('Are you sure you want to delete? This can’t be undone!');
});

/*
	Dropdown fix
*/
$('select').after('<span class="arrow" />');

/*
	Autofocusing first input
*/
if( ! $('[autofocus]').length) {
	$('input:first-child').attr('autofocus', 'autofocus').focus();
}