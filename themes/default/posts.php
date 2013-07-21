<?php theme_include('header'); ?>

<section class="content">

	<?php if(has_posts()): posts(); ?>
		<ul class="items">
			<li>
				<article class="wrap">
					<h1>
						<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
					</h1>

					<div class="content">
						<?php echo article_content(); ?>
					</div>

					<footer>
						Posted <time datetime="<?php echo date(DATE_W3C, article_time()); ?>">
						<?php echo relative_time(article_time()); ?></time> by <?php echo article_author(); ?>.
					</footer>
				</article>
			</li>
			<?php while(posts()): ?>
			<li style="<?php echo calculate_background(); ?>">
				<article class="wrap">
					<h2>
						<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
					</h2>
				</article>
			</li>
			<?php endwhile; ?>
		</ul>

		<?php if(has_pagination()): ?>
		<nav class="pagination">
			<div class="wrap">
				<?php echo posts_prev(); ?>
				<?php echo posts_next(); ?>
			</div>
		</nav>
		<?php endif; ?>

	<?php else: ?>
		<p>Looks like you have some writing to do!</p>
	<?php endif; ?>

</section>

<?php theme_include('footer'); ?>