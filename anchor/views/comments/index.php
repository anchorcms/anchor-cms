<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.comments'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>
	
	<nav class="sidebar statuses">
	    <?php foreach(array(
	        '' => 'comments.all_comments',
	        '/pending' => 'global.pending',
	        '/approved' => 'global.approved',
	        '/spam' => 'global.spam'
	    ) as $url => $str): ?>
    	    <a class="link <?php echo str_replace('/', '', $url) . (Uri::to('admin/comments' . $url) === '/' . Uri::current() ? ' active' : ''); ?>" href="<?php echo Uri::to('admin/comments' . $url); ?>">
    	        <span class="icon"></span>
    	        <?php echo __($str); ?>
    	    </a>
	    <?php endforeach; ?>
	</nav>

	<div class="main">
    	<?php if($comments->count): ?>
    	<ul class="list">
    		<?php foreach($comments->results as $comment): ?>
    		<li>
    			<a href="<?php echo Uri::to('admin/comments/edit/' . $comment->id); ?>">
    				<strong><?php echo strip_tags($comment->text); ?></strong>
    				<span><time><?php echo Date::format($comment->date); ?></time></span>
    				<span class="highlight"><?php echo $comment->status; ?></span>
    			</a>
    		</li>
    		<?php endforeach; ?>
    	</ul>
    
    	<aside class="paging"><?php echo $comments->links(); ?></aside>
    
    	<?php else: ?>
    	<p class="empty comments">
    		<span class="icon"></span> <?php echo __('comments.nocomments_desc'); ?>
    	</p>
    	<?php endif; ?>
    </div>
</section>

<?php echo $footer; ?>