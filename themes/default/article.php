
<section class="content">
	<?php echo article()->html; ?>
</section>

<section class="stats">
	<p>This article is my <?php echo numeral(article()->id + 1); ?> oldest. It is <?php echo count_words(article()->html); ?> words long. </p>
	
	<p>It was typeset in Freight Sans (via Typekit), Nudista and Avenir, uses Microsoft 
	for their amazing WordArt, and is powered by my wonderful, custom-built blogging 
	software, based upon a version of my open-source CMS, Anchor. </p>
</section>

