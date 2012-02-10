<section class="content">

    <h1><?php echo article_title(); ?></h1>
	
	<article>
	    <?php echo article_html(); ?>
	</article>
</section>

<?php if(comments_open()): ?>

<?php if(has_comments()): ?>
<section class="content">
	<p><strong>Comments</strong></p>
	
	<?php while(comments()): ?>
	<p><strong><?php echo comment_name(); ?> posted <?php echo relative_time(comment_time()); ?></strong></p>
	<p><?php echo comment_text(); ?></p>
	<?php endwhile; ?>
</section>
<?php endif; ?>

<section class="content">
	<form method="post" action="<?php echo current_url(); ?>">
		<legend>Add your comments</legend>
		
		<?php echo comment_form_notifications(); ?>
		
		<p><label>Name<br>
		<?php echo comment_form_input_name(); ?></label></p>
		
		<p><label>Email<br>
		<em>Your email address will never be published</em><br>
		<?php echo comment_form_input_email(); ?></label></p>
		
		<p><label>Comments<br>
		<?php echo comment_form_input_text(); ?></label></p>
		
		<p><?php echo comment_form_button(); ?></p>
	</form>
</section>

<?php endif; ?>

<section class="footnote">
	<p>This article is my <?php echo numeral(article_id() + 1); ?> oldest. It is <?php echo count_words(article_html()); ?> words long. 
	<?php echo article_custom_field('attribution'); ?></p>
</section>

