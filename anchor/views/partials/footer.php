
		<?php if(Auth::user()): ?>
		<footer class="wrap bottom">
			<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor, version %s', VERSION); ?>.
			<?php echo 'Running PHP ' . phpversion() . '.'; ?></small>

			<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
		</footer>
		<?php endif; ?>
	</body>
</html>