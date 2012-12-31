<?php theme_include('header'); ?>

		<section class="content wrap">
			<h1><?php echo page_title(); ?></h1>

			<?php echo page_content(); ?>

			<!--
				If you want to add custom functionality per-page, you can do so by using the bind() function, which is stored in the functions.php of your theme.
				Then, if you want to echo out your custom function, you just add a receive() function. Here's an example: "<?php echo receive(); ?>".
			 -->
		</section>

<?php theme_include('footer'); ?>