<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('pages.pages'); ?></h1>

	<?php if($pages->count): ?>
	<nav>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
		<?php echo Html::link('admin/menu', __('menu.edit_menu'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<nav class="sidebar statuses">
		<?php echo Html::link('admin/pages', '<span class="icon"></span> ' . __('global.all'), array(
			'class' => ($status == 'all') ? 'active' : ''
		)); ?>
		<?php foreach(array('published', 'draft', 'archived') as $type): ?>
		<?php echo Html::link('admin/pages/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
			'class' => ($status == $type) ? 'active' : ''
		)); ?>
		<?php endforeach; ?>
	</nav>

	<?php if($pages->count): ?>
	<ul class="main list">
		<?php
		$outerarray = array();
		foreach($pages->results as $page):
			$innerarray = array('id' => $page->id,'name' => $page->name,'slug' => $page->slug,'status' => $page->status,'parent' => $page->parent);
			array_push($outerarray,$innerarray);
		endforeach; ?>
		<?php
			$i = 0;
			foreach($outerarray as $in => $arr):
        		if ($arr['parent'] != 0){
        			$temp = $arr;
        			unset($outerarray[$in]);
        			foreach($outerarray as $in2 => $arr2):
        				$i++;
	        			if ($arr2['id'] == $temp['parent']){
	        				array_splice($outerarray, $i, 0, array($temp));
	        				$i = 0;
	        				break;
	        			}
	        		endforeach;
        		}
    		endforeach;
    	foreach($outerarray as $in => $arr): ?>
		<li>

			<a href="<?php echo Uri::to('admin/pages/edit/' . $arr['id']); ?>">
				<?php
						if ($arr['parent'] != 0)
							echo '<div class="indent">';
						else
							echo '<div>';
				?>
				<strong><?php echo $arr['name']; ?></strong>
				<span>
					<?php echo $arr['slug']; ?>
					<em class="status <?php echo $arr['status']; ?>" title="<?php echo __('global.' . $arr['status']); ?>">
						<?php echo __('global.' . $arr['status']); ?>
					</em>
				</span>
				</div>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $pages->links(); ?></aside>

	<?php else: ?>
	<aside class="empty pages">
		<span class="icon"></span>
		<?php echo __('pages.nopages_desc'); ?><br>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
	</aside>
	<?php endif; ?>
</section>

<?php echo $footer; ?>
