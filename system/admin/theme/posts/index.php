
<h1>Posts</h1>

<p><a href="/admin/posts/add">Create a new post</a></p>

<section class="content">

	<?php echo notifications(); ?>
	
	<?php if(has_posts()): ?>
	<table>
		<thead>
			<tr>
				<th>Title</th>
				<th>Created</th>
				<th>Status</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"><?php echo pagination(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php while(posts()): ?>
			<tr>
				<td><a href="/admin/posts/edit/<?php echo post_id() ?>"><?php echo post_title(); ?></a></td>
				<td><?php echo post_date(); ?></td>
				<td><?php echo ucwords(post_status()); ?></td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No posts just yet</p>
	<?php endif; ?>
</section>

