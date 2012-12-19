<?php theme_include('header'); ?>

		<section class="content wrap" id="article-<?php echo article_id(); ?>">
			<h1><?php echo article_title(); ?></h1>

			<article>
				<?php echo article_html(); ?>
			</article>
			
			<section class="footnote">
				<p>This article is my <?php echo numeral(article_id()); ?> oldest. It is <?php echo count_words(article_html()); ?> words long, and it’s got <?php echo total_comments() . pluralise(total_comments(), ' comment'); ?> for now. <?php echo article_custom_field('attribution'); ?></p>
			</section>
		</section>

		<?php if(comments_open()): ?>
		<section class="comments">
			<?php if(has_comments()): ?>
			<ul class="commentlist">
				<?php $i = 0; while(comments()): $i++; ?>
				<li class="comment" id="comment-<?php echo comment_id(); ?>">
					<div class="wrap">
						<h2><?php echo comment_name(); ?></h2>
						<time><?php echo relative_time(comment_time()); ?></time>
		
						<div class="content">
							<?php echo comment_text(); ?>
						</div>
						
						<span class="counter"><?php echo $i; ?></span>
					</div>
				</li>
				<?php endwhile; ?>
			</ul>
			<?php endif; ?>

			<form id="comment" class="commentform wrap" method="post" action="<?php echo comment_form_url(); ?>#comment">
				<?php echo comment_form_notifications(); ?>

				<p class="name">
					<label for="name">Your name:</label>
					<?php echo comment_form_input_name(); ?>
				</p>

				<p class="email">
					<label for="email">Your email address:</label>
					<em>Will never be published.</em>
					<?php echo comment_form_input_email(); ?>
				</p>

				<p class="textarea">
					<label for="text">Your comment:</label>
					<em>Allowed HTML: <code>&lt;a&gt;</code>, <code>&lt;b&gt;</code>, <code>&lt;blockquote&gt;</code>, <code>&lt;code&gt;</code>, <code>&lt;em&gt;</code>, <code>&lt;i&gt;</code>, <code>&lt;p&gt;</code> and <code>&lt;pre&gt;</code>.</em>
					<?php echo comment_form_input_text(); ?>
				</p>

				<p class="submit">
					<?php echo comment_form_button(); ?>
				</p>
			</form>

		</section>
		<?php endif; ?>

<?php theme_include('footer'); ?>