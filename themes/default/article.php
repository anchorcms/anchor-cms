
<section class="content">
	<p><strong><?php echo article_title(); ?></strong></p>
	
	<?php echo article_html(); ?>
</section>

<section class="footnote">
	<p>This article is my <?php echo numeral(article_id() + 1); ?> oldest. It is <?php echo count_words(article_html()); ?> words long. </p>
</section>

