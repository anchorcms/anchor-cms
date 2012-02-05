<h1>Pages <a href="<?php echo base_url('admin/pages/add'); ?>">Create a new page</a></h1>

<?php echo notifications(); ?>
	
<section class="content">
	
	<?php if(has_pages()): ?>
    	<ul class="list">
    	    <?php while(pages()): ?>
    	    <li>
    	        <a href="<?php echo base_url('admin/pages/edit/' . page_id()); ?>">
    	            <strong><?php echo truncate(page_name(), 4); ?></strong>
    	            <i class="status"><?php echo ucwords(page_status()); ?></i>
    	        </a>
    	    </li>
    	    <?php endwhile; ?>
    	</ul>
	<?php else: ?>
    	<p>No pages just yet. Why not <a href="<?php echo base_url('admin/pages/add'); ?>">write a new one</a>?</p>
	<?php endif; ?>
</section>
