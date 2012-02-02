
<h1>Posts</h1>

<p><a href="/admin/posts/add">Create a new post</a></p>

<section class="content">

	<?php echo notifications(); ?>
	
	<?php if(count(posts())): ?>
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
			<?php foreach(posts() as $post): ?>
			<tr>
				<td><a href="/admin/posts/edit/<?php echo $post->id ?>"><?php echo $post->title; ?></a></td>
				<td><?php echo date(Config::get('metadata.date_format'), strtotime($post->created)); ?></td>
				<td><?php echo ucwords($post->status); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No posts just yet</p>
	<?php endif; ?>
</section>

