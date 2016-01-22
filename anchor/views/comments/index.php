<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.comments'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<nav class="sidebar statuses">
		<?php foreach($statuses as $data): extract($data); ?>
		<?php echo Html::link('admin/comments/' . $url, '<span class="icon"></span> ' . __($lang), array(
			'class' => $class . (isset($status) && $status == $url ? ' active' : '')
		)); ?>
		<?php endforeach; ?>
	</nav>

	<?php if($comments->count): ?>
	<ul class="main list">
		<?php foreach($comments->results as $comment): ?>
		<li>
			<a href="<?php echo Uri::to('admin/comments/edit/' . $comment->id); ?>">
				<strong><?php echo strip_tags($comment->text); ?></strong>
				<span><time><?php echo Date::format($comment->date); ?></time></span>
				<span class="highlight"><?php echo __('global.' . $comment->status);  ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $comments->links(); ?></aside>

	<?php else: ?>
	<p class="empty comments">
		<span class="icon"></span>
		<?php echo __('comments.nocomments_desc'); ?>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>
