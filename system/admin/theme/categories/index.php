
<h1><?php echo __('categories.title', 'Categories'); ?>
<a href="<?php echo admin_url('categories/add'); ?>"><?php echo __('categories.create_category', 'Create a new category'); ?></a></h1>

<?php echo Notifications::read(); ?>
	
<section class="content">
	<?php if($categories): ?>
    	<ul class="list">
    	    <?php foreach($categories as $category): ?>
    	    <li>
    	        <a href="<?php echo admin_url('categories/edit/' . $category->id); ?>">
    	            <strong>
    	                <?php echo truncate($category->title, 4); ?>
    	                
    	                <i class="status <?php echo $category->visible ? 'visible' : 'hidden'; ?>" title="<?php echo $category->visible ? 'Visible' : 'Hidden'; ?>"><?php echo $category->visible ? 'Visible' : 'Hidden'; ?></i>
    	            </strong>
    	        </a>
    	    </li>
    	    <?php endforeach; ?>
    	</ul>
	<?php else: ?>
    	<p><a href="<?php echo admin_url('categories/add'); ?>"><?php echo __('categories.no_categories', 'No categories just yet. Why not write a new one?'); ?></a></p>
	<?php endif; ?>
</section>
