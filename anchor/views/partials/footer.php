
		<?php if(Auth::user()): ?>
		<footer class="wrap bottom">
			<small><?php echo __('global.powered_by_anchor', VERSION); ?></small>
			<em><?php echo __('global.make_blogging_beautiful'); ?></em>
		</footer>

		<script>
			// Confirm any deletions
			$('.delete').on('click', function() {return confirm('<?php echo __('global.confirm_delete'); ?>');});
		</script>
		<?php endif; ?>
	</body>
</html>