<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('categories.edit_category', $category->title); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/categories/edit/' . $category->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('categories.title'); ?>:</label>
				<?php echo Form::text('title', Input::previous('title', $category->title)); ?>
				<em><?php echo __('categories.title_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('categories.slug'); ?>:</label>
				<?php echo Form::text('slug', Input::previous('slug', $category->slug)); ?>
				<em><?php echo __('categories.slug_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('categories.description'); ?>:</label>
				<?php echo Form::textarea('description', Input::previous('description', $category->description)); ?>
				<em><?php echo __('categories.description_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>

			<?php echo Html::link('admin/categories/delete/' . $category->id, __('global.delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>

<?php echo $footer; ?>