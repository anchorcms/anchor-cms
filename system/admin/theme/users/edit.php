
<h1>Edit User</h1>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" autocomplete="off">
		<fieldset>
			<legend>Details</legend>
			
			<?php echo notifications(); ?>

			<p><label>Display Name<br>
			<input name="real_name" type="text" value="<?php echo Input::post('real_name', user_real_name()); ?>"></label></p>

			<p><label>Bio<br>
			<textarea name="bio"><?php echo Input::post('bio', user_bio()); ?></textarea></label></p>

			<p><label>Status<br>
			<select name="status">
				<?php foreach(array('inactive','active') as $status): ?>
				<option value="<?php echo $status; ?>"<?php if(Input::post('status', user_status()) == $status) echo 'selected'; ?>>
					<?php echo ucwords($status); ?>
				</option>
				<?php endforeach; ?>
			</select></label></p>
			
			<p><label>Role<br>
			<select name="role">
				<?php foreach(array('administrator', 'editor', 'user') as $role): ?>
				<option value="<?php echo $role; ?>"<?php if(Input::post('role', user_role()) == $role) echo 'selected'; ?>>
					<?php echo ucwords($role); ?>
				</option>
				<?php endforeach; ?>
			</select></label></p>
		</fieldset>
		
		<fieldset>
			<legend>Account</legend>

			<p><label>Username<br>
			<input name="username" type="text" value="<?php echo Input::post('username', user_name()); ?>"></label></p>
			
			<p><label>Password<br>
			<input name="password" type="password"></label><br>
			<small>Leave blank to keep the current password</small></p>
		</fieldset>
		
		<p>
			<button name="save" type="submit">Save</button>
			<?php if(user_authed_id() != user_id()): ?>
			<button name="delete" type="submit">Delete</button>
			<?php endif; ?>
			<a href="/admin/users">Return to users</a>
		</p>
	</form>

</section>

