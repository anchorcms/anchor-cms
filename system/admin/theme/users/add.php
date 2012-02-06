<h1>Add a new user</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate autocomplete="off">
		<fieldset>
			<p>
    			<label for="real_name">Real name:</label>
    			<input id="real_name" name="real_name" value="<?php echo Input::post('name'); ?>">
    			
    			<em>The user&rsquo;s real name. Used in author bylines (visible to public).</em>
    		</p>
						
            <p>
                <label for="bio">Biography:</label>
                <textarea id="bio" name="bio"><?php echo Input::post('bio'); ?></textarea>
                
                <em>A short biography for your user. Accepts valid HTML.</em>
            </p>
			
			<p>
			    <label for="status">Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('inactive','active') as $status): ?>
    				<option value="<?php echo $status; ?>"<?php if(Input::post('status') == $status) echo 'selected'; ?>>
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
    				<option value="<?php echo $role; ?>"<?php if(Input::post('role') == $role) echo 'selected'; ?>>
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
		        <input id="username" name="username" value="<?php echo Input::post('username'); ?>">
		        
		        <em>The desired username. Can be changed later.</em>
		    </p>

            <p>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password">
                
                <em>And the matching password. Can be changed later.</em>
            </p>
            
		    <p>
		        <label for="email">Email:</label>
		        <input id="email" name="email" value="<?php echo Input::post('email'); ?>">
		        
		        <em>The user&rsquo;s email address. Needed if the user forgets their password.</em>
		    </p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Create</button>
			<a href="<?php echo base_url('admin/users'); ?>">Return to users</a>
		</p>
	</form>

</section>

