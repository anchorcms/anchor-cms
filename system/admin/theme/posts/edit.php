
<h1>Edit Post</h1>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>">
		<fieldset>
			<legend>Details</legend>
			
			<?php echo notifications(); ?>
		
			<p><label>Title<br>
			<input name="title" type="text" value="<?php echo Input::post('title', article_title()); ?>"></label></p>
			
			<p><label>Slug<br>
			<input name="slug" type="text" value="<?php echo Input::post('slug', article_slug()); ?>"></label></p>
			
			<p><label>Description<br>
			<textarea name="description"><?php echo Input::post('description', article_description()); ?></textarea></label></p>
			
			<p><label>Content<br>
			<textarea name="html"><?php echo Input::post('html', article_html()); ?></textarea></label></p>

			<p><label>Status<br>
			<select name="status">
				<?php foreach(array('draft','published','archived') as $status): ?>
				<option value="<?php echo $status; ?>"<?php if(Input::post('status', article_status()) == $status) echo 'selected'; ?>>
					<?php echo ucwords($status); ?>
				</option>
				<?php endforeach; ?>
			</select></label></p>
		</fieldset>
		
		<fieldset>
			<legend>Customise</legend>

			<p><label>Css<br>
			<textarea name="css"><?php echo Input::post('css', article_css()); ?></textarea></label></p>
			
			<p><label>Js<br>
			<textarea name="js"><?php echo Input::post('js', article_js()); ?></textarea></label></p>
		</fieldset>
		
		<p>
			<button name="save" type="submit">Save</button>
			<button name="delete" type="submit">Delete</button>
			<a href="/admin/posts">Return to posts</a>
		</p>
	</form>

</section>

