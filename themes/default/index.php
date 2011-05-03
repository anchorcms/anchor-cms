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
<?php
//	Get the posts
	get_posts();
?>
</div>
<?php	
//	Get the footer
	get_footer();
?>
