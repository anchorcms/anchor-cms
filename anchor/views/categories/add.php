<?php echo $header; ?>

<hgroup class="wrap">
	<h1>Create a new category</h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('categories/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="title"><?php echo __('categories.title', 'Title'); ?>:</label>
				<input id="title" name="title" value="<?php echo Input::old('title'); ?>">

				<em><?php echo __('categories.title_explain', 'Your category title.'); ?></em>
			</p>

			<p>
				<label for="slug"><?php echo __('categories.slug', 'Slug'); ?>:</label>
				<input id="slug" name="slug" value="<?php echo Input::old('slug'); ?>">

				<em><?php echo __('categories.slug_explain', 'The slug for your category.'); ?></em>
			</p>

			<p>
				<label for="description"><?php echo __('categories.description', 'Description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::old('description'); ?></textarea>

				<em><?php echo __('categories.description_explain', 'A brief outline of what your category is about.'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('categories.save', 'Save'), array('type' => 'submit', 'class' => 'btn')); ?>
		</aside>

	</form>
</section>

<script src="<?php echo admin_asset('js/slug.js'); ?>"></script>

<?php echo $footer; ?>