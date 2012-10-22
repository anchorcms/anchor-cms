<?php echo $header; ?>

<h1><?php echo __('comments.comments', 'Comments'); ?>
<a href="<?php echo url('comments'); ?>">All</a>
<a href="<?php echo url('comments/pending'); ?>">Pending</a>
<a href="<?php echo url('comments/approved'); ?>">Approved</a>
<a href="<?php echo url('comments/spam'); ?>">Spam</a></h1>

<?php echo $messages; ?>

<section class="content">
<?php if($comments->count): ?>
	<ul class="list">
		<?php foreach($comments->results as $comment): ?>
		<li>
			<a href="<?php echo url('comments/edit/' . $comment->id); ?>">
				<strong><?php echo Str::truncate($comment->text, 10); ?></strong>

				<span><?php echo __('comments.created', 'Created'); ?> <time><?php echo Date::format($comment->date); ?></time>
				<?php echo __('comments.by', 'by'); ?> <?php echo $comment->name; ?></span>

				<span class="highlight"><?php echo $comment->status; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $comments->links(); ?>
<?php else: ?>
	<p><?php echo __('comments.no_comments', 'No comments found.'); ?></p>
<?php endif; ?>
</section>

<?php echo $footer; ?>