
<h1>Users</h1>

<p><a href="/admin/users/add">Create a new user</a></p>

<section class="content">

	<?php echo notifications(); ?>

	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Role</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"><?php echo pagination(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach(users() as $user): ?>
			<tr>
				<td><a href="/admin/users/edit/<?php echo $user->id ?>"><?php echo $user->real_name; ?></a></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo ucwords($user->role); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</section>

