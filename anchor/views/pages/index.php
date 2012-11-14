<?php echo $header; ?>

<h1><?php echo __('pages.pages', 'Pages'); ?>
<?php if($pages->count): ?>
<a href="<?php echo url('pages/add'); ?>"><?php echo __('pages.create_page', 'Create a new page'); ?></a>
<?php endif; ?>
</h1>

<section class="content">
	<?php echo $messages; ?>

	<?php if($pages->count): ?>
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
	<?php else: ?>
	<p class="empty pages">
		<span class="icon"></span>
		<?php echo __('comments.nopages_desc', 'You don’t have any pages.'); ?><br>
		
		<a class="btn" href="<?php echo url('pages/add'); ?>"><?php echo __('pages.create_page', 'Create a new page'); ?></a>
	</p>
	<?php endif; ?>
</section>

<?php echo $footer; ?>