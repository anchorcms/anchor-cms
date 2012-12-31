<?php theme_include('header'); ?>

<section class="content">

	<?php if(has_posts()): posts(); ?>
		<ul class="items">
			<li>
				<article class="wrap">
					<h2>
						<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
					</h2>

					<div class="content">
						<?php echo article_html(); ?>
					</div>

					<footer>
						Posted <time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time> by <?php echo article_author('real_name'); ?>.
					</footer>
				</article>
			</li>
			<?php $i = 0; while(posts()): $i++; ?>
			<li style="background: hsl(215,28%,<?php echo round((($i / posts_per_page()) * 20) + 20); ?>%);">
				<article class="wrap">
					<h2>
						<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
					</h2>

					<footer>
						Posted <time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time> by <?php echo article_author('real_name'); ?>.
					</footer>
				</article>
			</li>
			<?php endwhile; ?>
		</ul>

		<p><?php echo posts_prev(); ?> <?php echo posts_next(); ?></p>

	<?php else: ?>
		<p>Looks like you have some writing to do!</p>
	<?php endif; ?>

</section>

<?php theme_include('footer'); ?>