
		<?php if(Auth::user()): ?>
		<footer id="bottom">
			<small><?php echo __('common.powered_by_anchor', 'Powered by Anchor'); ?>.
			<?php echo 'Running PHP ' . phpversion() . '.'; ?></small>

			<em><?php echo __('common.make_blogging_beautiful', 'Make blogging beautiful.'); ?></em>
		</footer>

		<script src="<?php echo asset('js/zepto.js'); ?>"></script>
		<script src="<?php echo asset('js/admin.js'); ?>"></script>

		<?php endif; ?>
	</body>
</html>