<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
if(!is_logged_in()){
	login_error_redirect();
}

include 'includes/head.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $db->query($sql);
$errors = array();
$category ='';
$post_parent='';

//edit category
if(isset($_GET['edit']) && !empty($_GET['edit'])){
	$edit_id = (int)$_GET['edit'];
	$edit_id = sanitize($edit_id);
	$sql_edit = "SELECT * FROM categories WHERE id = '$edit_id'";
	$edit_result = $db->query($sql_edit);
	$category_edit = mysqli_fetch_assoc($edit_result);

}

//delete category

if(isset($_GET['delete']) && !empty($_GET['delete'])){
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$sql = "SELECT * FROM categories WHERE id = '$delete_id'";
	$result = $db->query($sql);
	$category = mysqli_fetch_assoc($result);
	if($category['parent'] == 0){
		$sql = " DELETE FROM categories WHERE parent = '$delete_id'";
		$db->query($sql);
	}
	$sql_del = " DELETE FROM categories WHERE id = '$delete_id'";
	$db->query($sql_del);
	header('Location: categories.php');

}

//process form

if(isset($_POST) && !empty($_POST)){
	$post_parent = sanitize($_POST['parent']);
	$category = sanitize($_POST['category']);
	$sql_form = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
	if(isset($_GET['edit'])){
		$id = $category_edit['id'];
		$sql_form = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id!='$id'";
		}

	$form_result = $db->query($sql_form);
	$count = mysqli_num_rows($form_result);
	//if category is blank
	if($category == ''){
		$errors[] .= 'The category cannot be left blank';

	}
	//if exists in db
	if($count > 0 ){
		$errors[] .= $category. ' already exists!';
	}

    //display errors or update db
	if(!empty($errors)){
		//display errors
		$display = display_errors($errors); ?>
		<script>
			jQuery(document).ready(function(){
				jQuery('#errors').html('<?= $display ?>');
			});
		</script>
<?php }else{ 
       
       //update
	  $sql_update = "INSERT INTO categories(category, parent) VALUES ('$category','$post_parent')";
	  if(isset($_GET['edit'])){
	  	$sql_update = "UPDATE categories SET category = '$category', parent ='$post_parent' WHERE id = '$edit_id'";
	  }
	  $db->query($sql_update);
	  header('Location: categories.php');
	}

}   
	$category_value ='';
	$parent_value = 0;
	if(isset($_GET['edit'])) {
		$category_value = $category_edit['category'];
		$parent_value = $category_edit['parent'];
	}else{
		if(isset($_POST)){
			$category_value = $category;
			$parent_value = $post_parent;

		}
	}
?>

 
 <h2 class= "text-center">Categories</h2><hr>
 <div class = "row">
 	<div class = "col-md-6">
 		<!--form-->
 		<form class = "form" action = "categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="POST">
 			<legend><?=((isset($_GET['edit']))?'edit':'Add a'); ?> category</legend>
 			<div id = "errors"></div>
 			<div class="form-group">                
 				<label for="parent">Parent</label>
 				<select class ="form-control"  name="parent" id = "parent">
 					<option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parent</option>
 					<?php while($parent = mysqli_fetch_assoc($result)):  ?>
 						<option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id'])?' selected="selected"':'');?>><?= $parent['category'];?></option>
 					<?php endwhile; ?>
 				</select>
 			</div>
 			<div class = "form-group">
 				<label for = "category">Category</label>
 				<input type="text" name="category" class="form-control" id="category" value = "<?= $category_value; ?>">
 				
 			</div>
 			<div class="form-group">
 				<input type="submit" class="btn btn-success" value="<?=((isset($_GET['edit']))?'edit':'Add a'); ?> category">
 			</div>
 			
 		</form>
 		
 	</div>
 	<!--category table-->
 	<div class = "col-md-6">
 		<table class = "table table-bordered">
 			<thead>
 				<th>Category</th>
 				<th>Parent</th>
 				<th></th>
 			</thead>
 			<tbody>
 				<?php 
 					$sql = "SELECT * FROM categories WHERE parent = 0";
					$result = $db->query($sql);
				
	

 				  while($parent = mysqli_fetch_assoc($result)): 
 					$parent_id = (int)$parent['id'];
 					$sql2 = "SELECT * FROM categories WHERE  parent=$parent_id";
 					$child_result = $db->query($sql2);
 				?>
 					
 				
	 				<tr class="bg-primary">
	 					<td><?= $parent['category']; ?></td>
	 					<td>parent</td>
	 					<td>
	 						<a href="categories.php?edit=<?= $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
	 						<a href="categories.php?delete=<?= $parent['id']; ?>"  class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
	 					</td>
	 				</tr>
	 			  <?php while($child = mysqli_fetch_assoc($child_result)): ?>
	 			  	<tr class="bg-info">
	 					<td><?= $child['category']; ?></td>
	 					<td><?= $parent['category']; ?></td>
	 					<td>
	 						<a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
	 						<a href="categories.php?delete=<?= $child['id']; ?>"  class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
	 					</td>
	 				</tr>

	 			  <?php endwhile; ?>
				<?php endwhile; ?>
 			</tbody> 
 		</table>
 	</div>
 </div>

 <?php
 include 'includes/footer.php';
 ?>