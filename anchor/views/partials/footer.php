
		<?php if(Auth::user()): ?>
		<footer class="wrap bottom">
			<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor, version %s', VERSION); ?>.
			<?php echo 'Running PHP ' . PHP_VERSION . '.'; ?></small>

			<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
		</footer>

		<script src="<?php echo asset('anchor/views/assets/js/admin.js'); ?>"></script>
		<?php endif; ?>
	</body>
</html>