<?php echo $header; ?>

			<h1>Create a new category</h1>

			<?php echo $messages; ?>

			<section class="content">

				<form method="post" action="<?php echo url('categories/add'); ?>" novalidate>

					<input name="token" type="hidden" value="<?php echo $token; ?>">

					<fieldset>
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
					
					<p class="buttons">
						<button type="submit"><?php echo __('categories.save', 'Save'); ?></button>
						<a href="<?php echo url('categories'); ?>"><?php echo __('categories.return_categories', 'Return to categories'); ?></a>
					</p>
					
				</form>
			</section>

<?php echo $footer; ?>