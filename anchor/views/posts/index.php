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
	    <a href="<?php echo Uri::to('admin/posts'); ?>" class="<?php if(!Input::get('cat')) echo 'active'; ?>"><?php echo __('global.all', 'All'); ?></a>
	    
	    <?php foreach(Category::paginate(1,999)->results as $result): ?>
	    <a href="?cat=<?php echo $result->slug; ?>" class="<?php if(Input::get('cat') === $result->slug) echo 'active'; ?>">
	        <?php echo $result->title; ?>
	    </a>
	    <?php endforeach; ?>
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