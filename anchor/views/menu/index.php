<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('menu.menu', 'Menu'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<?php if(count($pages)): ?>
	<ul class="sortable">
		<?php foreach($pages as $page): ?>
		<li class="item" draggable="true">
			<span data-id="<?php echo $page->id; ?>"><?php echo $page->name; ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="empty">
		<span class="icon"></span>
		No menu items yet.
	</p>
	<?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/sortable.js'); ?>"></script>
<script>
	$('.sortable').sortable({
		element: 'li',
		dropped: function() {
			var data = {sort: []};

			$('.sortable span').each(function(index, item) {
				data.sort.push($(item).data('id'));
			});

			$.ajax({
				'type': 'POST',
				'url': '<?php echo Uri::to("admin/menu/update"); ?>',
				'data': $.param(data)
			});
		}
	});
</script>

<?php echo $footer; ?>