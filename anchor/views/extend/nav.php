<ul class="items">
    <?php $pages = array('fields', 'variables', 'metadata', 'themes', 'plugins'); ?>
    
    <?php foreach($pages as $page): $slug = 'admin/extend/' . $page; ?>
	<li class="<?php if(Uri::current() == $slug) echo 'active'; ?>">
		<a href="<?php echo Uri::to('admin/extend/' . $page); ?>">
			<strong><?php echo fallback(__('extend.' . $page), __($page . '.' . $page), 'extend.' . $page); ?></strong>
			<span><?php echo fallback(__('extend.' . $page . '_desc'), __($page . '.' . $page . '_desc'), 'extend.' . $page . '_desc'); ?>.</span>
		</a>
	</li>
	<?php endforeach; ?>
</ul>