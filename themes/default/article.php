<?php theme_include('header'); ?>

		<section class="content wrap" id="article-<?php echo article_id(); ?>">
			<h1><?php echo article_title(); ?></h1>

			<article>
				<?php echo article_markdown(); ?>
			</article>

			<section class="footnote">
				<!-- Unfortunately, CSS means everything's got to be inline. -->
				<p>This article is my <?php echo numeral(article_number(article_id()), true); ?> oldest. It is <?php echo count_words(article_markdown()); ?> words long<?php if(comments_open()): ?>, and it’s got <?php echo total_comments() . pluralise(total_comments(), ' comment'); ?> for now.<?php endif; ?> <?php echo article_custom_field('attribution'); ?></p>
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

						<span class="counter">
							<input class="btn" type="button" value="Reply" />
							<?php echo $i; ?>
						</span>
					</div>
					<?php if(has_replies()) : ?>
					<ul>
						<?php $j = 0; while(replies()) : $j++; ?>
						<li class="comment reply" id="comment-<?php echo reply_id(); ?>">
							<div class="wrap">
								<h2><?php echo reply_name(); ?></h2>
								<time><?php echo relative_time(reply_time()); ?></time>

								<div class="content">
									<?php echo reply_text(); ?>
								</div>

								<span class="counter"><?php echo "{$i}.{$j}"; ?></span>
							</div>
						</li>
						<?php endwhile; ?>
					</ul>
					<?php endif; ?>
				</li>
				<?php endwhile; ?>
			</ul>
			<?php endif; ?>

			<form id="comment" class="commentform wrap" method="post" action="<?php echo comment_form_url(); ?>#comment">

				<div id="comment-reply-notification">
					<div>You are replying to @<span id="comment-reply-username">nobody</span>.</div>
					<span id="cancel-reply" title="cancel">&times;</span>
				</div>

				<?php echo comment_form_notifications(); ?>

				<p class="name">
					<label for="name">Your name:</label>
					<?php echo comment_form_input_name('placeholder="Your name"'); ?>
				</p>

				<p class="email">
					<label for="email">Your email address:</label>
					<?php echo comment_form_input_email('placeholder="Your email (won’t be published)"'); ?>
				</p>

				<p class="textarea">
					<label for="text">Your comment:</label>
					<?php echo comment_form_input_text('placeholder="Your comment"'); ?>
				</p>

				<p class="submit">
					<?php echo comment_form_reply_to(); ?>
					<?php echo comment_form_button(); ?>
				</p>
			</form>

		</section>
		<?php endif; ?>

<?php theme_include('footer'); ?>
