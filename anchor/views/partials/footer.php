
		<?php if(Auth::user()): ?>
		<footer id="bottom">
			<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor, version %s', VERSION); ?>.
			<?php echo 'Running PHP ' . phpversion() . '.'; ?></small>

			<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
		</footer>

		<script src="<?php echo admin_asset('js/zepto.js'); ?>"></script>
		<script src="<?php echo admin_asset('js/admin.js'); ?>"></script>
		<?php else: ?>
		<script>
		    var b = document.body;

		    b.style.marginTop = -(b.clientHeight / 2) + 'px';
		</script>
		<?php endif; ?>
	</body>
</html>