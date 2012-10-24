<?php echo $header; ?>

<h1><?php echo __('pages.pages', 'Pages'); ?>
<a href="<?php echo url('pages/add'); ?>"><?php echo __('pages.create_page', 'Create a new page'); ?></a></h1>

<?php echo $messages; ?>

<section class="content">
	<ul class="list">
		<?php foreach($pages->results as $page): ?>
		<li>
			<a href="<?php echo url('pages/edit/' . $page->id); ?>">
				<strong><?php echo $page->name; ?></strong>

				<span>
					<?php echo $page->slug; ?>

					<em class="status <?php echo $page->status; ?>"
						title="This page is currently <?php echo $page->status; ?>"><?php echo ucfirst($page->status); ?></em>
				</span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php echo $pages->links(); ?>
</section>

<?php echo $footer; ?>