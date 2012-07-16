	<footer id="bottom">
		<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor, version'); ?> <?php echo ANCHOR_VERSION; ?>. 
		<a href="<?php echo Url::make(); ?>"><?php echo __('common.visit_your_site', 'Visit your site'); ?></a>.
		<?php if(Config::get('debug', false)): ?>
		<br><a id="debug_toggle" href="#debug"><?php echo __('common.show_database_profile', 'Show database profile'); ?></a>
		<?php endif; ?></small>
		
		<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
	</footer>

	<script src="<?php echo theme_url('assets/js/admin.js'); ?>"></script>
	<?php if(Config::get('debug', false)) echo db_profile(); ?>
	</body>
</html>
