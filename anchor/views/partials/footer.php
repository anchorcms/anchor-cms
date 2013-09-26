        </div>

        <script src="<?php echo asset('anchor/views/assets/js/zepto.js'); ?>"></script>
        <script src="<?php echo asset('anchor/views/assets/js/admin.js'); ?>"></script>
        <script src="<?php echo asset('anchor/views/assets/js/notify.js'); ?>"></script>
        <script src="<?php echo asset('anchor/views/assets/js/preloader.js'); ?>"></script>

		<?php if(Auth::user()): ?>
		<script>
			// Confirm any deletions
			$('.delete').on('click', function() {return confirm('<?php echo __('global.confirm_delete'); ?>');});
		</script>
		<?php endif; ?>
	</body>
</html>