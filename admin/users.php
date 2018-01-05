<?php
require_once '..\core\initialise.php';
if(!is_logged_in()){
	login_error_redirect();
}

if(!has_permission()){
	permission_error_redirect('brand.php');
}
include 'includes\head.php';
include 'includes\navigation.php';
$user_query = $db->query("SELECT * FROM users ORDER BY full_name");

?>
<h2 class ="text-center">Users</h2><hr>
<table class ="table table-bordered table-striped table-condensed">
	<thead>
		<th></th><th>Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permissions</th>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($user_query)): ?>
		<tr>
			<td></td>
			<td><?= $user['full_name'];?></td>
			<td><?= $user['email'];?></td>
			<td><?= pretty_date($user['join_date']);?></td>
			<td><?= pretty_date($user['last_login']);?></td>
			<td><?= $user['permissions'];?></td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>
<?php
include 'includes\footer.php'
?>