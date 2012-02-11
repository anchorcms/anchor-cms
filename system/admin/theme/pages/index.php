<h1>Pages <a href="<?php echo admin_url('pages/add'); ?>">Create a new page</a></h1>

<?php echo Notifications::read(); ?>
	
<section class="content">
	<?php if($pages->length()): ?>
    	<ul class="list">
    	    <?php foreach($pages as $page): ?>
    	    <li>
    	        <a href="<?php echo admin_url('pages/edit/' . $page->id); ?>">
    	            <strong><?php echo truncate($page->name, 4); ?></strong>
    	            <i class="status"><?php echo ucwords($page->status); ?></i>
    	        </a>
    	    </li>
    	    <?php endforeach; ?>
    	</ul>
	<?php else: ?>
    	<p>No pages just yet. Why not <a href="<?php echo admin_url('pages/add'); ?>">write a new one</a>?</p>
	<?php endif; ?>
</section>
