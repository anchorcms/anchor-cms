<?php echo $header; ?>

			<h1>Create a new user</h1>

			<?php echo $messages; ?>

			<section class="content">

				<form method="post" action="<?php echo url('users/add'); ?>" novalidate>

					<input name="token" type="hidden" value="<?php echo $token; ?>">

					<fieldset>
						<p>
							<label for="real_name"><?php echo __('users.real_name'); ?>:</label>
							<input id="real_name" name="real_name" value="<?php echo Input::old('real_name'); ?>">
							
							<em><?php echo __('users.real_name_explain'); ?></em>
						</p>

						<p>
							<label for="username"><?php echo __('users.username'); ?>:</label>
							<input id="username" name="username" value="<?php echo Input::old('username'); ?>">
							
							<em><?php echo __('users.username_explain'); ?></em>
						</p>

						<p>
							<label for="email"><?php echo __('users.email'); ?>:</label>
							<input id="email" name="email" value="<?php echo Input::old('email'); ?>">
							
							<em><?php echo __('users.email_explain'); ?></em>
						</p>
						
						<p>
							<label for="password"><?php echo __('users.password'); ?>:</label>
							<input id="password" name="password" type="password">
							
							<em><?php echo __('users.password_blank'); ?></em>
						</p>
						
						<p>
							<label for="bio"><?php echo __('users.bio'); ?>:</label>
							<textarea id="bio" name="bio"><?php echo Input::old('bio'); ?></textarea>
							
							<em><?php echo __('users.bio_explain'); ?></em>
						</p>
						
						<p>
							<label for="status"><?php echo __('users.status'); ?>:</label>
							<select id="status" name="status">
								<?php foreach($statuses as $value => $status): ?>
								<?php $selected = (Input::old('status') == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $status; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('users.status_explain'); ?></em>
						</p>

						<p>
							<label for="role"><?php echo __('users.role'); ?>:</label>
							<select id="role" name="role">
								<?php foreach($roles as $value => $template): ?>
								<?php $selected = (Input::old('role') == $value) ? ' selected' : ''; ?>
								<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $template; ?></option>
								<?php endforeach; ?>
							</select>
							
							<em><?php echo __('users.role_explain', 'Theme template for your post.'); ?></em>
						</p>
					</fieldset>
					
					<p class="buttons">
						<button type="submit"><?php echo __('users.save', 'Save'); ?></button>
						<a href="<?php echo url('posts'); ?>"><?php echo __('users.return_users'); ?></a>
					</p>
					
				</form>
			</section>

<?php echo $footer; ?>