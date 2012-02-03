
<section class="content">
	<p><strong><?php echo article_title(); ?></strong></p>
	
	<?php echo article_html(); ?>
	
	<p class="footnote">This article is my <?php echo numeral(article_id() + 1); ?> oldest. It is <?php echo count_words(article_html()); ?> words long. </p>
</section>

