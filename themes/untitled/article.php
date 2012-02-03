
<section class="content">
	<p><strong><?php echo article_title(); ?></strong></p>
	
	<?php echo article_html(); ?>
	
	<p class="footnote">This article is my <?php echo numeral(article_id() + 1); ?> oldest. 
	It is <?php echo count_words(article_html()); ?> words long. </p>
	
	<?php if(user_authed()): ?>
	<p class="footnote"><a href="/admin/posts/edit/<?php echo article_id(); ?>">&larr; Edit this article</a></p>
	<?php endif; ?>
</section>

