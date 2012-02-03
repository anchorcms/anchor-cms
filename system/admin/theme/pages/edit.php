
<h1>Edit Page</h1>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>">
		<fieldset>
			<legend>Details</legend>
			
			<?php echo notifications(); ?>
		
			<p><label>Name<br>
			<input name="name" type="text" value="<?php echo Input::post('name', page_name()); ?>"></label></p>
		
			<p><label>Title<br>
			<input name="title" type="text" value="<?php echo Input::post('title', page_title()); ?>"></label></p>
			
			<p><label>Slug<br>
			<input name="slug" type="text" value="<?php echo Input::post('slug', page_slug()); ?>"></label></p>
			
			<p><label>Content<br>
			<textarea name="content"><?php echo Input::post('content', page_content()); ?></textarea></label></p>

			<p><label>Status<br>
			<select name="status">
				<?php foreach(array('draft','published','archived') as $status): ?>
				<option value="<?php echo $status; ?>"<?php if(Input::post('status', page_status()) == $status) echo 'selected'; ?>>
					<?php echo ucwords($status); ?>
				</option>
				<?php endforeach; ?>
			</select></label></p>
		</fieldset>

		<p>
			<button name="save" type="submit">Save</button>
			<button name="delete" type="submit">Delete</button>
			<a href="/admin/pages">Return to pages</a>
		</p>
	</form>

</section>

