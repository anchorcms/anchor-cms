<?php echo $header; ?>

			<h1>Create a new page</h1>

			<?php echo $messages; ?>

			<section class="content">

				<form method="post" action="<?php echo url('pages/add'); ?>" novalidate>

					<input name="token" type="hidden" value="<?php echo $token; ?>">

					<fieldset>
						<p>
							<label for="name"><?php echo __('pages.name', 'Name'); ?>:</label>
							<input id="name" name="name" value="<?php echo Input::old('name'); ?>">
							
							<em><?php echo __('pages.name_explain', 'Your page&rsquo;s name.'); ?></em>
						</p>

						<p>
							<label for="title"><?php echo __('pages.title', 'Title'); ?>:</label>
							<input id="title" name="title" value="<?php echo Input::old('title'); ?>">
							
							<em><?php echo __('pages.title_explain', 'Your page&rsquo;s title.'); ?></em>
						</p>

						<p>
							<label for="slug"><?php echo __('pages.slug', 'Slug'); ?>:</label>
							<input id="slug" name="slug" value="<?php echo Input::old('slug'); ?>">
							
							<em><?php echo __('pages.slug_explain', 'The slug for your page.'); ?></em>
						</p>
						
						<p>
							<label for="content"><?php echo __('pages.content', 'Content'); ?>:</label>
							<textarea id="content" name="content"><?php echo Input::old('content'); ?></textarea>
							
							<em><?php echo __('pages.content_explain', 'Your page\'s main content. Enjoys a healthy dose of valid HTML.'); ?></em>
						</p>
						
						<p>
							<label for="status"><?php echo __('pages.status', 'Status'); ?>:</label>
							<select id="status" name="status">
								<?php foreach($statuses as $value => $status): ?>
								<?php $selected = (Input::old('status') == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $status; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('pages.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
						</p>
					</fieldset>
					
					<p class="buttons">
						<button type="submit"><?php echo __('pages.save', 'Save'); ?></button>
						<a href="<?php echo url('pages'); ?>"><?php echo __('pages.return_pages', 'Return to pages'); ?></a>
					</p>
					
				</form>
			</section>

<?php echo $footer; ?>