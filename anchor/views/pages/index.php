<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('pages.pages'); ?></h1>

	<?php if($pages->count): ?>
	<nav>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
		<?php echo Html::link('admin/menu', __('menu.edit_menu'), array('class' => 'btn')); ?>
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
			<?php $child_pages = array(); // Will hold pages that are only children of other pages
			foreach($pages->results as $page) :
				if(menu_parent($page) != 0) $child_pages[] = clone $page;
			endforeach;
			foreach($pages->results as $page) : ?>
				<?php if(menu_has_children($page)) : ?>
					<li>
						<a href="<?php echo Uri::to('admin/pages/edit/' . menu_id($page)); ?>">
							<div>
								<strong><?php echo menu_name($page); ?></strong>
								<span>
									<?php echo page_slug($page); ?>
									<em class="status <?php echo page_status($page); ?>" title="<?php echo __('global.' . page_status($page)); ?>">
										<?php echo __('global.' . page_status($page)); ?>
									</em>
								</span>
							</div>
						</a>
					</li>
					<?php foreach($child_pages as $child) :
						if(menu_parent($child) == menu_id($page)) : ?>
							<li>
								<a href="<?php echo Uri::to('admin/pages/edit/' . menu_id($child)); ?>">
									<div class="indent">
										<strong><?php echo menu_name($child); ?></strong>
										<span>
											<?php echo page_slug($child); ?>
											<em class="status <?php echo page_status($child); ?>" title="<?php echo __('global.' . page_status($child)); ?>">
												<?php echo __('global.' . page_status($child)); ?>
											</em>
										</span>
									</div>
								</a>
							</li>
						<?php endif;
					endforeach;
				elseif(menu_parent($page) == 0) : ?>
					<li>
						<a href="<?php echo Uri::to('admin/pages/edit/' . menu_id($page)); ?>">
							<div>
								<strong><?php echo menu_name($page); ?></strong>
								<span>
									<?php echo page_slug($page); ?>
									<em class="status <?php echo page_status($page); ?>" title="<?php echo __('global.' . page_status($page)); ?>">
										<?php echo __('global.' . page_status($page)); ?>
									</em>
								</span>
							</div>
						</a>
					</li>
				<?php endif;
			endforeach; ?>
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
