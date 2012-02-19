<section class="content" id="article-<?php echo article_id(); ?>">
    <h1><?php echo article_title(); ?></h1>
	
	<article>
	    <?php echo article_html(); ?>
	</article>
</section>

<?php include 'includes/comment_form.php'; ?>

<section class="footnote">
	<p>This article is my <?php echo numeral(article_id()); ?> oldest. It is <?php echo count_words(article_html()); ?> words long. 
	<?php echo article_custom_field('attribution'); ?></p>
</section>

