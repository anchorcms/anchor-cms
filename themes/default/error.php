<?php
//
//		Let's try making theming crazy-simple.
//

//	Include the header (as always)
	get_header();
?>

<div id="wrap">
	<h1 id="logo">
		<a href="<?php echo $urlpath; ?>" title="<?php sitename(); ?>"><?php sitename(); ?></a>
	</h1>
	<ul id="list">
		<h2>Error 404</h2>
		<p>Sadly, this page could not be found. Rest assured, we're looking for it.</p>
	</ul>
<?php	
//	Get the footer
	get_footer();
?>
