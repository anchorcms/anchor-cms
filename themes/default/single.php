<?php
//	Get the page's header (shared across both the homepage and the subpages)
	get_header();
?>
	
	<div id="frame">
		<ul>
			<li><strong>Post ID:</strong> <?php get_post_id(); ?></li>
			<li><strong>Post Title:</strong> <?php get_post_title(); ?></li>
		</ul>
	</div>
	
	<article id="article">
	<?php
	//	Then get the HTML, given to us in the admin panel
		get_post_content();
	?>
	</article>
	
<?php
//	And get the footer
	get_footer();
?>