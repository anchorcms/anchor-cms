<?php echo $header; ?>

			<h1>Editing &ldquo;<?php echo Str::truncate($article->title, 4); ?>&rdquo;</h1>

			<?php echo $messages; ?>

			<section class="content">

				<form method="post" action="<?php echo url('posts/edit/' . $article->id); ?>" novalidate>

					<input name="token" type="hidden" value="<?php echo $token; ?>">

					<fieldset>
						<p>
							<label for="title"><?php echo __('posts.title', 'Title'); ?>:</label>
							<input id="title" name="title" value="<?php echo Input::old('title', $article->title); ?>">
							
							<em><?php echo __('posts.title_explain', 'Your post&rsquo;s title.'); ?></em>
						</p>
						
						<p>
							<label for="created"><?php echo __('posts.date', 'Date'); ?>:</label>
							<input id="created" name="created" value="<?php echo Input::old('created', $article->created); ?>">
							
							<em><?php echo __('posts.date_explain', 'The date your post will be published.'); ?></em>
						</p>

						<p>
							<label for="slug"><?php echo __('posts.slug', 'Slug'); ?>:</label>
							<input id="slug" name="slug" value="<?php echo Input::old('slug', $article->slug); ?>">
							
							<em><?php echo __('posts.slug_explain', 'The slug for your post.'); ?></em>
						</p>
						
						<p>
							<label for="description"><?php echo __('posts.description', 'Description'); ?>:</label>
							<textarea id="description" name="description"><?php echo Input::old('description', $article->description); ?></textarea>
							
							<em><?php echo __('posts.description_explain', 'A brief outline of what your post is about.'); ?></em>
						</p>
						
						<p>
							<label for="html"><?php echo __('posts.content', 'Content'); ?>:</label>
							<textarea id="html" name="html"><?php echo Input::old('html', $article->html); ?></textarea>
							
							<em><?php echo __('posts.content_explain', 'Your post\'s main content. Enjoys a healthy dose of valid HTML.'); ?></em>
						</p>
						
						<p>
							<label for="status"><?php echo __('posts.status', 'Status'); ?>:</label>
							<select id="status" name="status">
								<?php foreach($statuses as $value => $status): ?>
								<?php $selected = (Input::old('status', $article->status) == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $status; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('posts.status_explain', 'Statuses: live (published), pending (draft), or hidden (archived).'); ?></em>
						</p>

						<p>
							<label for="template"><?php echo __('posts.template', 'Template'); ?>:</label>
							<select id="template" name="template">
								<?php foreach($templates as $value => $template): ?>
								<?php $selected = (Input::old('template', $article->template) == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $template; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('posts.template_explain', 'Theme template for your post.'); ?></em>
						</p>

						<p>
							<label for="category"><?php echo __('posts.category', 'Category'); ?>:</label>
							<select id="category" name="category">
								<?php foreach($categories as $value => $category): ?>
								<?php $selected = (Input::old('category', $article->category) == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $category; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('posts.category_explain', 'Post category.'); ?></em>
						</p>
						
						<p>
							<label for="comments"><?php echo __('posts.allow_comments', 'Allow Comments'); ?>:</label>
							<input id="comments" name="comments" type="checkbox" value="1"<?php if(Input::old('comments', $article->comments)) echo ' checked'; ?>>
							<em><?php echo __('posts.allow_comments_explain', 'This will allow users to comment on your posts.'); ?></em>
						</p>
					</fieldset>
					
					<p class="buttons">
						<button type="submit"><?php echo __('posts.save', 'Save'); ?></button>
						<a href="<?php echo url('posts'); ?>"><?php echo __('posts.return_posts', 'Return to posts'); ?></a>
					</p>
					
				</form>
			</section>

<?php echo $footer; ?>