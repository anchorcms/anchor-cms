<h1>Editing <?php echo $user->username; ?>&rsquo;s profile</h1>

<?php echo Notifications::read(); ?>

<section class="content">

	<form method="post" action="<?php echo Url::current(); ?>" novalidate autocomplete="off">
		<fieldset>
			<p>
    			<label for="real_name">Real name:</label>
    			<input id="real_name" name="real_name" value="<?php echo Input::post('real_name', $user->real_name); ?>">
    			
    			<em>The user&rsquo;s real name. Used in author bylines (visible to public).</em>
    		</p>
						
            <p>
                <label for="bio">Biography:</label>
                <textarea id="bio" name="bio"><?php echo Input::post('bio', $user->bio); ?></textarea>
                
                <em>A short biography for your user. Accepts valid HTML.</em>
            </p>
			
			<p>
			    <label for="status">Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('inactive','active') as $status): ?>
    				<?php $selected = (Input::post('status', $user->status) == $status) ? ' selected' : ''; ?>
    				<option value="<?php echo $status; ?>"<?php echo $selected; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>If inactive, the user will not be able to log in.</em>
			</p>
			
			<p>
			    <label for="role">Role:</label>
    			<select id="role" name="role">
    				<?php foreach(array('administrator', 'editor', 'user') as $role): ?>
    				<?php $selected = (Input::post('role', $user->role) == $role) ? ' selected' : ''; ?>
    				<option value="<?php echo $role; ?>"<?php echo $selected; ?>>
    					<?php echo ucwords($role); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>The user&rsquo;s role. See <a href="//anchorcms.com/docs/roles">here</a> for more info.</em>
			</p>
		</fieldset>
		
		<fieldset>
		
		    <legend>User details</legend>
		    <em>Create the details for your new user to log in to Anchor.</em>
		
		    <p>
		        <label for="username">Username:</label>
		        <input id="username" name="username" value="<?php echo Input::post('username', $user->username); ?>">
		        
		        <em>The desired username. Can be changed later.</em>
		    </p>

            <p>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password">
                
                <em>Leave blank for no change.</em>
            </p>
            
		    <p>
		        <label for="email">Email:</label>
		        <input id="email" name="email" value="<?php echo Input::post('email', $user->email); ?>">

                <em>The user&rsquo;s email address. Needed if the user forgets their password.</em>
		    </p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Update</button>
			<?php if(Users::authed()->id !== $user->id): ?>
			<button name="delete" type="submit">Delete</button>
			<?php endif; ?>
			
			<a href="<?php echo admin_url('users'); ?>">Return to users</a>
		</p>
	</form>

</section>
