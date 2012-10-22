<?php echo $header; ?>

			<h1><?php echo __('comments.comments', 'Comments'); ?></h1>

			<?php echo $messages; ?>

			<section class="content">
				<nav>
					<ul>
						<li><a href="<?php echo url('comments'); ?>">All</a></li>
						<li><a href="<?php echo url('comments/pending'); ?>">Pending</a></li>
						<li><a href="<?php echo url('comments/approved'); ?>">Approved</a></li>
						<li><a href="<?php echo url('comments/spam'); ?>">Spam</a></li>
					</ul>
				</nav>

			<?php if($comments->count): ?>
				<ul class="list">
					<?php foreach($comments->results as $comment): ?>
					<li>
						<p><a href="<?php echo url('comments/edit/' . $comment->id); ?>"><?php echo Str::truncate($comment->text, 10); ?></a></p>

						<p><?php echo __('comments.created', 'Created'); ?> <time><?php echo Date::format($comment->date); ?></time>
						<?php echo __('comments.by', 'by'); ?> <?php echo $comment->name; ?></p>

						<nav>
							<ul>
								<?php if($comment->status == 'approved'): ?>
								<li><a href="<?php echo url('comments/status/unapprove/' . $comment->id); ?>">Unapprove</a></li>
								<?php else: ?>
								<li><a href="<?php echo url('comments/status/approve/' . $comment->id); ?>">Approve</a></li>
								<?php endif; ?>


								<?php if($comment->status == 'spam'): ?>
								<li><a href="<?php echo url('comments/status/notspam/' . $comment->id); ?>">Not Spam</a></li>
								<?php else: ?>
								<li><a href="<?php echo url('comments/status/spam/' . $comment->id); ?>">Spam</a></li>
								<?php endif; ?>

								<li><a href="<?php echo url('comments/delete/' . $comment->id); ?>">Delete</a></li>
							</ul>
						</nav>
					</li>
					<?php endforeach; ?>
				</ul>

				<?php echo $comments->links(); ?>
			<?php else: ?>
				<p><?php echo __('comments.no_comments', 'No comments found.'); ?></p>
			<?php endif; ?>
			</section>

<?php echo $footer; ?>