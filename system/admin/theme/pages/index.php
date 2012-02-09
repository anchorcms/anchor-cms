<h1>Pages <a href="<?php echo Url::make('admin/pages/add'); ?>">Create a new page</a></h1>

<?php echo Notifications::read(); ?>
	
<section class="content">
	
	<?php if(count($pages)): ?>
    	<ul class="list">
    	    <?php foreach($pages as $page): ?>
    	    <li>
    	        <a href="<?php echo Url::make('admin/pages/edit/' . $page->id); ?>">
    	            <strong><?php echo truncate($page->name, 4); ?></strong>
    	            <i class="status"><?php echo ucwords($page->status); ?></i>
    	        </a>
    	    </li>
    	    <?php endforeach; ?>
    	</ul>
	<?php else: ?>
    	<p>No pages just yet. Why not <a href="<?php echo Url::make('admin/pages/add'); ?>">write a new one</a>?</p>
	<?php endif; ?>
</section>
