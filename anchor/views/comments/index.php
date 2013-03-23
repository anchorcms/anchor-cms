<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.comments'); ?></h1>

	<nav>
		<a class="btn" href="<?php echo Uri::to('admin/comments'); ?>"><?php echo __('comments.all_comments'); ?></a>
		<a class="btn" href="<?php echo Uri::to('admin/comments/pending'); ?>"><?php echo __('global.pending'); ?></a>
		<a class="btn" href="<?php echo Uri::to('admin/comments/approved'); ?>"><?php echo __('global.approved'); ?></a>
		<a class="btn" href="<?php echo Uri::to('admin/comments/spam'); ?>"><?php echo __('global.spam'); ?></a>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if($comments->count): ?>
	<ul class="list">
		<?php foreach($comments->results as $comment): ?>
		<li>
			<a href="<?php echo Uri::to('admin/comments/edit/' . $comment->id); ?>">
				<strong><?php echo strip_tags($comment->text); ?></strong>
				<span><time><?php echo Date::format($comment->date); ?></time></span>
				<span class="highlight"><?php echo $comment->status; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $comments->links(); ?></aside>

	<?php else: ?>
	<p class="empty comments">
		<span class="icon"></span> <?php echo __('comments.nocomments_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>