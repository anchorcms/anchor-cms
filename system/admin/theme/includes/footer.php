
	<?php if(($user = Users::authed()) !== false): ?>
	<aside id="sidebar">
		<h2><?php echo __('common.status_check', 'Status check'); ?></h2>
		
		<?php if(error_check() !== false): ?>
		<p>
		<?php if(count(error_check()) === 1): ?>
			<?php echo __('common.found_a_problem', 'Oh no, we found a problem'); ?> 
		<?php else: ?>
			<?php echo __('common.found_some_problems', 'Oh no, we found some problems'); ?>
		<?php endif; ?>
		</p>
		
		<ul>
			<?php foreach(error_check() as $error): ?>
			<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
		<?php else: ?>
			<p><?php echo __('common.nice_job', 'Nice job, keep on going!'); ?></p>        
		<?php endif; ?>
	</aside>
	<?php endif; ?>

	<footer id="bottom">
		<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor, version'); ?> <?php echo ANCHOR_VERSION; ?>. 
		<a href="<?php echo Url::make(); ?>"><?php echo __('common.visit_your_site', 'Visit your site'); ?></a>.
		<?php if(Config::get('debug', false)): ?>
		<br><a id="debug_toggle" href="#debug"><?php echo __('common.show_database_profile', 'Show database profile'); ?></a>
		<?php endif; ?></small>
		
		<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
	</footer>

	<?php if(Config::get('debug', false)): ?>
	<?php echo db_profile(); ?>
	<script>
		(function() {
			var a = $('#debug_toggle'), t = $('#debug_table');

			a.bind('click', function(e) {
				var d = (t.css('display') == '' || t.css('display') == 'none') ? 'block' : 'none';
				t.css('display', d);
				e.end();
			});
		}());
	</script>
	<?php endif; ?>
	
	</body>
</html>
