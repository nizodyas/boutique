<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
if(!is_logged_in()){
	login_error_redirect();
}

include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['restore'])){
	$restore_id = (int)$_GET['restore'];
	$sql = "UPDATE products SET deleted = 0 WHERE id='$restore_id'";
	$db->query($sql);
	header('Location: deleted_products.php');
}else{

$sql = " SELECT * FROM products WHERE deleted = 1 ";
$prod_result = $db->query($sql);
}

?>

<h2 class="text-center">Deleted Products</h2><hr>
 <table class="table table-bordered table-condensed table-striped">
 	 <thead>
 	 	<th></th>
 	 	<th>product</th>
 	 	<th>price</th>
 	 	<th>category</th>
 	 	<th>sold</th>
 	 </thead>
 	 <tbody>
 		<?php while($product = mysqli_fetch_assoc($prod_result)):
 			$child_id = $product['categories'];
 			$cat_sql = "SELECT * FROM categories WHERE id = $child_id";
 			$result=$db->query($cat_sql);
 			$child = mysqli_fetch_assoc($result);
 			$parent_id = $child['parent'];
 			$p_sql = "SELECT * FROM categories WHERE id = $parent_id";
 			$p_result = $db->query($p_sql);
 			$parent = mysqli_fetch_assoc($p_result);
 			$category = $parent['category'].'~'.$child['category'];
 			 ?>
 			<tr>
 				<td>
 					<a href="deleted_products.php?restore=<?=$product['id']?>" class= "btn btn-xs btn-default"><span class = "glyphicon glyphicon-refresh"></span></a>
 					
 				</td>
 				<td><?=$product['title'];?></td>
 				<td><?=money($product['price']);?></td>
 				<td><?=$category;?></td>
 				<td><?=$product['id'];?></td>
			</tr>
 		<?php endwhile; ?>
 	</tbody>
 </table>

 <?php

include 'includes/footer.php';
?>
