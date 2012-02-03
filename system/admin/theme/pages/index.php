
<h1>Pages</h1>

<p><a href="/admin/pages/add">Create a new page</a></p>

<section class="content">

	<?php echo notifications(); ?>
	
	<?php if(count(pages())): ?>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Title</th>
				<th>Status</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"><?php echo pagination(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach(pages() as $page): ?>
			<tr>
				<td><a href="/admin/pages/edit/<?php echo $page->id ?>"><?php echo $page->name; ?></a></td>
				<td><?php echo $page->title; ?></td>
				<td><?php echo ucwords($page->status); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No pages just yet</p>
	<?php endif; ?>
</section>

