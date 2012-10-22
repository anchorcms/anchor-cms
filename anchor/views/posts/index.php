<?php echo $header; ?>

<h1><?php echo __('posts.posts', 'Posts'); ?>
<a href="<?php echo url('posts/add'); ?>"><?php echo __('posts.create_post', 'Create a new post'); ?></a></h1>

<?php echo $messages; ?>

<section class="content">
	<?php if($posts->count): ?>
	<ul class="list">
		<?php foreach($posts->results as $article): ?>
		<li>
			<a href="<?php echo url('posts/edit/' . $article->id); ?>">
				<strong><?php echo $article->title; ?></strong>
				<span>
					<time><?php echo Date::format($article->created); ?></time>

					<i title="This post is currently <?php echo $article->status; ?>" class="status <?php echo strtolower($article->status); ?>">
						<?php echo $article->status; ?></i>
				</span>

				<p><?php echo strip_tags($article->description); ?></p>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $posts->links(); ?>
	<?php else: ?>
	<p><a href="<?php echo url('posts/add'); ?>"><?php echo __('posts.noposts', 'No posts just yet. Why not write a new one?'); ?></a></p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>