<?php echo $header; ?>

			<h1>Editing Comment</h1>

			<?php echo $messages; ?>

			<section class="content">

				<form method="post" action="<?php echo url('comments/edit/' . $comment->id); ?>" novalidate>

					<input name="token" type="hidden" value="<?php echo $token; ?>">

					<fieldset>
						<p>
							<label for="name"><?php echo __('comments.name', 'Name'); ?>:</label>
							<input id="name" name="name" value="<?php echo Input::old('name', $comment->name); ?>">
							
							<em><?php echo __('comments.name_explain', 'Author name.'); ?></em>
						</p>
						
						<p>
							<label for="email"><?php echo __('comments.email', 'Email'); ?>:</label>
							<input id="email" name="email" value="<?php echo Input::old('email', $comment->email); ?>">
							
							<em><?php echo __('comments.email_explain', 'Author email.'); ?></em>
						</p>
						
						<p>
							<label for="text"><?php echo __('comments.text', 'Comment'); ?>:</label>
							<textarea id="text" name="text"><?php echo Input::old('text', $comment->text); ?></textarea>
						</p>
						
						<p>
							<label for="status"><?php echo __('comments.status', 'Status'); ?>:</label>
							<select id="status" name="status">
								<?php foreach($statuses as $value => $status): ?>
								<?php $selected = (Input::old('status', $comment->status) == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $status; ?></option>
								<?php endforeach; ?>
							</select>
						</p>
					</fieldset>
					
					<p class="buttons">
						<button type="submit"><?php echo __('comments.save', 'Save'); ?></button>
						<a href="<?php echo url('comments'); ?>"><?php echo __('comments.return_comments', 'Return to comments'); ?></a>
					</p>
				</form>
			</section>

<?php echo $footer; ?>