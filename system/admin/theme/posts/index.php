
<h1>Posts</h1>

<p><a href="/admin/posts/add">Create a new post</a></p>

<section class="content">

	<?php echo notifications(); ?>
	
	<?php if(count(posts())): ?>
	<table>
		<thead>
			<tr>
				<th>Title</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>Pagination</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach(posts() as $post): ?>
			<tr>
				<td><a href="/admin/posts/edit/<?php echo $post->id ?>"><?php echo $post->title; ?></a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No posts just yet</p>
	<?php endif; ?>
</section>

