<?php echo $header; ?>

<h1><?php echo __('posts.posts', 'Posts'); ?>
<?php if($posts->count): ?>
<a href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.create_post', 'Create a new post'); ?></a>
<?php endif; ?>
</h1>

<section class="content">
	<?php echo $messages; ?>

	<?php if($posts->count): ?>
	<ul class="list">
		<?php foreach($posts->results as $article): ?>
		<li>
			<a href="<?php echo admin_url('posts/edit/' . $article->id); ?>">
				<strong><?php echo $article->title; ?></strong>
				<span>
					<time><?php echo Date::format($article->created); ?></time>

					<em title="This post is currently <?php echo $article->status; ?>"
						class="status <?php echo strtolower($article->status); ?>"><?php echo $article->status; ?></em>
				</span>

				<p><?php echo strip_tags($article->description); ?></p>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $posts->links(); ?>
	<?php else: ?>

	<p class="empty posts">
		<span class="icon"></span>
		<?php echo __('posts.noposts_desc', 'You don’t have any posts!'); ?><br>
		<a class="btn" href="<?php echo admin_url('posts/add'); ?>"><?php echo __('posts.create_post', 'Why not write a new one?'); ?></a>
	</p>
	
	<?php endif; ?>
</section>

<?php echo $footer; ?>