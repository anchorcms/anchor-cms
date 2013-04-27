<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('pages.pages'); ?></h1>

	<?php if($pages->count): ?>
	<nav>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<nav class="sidebar statuses">
		<?php echo Html::link('admin/pages', '<span class="icon"></span> ' . __('global.all'), array(
			'class' => ($status == 'all') ? 'active' : ''
		)); ?>
		<?php foreach(array('published', 'draft', 'archived') as $type): ?>
		<?php echo Html::link('admin/pages/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
			'class' => ($status == $type) ? 'active' : ''
		)); ?>
		<?php endforeach; ?>
	</nav>

	<?php if($pages->count): ?>
	<ul class="main list">
		<?php foreach($pages->results as $page): ?>
		<li>
			<a href="<?php echo Uri::to('admin/pages/edit/' . $page->id); ?>">
				<strong><?php echo $page->name; ?></strong>

				<span>
					<?php echo $page->slug; ?>

					<em class="status <?php echo $page->status; ?>" title="<?php echo __('global.' . $page->status); ?>">
						<?php echo __('global.' . $page->status); ?>
					</em>
				</span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $pages->links(); ?></aside>

	<?php else: ?>
	<aside class="empty pages">
		<span class="icon"></span>
		<?php echo __('pages.nopages_desc'); ?><br>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
	</aside>
	<?php endif; ?>
</section>

<?php echo $footer; ?>