<h1><?php echo __('users.editing', 'Editing'); ?> 
<?php echo $user->username; ?>&rsquo;s <?php echo __('users.profile', 'profile'); ?></h1>

<section class="content">

    <?php echo Notifications::read(); ?>
    
	<form method="post" action="<?php echo Url::current(); ?>" novalidate autocomplete="off">

		<input name="token" type="hidden" value="<?php echo Csrf::token(); ?>">
		
		<fieldset class="half split">
			<p>
				<label for="real_name"><?php echo __('users.real_name', 'Real name'); ?>:</label>
				<input id="real_name" name="real_name" value="<?php echo Input::post('real_name', $user->real_name); ?>">
				
				<em><?php echo __('users.real_name_explain', 'The user&rsquo;s real name. Used in author bylines (visible to public).'); ?></em>
			</p>
						
			<p>
				<label for="bio"><?php echo __('users.bio', 'Biography'); ?>:</label>
				<textarea id="bio" name="bio"><?php echo Input::post('bio', $user->bio); ?></textarea>
				
				<em><?php echo __('users.bio_explain', 'A short biography for your user. Uses Markdown.'); ?></em>
			</p>
			
			<p>
				<label for="status"><?php echo __('users.status', 'Status'); ?>:</label>
				<select id="status" name="status">
					<?php foreach(array(
						'inactive' => __('users.inactive', 'Inactive'),
						'active' => __('users.active', 'Active')
					) as $value => $status): ?>
					<?php $selected = (Input::post('status', $user->status) == $value) ? ' selected' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
						<?php echo $status; ?>
					</option>
					<?php endforeach; ?>
				</select>
				
				<em><?php echo __('users.status_explain', 'If inactive, the user will not be able to log in.'); ?></em>
			</p>
			
			<p>
				<label for="role"><?php echo __('users.role', 'Role'); ?>:</label>
				<select id="role" name="role">
					<?php foreach(array(
						'administrator' => __('users.administrator', 'Administrator'), 
						'editor' => __('users.editor', 'Editor'), 
						'user' => __('users.user', 'User')
					) as $value => $role): ?>
					<?php $selected = (Input::post('role', $user->role) == $value) ? ' selected' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>>
						<?php echo $role; ?>
					</option>
					<?php endforeach; ?>
				</select>
				
				<em><?php echo __('users.role_explain', 'The user&rsquo;s role. See <a href="//anchorcms.com/docs/roles">here</a> for more info.'); ?></em>
			</p>
		</fieldset>
		
		<fieldset class="half split">
			<legend><?php echo __('users.user_details', 'User details'); ?></legend>
			<em><?php echo __('users.user_details_explain', 'Create the details for your new user to log in to Anchor.'); ?></em>
		
			<p>
				<label for="username"><?php echo __('users.username', 'Username'); ?>:</label>
				<input id="username" name="username" value="<?php echo Input::post('username', $user->username); ?>">
				
				<em><?php echo __('users.username_explain', 'The desired username. Can be changed later.'); ?></em>
			</p>

			<p>
				<label for="password"><?php echo __('users.password', 'Password'); ?>:</label>
				<input id="password" type="password" name="password">
				
				<em><?php echo __('users.password_blank', 'Leave blank for no change.'); ?></em>
			</p>
			
			<p>
				<label for="email"><?php echo __('users.email', 'Email'); ?>:</label>
				<input id="email" name="email" value="<?php echo Input::post('email', $user->email); ?>">

				<em><?php echo __('users.email_explain', 'The user&rsquo;s email address. Needed if the user forgets their password.'); ?></em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit"><?php echo __('users.update', 'Update'); ?></button>
			<?php if(Users::authed()->id !== $user->id): ?>
			<button class="red" name="delete" type="submit"><?php echo __('users.delete', 'Delete'); ?></button>
			<?php endif; ?>
		</p>
	</form>

</section>

<?php if(Users::authed()->id !== $user->id): ?>
<script src="<?php echo theme_url('assets/js/lang.js'); ?>"></script>
<script>
	// define global js translations
	// for our popups
	Lang.load('users');
</script>

<script src="<?php echo theme_url('assets/js/confirm.js'); ?>"></script>
<script>
	// confirm for deletions
	$('button[name=delete]').bind('click', function(event) {
		Confirm.open(function() {
			var form = $('form'), input = new Element('input', {'type': 'hidden', 'name': 'delete'});
			form.append(input);
			form.submit();
		});
		event.end();
	});
</script>
<?php endif; ?>