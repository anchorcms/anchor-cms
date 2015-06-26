<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('posts.posts'); ?></h1>

	<?php if($posts->count): ?>
	<nav>
		<?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<nav class="sidebar">
		<nav class="statuses">
			<p>Statuses</p>
			<?php echo Html::link('admin/posts', '<span class="icon"></span> ' . __('global.all'), array(
				'class' => isset($status) ? ($status == 'all' ? 'active' : '') : ''
			)); ?>
			<?php foreach(array('published', 'draft', 'archived') as $type): ?>
			<?php echo Html::link('admin/posts/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
				'class' => ($status == $type) ? 'active' : ''
			)); ?>
			<?php endforeach; ?>
		</nav>
		<br>
		<nav class="categories">
			<p>Categories</p>
			<?php foreach($categories as $cat): ?>
			<?php echo Html::link('admin/posts/category/' . $cat->slug, $cat->title, array(
				'class' => (isset($category) and $category->id == $cat->id) ? 'active' : ''
			)); ?>
			<?php endforeach; ?>
		</nav>
	</nav>

	<?php if($posts->count): ?>
	<ul class="main list">
		<?php foreach($posts->results as $article): ?>
		<li>
			<a href="<?php echo Uri::to('admin/posts/edit/' . $article->id); ?>">
				<strong><?php echo $article->title; ?></strong>
				<span>
					<time><?php echo Date::format($article->created); ?></time>

					<em class="status <?php echo $article->status; ?>" title="<?php echo __('global.' . $article->status); ?>">
						<?php echo __('global.' . $article->status); ?>
					</em>
				</span>

				<p><?php echo strip_tags($article->description); ?></p>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $posts->links(); ?></aside>

	<?php else: ?>

	<p class="empty posts">
		<span class="icon"></span>
		<?php echo __('posts.noposts_desc'); ?><br>
		<?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
	</p>

	<?php endif; ?>
</section>

<?php echo $footer; ?>
