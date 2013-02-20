<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.comments', 'Comments'); ?></h1>


	<nav>
		<a class="btn" href="<?php echo admin_url('comments'); ?>">All</a>
		<a class="btn" href="<?php echo admin_url('comments/pending'); ?>">Pending</a>
		<a class="btn" href="<?php echo admin_url('comments/approved'); ?>">Approved</a>
		<a class="btn" href="<?php echo admin_url('comments/spam'); ?>">Spam</a>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if($comments->count): ?>
	<ul class="list">
		<?php foreach($comments->results as $comment): ?>
		<li>
			<a href="<?php echo admin_url('comments/edit/' . $comment->id); ?>">
				<strong><?php echo strip_tags($comment->text); ?></strong>

				<span><?php echo __('comments.created', 'Created'); ?> <time><?php echo Date::format($comment->date); ?></time>
				<?php echo __('comments.by', 'by'); ?> <?php echo $comment->name; ?></span>

				<span class="highlight"><?php echo $comment->status; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $comments->links(); ?></aside>

	<?php else: ?>
	<p class="empty comments">
		<span class="icon"></span>
		<?php echo __('comments.no_comments', 'No comments, yet.'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>