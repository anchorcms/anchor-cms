<?php echo $header; ?>

<h1><?php echo __('categories.title', 'Categories'); ?>
<a href="<?php echo url('categories/add'); ?>"><?php echo __('categories.create_category', 'Create a new category'); ?></a></h1>

<?php echo $messages; ?>

<section class="content">

	<ul class="list">
		<?php foreach($categories->results as $category): ?>
		<li>
			<a href="<?php echo url('categories/edit/' . $category->id); ?>">
				<strong><?php echo $category->title; ?></strong>

				<span><?php echo site($category->slug); ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $categories->links(); ?>
</section>

<?php echo $footer; ?>