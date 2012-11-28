<?php echo $header; ?>

<hgroup class="wrap">
	<h1>Editing &ldquo;<?php echo Str::truncate($category->title, 4); ?>&rdquo;</h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo admin_url('categories/edit/' . $category->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('categories.title', 'Title'); ?>:</label>
				<?php echo Form::text('title', Input::old('title', $category->title)); ?>

				<em><?php echo __('categories.title_explain', 'Your category title.'); ?></em>
			</p>

			<p>
				<label><?php echo __('categories.slug', 'Slug'); ?>:</label>
				<?php echo Form::text('slug', Input::old('slug', $category->slug)); ?>

				<em><?php echo __('categories.slug_explain', 'The slug for your category.'); ?></em>
			</p>

			<p>
				<label><?php echo __('categories.description', 'Description'); ?>:</label>
				<?php echo Form::textarea('description', Input::old('description', $category->description)); ?>

				<em><?php echo __('categories.description_explain', 'A brief outline of what your category is about.'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('categories.save', 'Save'), array('type' => 'submit', 'class' => 'btn')); ?>

			<?php echo Html::link(admin_url('categories/delete/' . $category->id), __('categories.delete', 'Delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>
</section>

<script src="<?php echo admin_asset('js/slug.js'); ?>"></script>

<?php echo $footer; ?>