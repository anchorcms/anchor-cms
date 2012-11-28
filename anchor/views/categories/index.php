<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('categories.title', 'Categories'); ?></h1>

	<nav>
		<?php echo Html::link(admin_url('categories/add'), __('categories.create_category', 'Create a new category'), array('class' => 'btn')); ?>
	</nav>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($categories->results as $category): ?>
		<li>
			<a href="<?php echo admin_url('categories/edit/' . $category->id); ?>">
				<strong><?php echo $category->title; ?></strong>

				<span><?php echo $category->slug; ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $categories->links(); ?></aside>
</section>

<?php echo $footer; ?>