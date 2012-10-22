<?php echo $header; ?>

			<h1><?php echo __('extend.extend', 'Extend'); ?></h1>

			<nav>
				<ul>
					<li><a href="<?php echo url('extend/add'); ?>"><?php echo __('extend.create_field', 'Create a new field'); ?></a></li>
				</ul>
			</nav>

			<?php echo $messages; ?>

			<section class="content">
				<ul class="list">
					<?php foreach($extend->results as $field): ?>
					<li>
						<a href="<?php echo url('extend/edit/' . $field->id); ?>"><?php echo $field->label; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>

				<?php echo $extend->links(); ?>
			</section>

<?php echo $footer; ?>