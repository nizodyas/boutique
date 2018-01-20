 <?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
if(!is_logged_in()){
	login_error_redirect();
}

include 'includes/head.php';
include 'includes/navigation.php';

// delete product(only from site)
if(isset($_GET['delete'])){
	$delete_id = sanitize($_GET['delete']);
	$db->query("UPDATE products SET deleted = 1 WHERE id = '$delete_id'");
	header('Location: products.php');
}
$db_path = '';
if(isset($_GET['add']) || isset($_GET['edit'])){
	$brand_query = $db->query("SELECT * FROM brand ORDER BY brand");
	$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
	$title = ((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):'');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
	$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$price = ((isset($_POST['price']) && $_POST['price']!='')?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && $_POST['list_price']!='')?sanitize($_POST['list_price']):'');
	$description = ((isset($_POST['description']) && $_POST['description']!='')?sanitize($_POST['description']):'');
	$sizes = ((isset($_POST['sizes']) && $_POST['sizes']!='')?sanitize($_POST['sizes']):'');
	$sizes = rtrim($sizes,',');
	$saved_image = '';
	if(isset($_GET['edit'])){
		$edit_id = (int)$_GET['edit'];
		$product_result = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($product_result); 
		if(isset($_GET['delete_image'])){
			$image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
			unset($image_url);
			$db->query("UPDATE products SET image = '' WHERE id ='$edit_id' ");
			header('Location: products.php?edit='.$edit_id); 
		}
		$category = ((isset($_POST['child']) && $_POST['child']!='')?sanitize($_POST['child']):$product['categories']);
		$title = ((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):$product['title']);
	    $brand = ((isset($_POST['brand']) && $_POST['brand']!='')?sanitize($_POST['brand']):$product['brand']);
	    $parent_q = $db->query("SELECT * FROM categories WHERE id=$category");
	    $parent_result = mysqli_fetch_assoc($parent_q);
	    $parent = ((isset($_POST['parent']) && $_POST['parent']!='')?sanitize($_POST['parent']):$parent_result['parent']);
	    $price = ((isset($_POST['price']) && $_POST['price']!='')?sanitize($_POST['price']):$product['price']);
	    $list_price = ((isset($_POST['list_price']) && $_POST['price']!='')?sanitize($_POST['list_price']):$product['list_price']);
	    $description = ((isset($_POST['description']) && $_POST['description']!='')?sanitize($_POST['description']):$product['description']);
	    $sizes = ((isset($_POST['sizes']) && $_POST['sizes']!='')?sanitize($_POST['sizes']):$product['sizes']);

		$sizes = rtrim($sizes,',');
		$saved_image = (($product['image'] != '')?$product['image']:'');
		$db_path  = $saved_image;
	}
		if(!empty($sizes)){
			$sizeString = sanitize($sizes);
			$sizeString = rtrim($sizeString,',');
			$sizesArray = explode(',', $sizeString);
			$sArray=array();
			$qArray=array();
			foreach ($sizesArray as $ss) {
			 $s = explode(':', $ss);
			 $sArray[] = $s[0];
			 $qArray[] = $s[1];
			}


		} else { $sizesArray = array(); }
	
	if($_POST){
		
		$errors = array();
		if(!empty($_POST['size'])){
			$sizeString = sanitize($_POST['size']);
			$sizeString = rtrim($sizeString,',');
			$sizesArray = explode(',', $sizeString);
			$sArray=array();
			$qArray=array();
			foreach ($sizesArray as $ss) {
			 $s = explode(':', $ss);
			 $sArray[] = $s[0];
			 $qArray[] = $s[1];
			}


		} else { $sizesArray = array(); }
		$required = array('title','brand','price','parent','child','size');
		foreach ($required as $field) {
			if($_POST[$field]==''){
				$errors[] = 'all field with * is required';
				break;
			}
		}
		if(!empty($_FILES)){
			var_dump($_FILES);
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$nameArray = explode('.', $name);
			$file_name = $nameArray[0];
			$file_extension = $nameArray[1];
			$mime = explode('/', $photo['type']);
			$mime_type = $mime[0];
			$mime_extension = $mime[1];
			$tmp_location = $photo['tmp_name'];
			$file_size =$photo['size'];
			$allowed=array('jpg','jpeg','png','gif');
			$upload_name = md5(microtime()).'.'.$file_extension;
			$upload_path = BASEURL.'images/products'.$upload_name;
			$db_path = '/project/images/products'.$upload_name;
			if($mime_type != 'image'){
				$errors[]= 'file chosen must be an image!!';
			}

			if(!in_array($file_extension, $allowed)){
				$errors[]= 'image must be jpg,jpeg,png or gif!!';
			}

			if($file_size>15000000){
				$errors[]='too large file,must be under 15mb';
			}
			if($file_extension!=$mime_extension && ($mime_extension=='jpeg' && $file_extension!='jpg')){
				$errors[]='file extension does not match the file';
			}

			}
		if(!empty($errors)){
			echo display_errors($errors);
			
		}else{
			//upload file and update db
			if (!empty($_FILES) ){
				move_uploaded_file($tmp_location,$upload_path);
			}
			$upload_sql = "INSERT INTO products (title,price,list_price,brand,categories,sizes,image,description,featured,deleted) VALUES ('$title','$price','$list_price','$brand',$category,'$sizes','$db_path','$description',0,0)";
			if(isset($_GET['edit'])){
				$upload_sql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price',brand = '$brand', categories = '$category', sizes ='$sizes',image = '$db_path' , description = '$description' WHERE id = '$edit_id'";
			}
			$db->query($upload_sql);
			header('Location: products.php');

			

		}
	}

?>
<h2 class ="text-center"><?=((isset($_GET['edit']))?'edit':'add'); ?> a new product</h2><hr>
<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="POST" enctype="multipart/form-data">
	<div class="form-group col-md-3">
		<label for="title">Title*:</label>
		<input type="text" class="form-control" name="title" id="title" value="<?=$title;?>">
	</div>
	<div class="form-group col-md-3">
		<label for="brand">Brand*:</label>
		<select class="form-control" id="brand" name="brand">
			<option value=""<?=(($brand == '')?' selected':''); ?>></option>
			<?php while($b = mysqli_fetch_assoc($brand_query)): ?>
				<option value="<?=$b['id'];?>" <?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>

			<?php endwhile; ?>	
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="parent">Parent Category*:</label>
		<select class="form-control" id="parent" name="parent">
			<option value=""><?=(($parent=='')?' selected':''); ?></option>
			<?php while($p = mysqli_fetch_assoc($parent_query)): ?>
				<option value = "<?=$p['id']; ?>" <?=(($parent==$p['id'])?' selected':''); ?>><?=$p['category']; ?></option>


			<?php endwhile; ?>
		</select>
	</div>
	<div class= "form-group col-md-3">
		<label for="child">Child Category*</label>
		<select class="form-control" id="child" name="child">
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="price">Price*:</label>
		<input type="text" class="form-control" name="price" id="price" value="<?= $price;?>" >
	</div>
	<div class="form-group col-md-3">
		<label for="list_price">List Price:</label>
		<input type="text" class="form-control" name="list_price" id="list_price" value="<?= $list_price;?>" >
	</div>
	<div class="form-group col-md-3">
		<label for="">Quantity & sizes*:</label>
		<button class="btn btn-default form-control" onClick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & sizes</button>
		
	</div>
	<div class="form-group col-md-3">
		<label for="sizes">Sizes & Qty Preview</label>
		<input type="text" class="form-control"name="size" id="sizes" value="<?= $sizes; ?>" readonly >
	</div>
	<div class="form-group col-md-6">
		<?php if($saved_image != '') : ?>
			<div class = "saved-image"><img src ="<?= $saved_image;?>" alt = "saved image"/><br>
				<a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete image</a>
			</div>
		<?php else:?>
		<label for="photo">Product Photo:</label>
		<input type="file" name="photo" id="photo" class="form-control">
	   <?php endif; ?>
	</div>
	<div class="form-group col-md-6">
		<label for="description">Description</label>
		<textarea id="description" name="description" class="form-control" rows="6"><?= $description; ?></textarea>
	</div>
	<div class="form-group pull-right">
		<a href="products.php" class="btn btn-default">Cancel</a>
	<input type="submit" value="<?= ((isset($_GET['edit']))?'edit':'add'); ?> Product" class=" btn btn-success ">
	</div><div class="clearfix"></div>

	
</form>

 

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="sizesModalLabel">Sizes & Quantity</h4>
      </div>
      <div class="modal-body">
      	<div class="container-fluid">
      	<?php for($i= 1;$i<=12;$i++) : ?>
      		<div class="form-group col-md-4">
      			<label for="size<?=$i; ?>">Size:</label>
      			<input type="text" name="size<?=$i;?>" id="size<?=$i?>"" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
			</div>
			<div class="form-group col-md-2">
      			<label for="qty<?=$i; ?>">Quantity:</label>
      			<input type="number" name="qty<?=$i;?>" id="qty<?=$i?>"" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
			</div>

      	<?php endfor; ?> 
      </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onClick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php }
 else{


$sql = " SELECT * FROM products WHERE deleted = 0 ";
$prod_result = $db->query($sql);
if(isset($_GET['featured'])){
	$id = (int)$_GET['id'];
	$featured = (int)$_GET['featured'];
	$featured_sql = "UPDATE products SET featured = $featured WHERE id = '$id'"; 
	$db->query($featured_sql);
	header('Location: products.php');
}

 
 ?>

 <h2 class="text-center">Products</h2>
 <a href="products.php?add=1" class="btn btn-success pull-right" id = "add-product-btn">Add product</a><div class="clearfix"></div>
 <hr>
 <table class="table table-bordered table-condensed table-striped ">
 	<thead>
 		<th></th>
 		<th>product</th>
 		<th>price</th>
 		<th>category</th>
 		<th>featured</th>
		<th>sold</th>/
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
 					<a href="products.php?edit=<?=$product['id'];?>" class= "btn btn-xs btn-default"><span class = "glyphicon glyphicon-pencil"></span></a>
 					<a href="products.php?delete=<?=$product['id'];?>" class= "btn btn-xs btn-default"><span class = "glyphicon glyphicon-remove"></span></a>
 				</td>
 				<td><?=$product['title'];?></td>
 				<td><?=money($product['price']);?></td>
 				<td><?=$category;?></td>
 				<td><a href="products.php?featured=<?= (($product['featured'] == 0)?'1':'0');?> &id=<?=$product['id']; ?>" class = "btn btn-xs btn-default"><span class = "glyphicon glyphicon-<?=(($product['featured'] ==1)?'minus':'plus'); ?>"></span></a> &nbsp <?=(($product['featured'] == 1)?'featured product':'');?>
 				</td>
 				<td><?=$product['id'];?></td>
			</tr>
 		<?php endwhile; ?>
 	</tbody>
 </table>

 <?php }
include 'includes/footer.php';
 ?>
 <script>
 	jQuery('document') .ready(function(){
 		get_child_options(<?=$category?>);
 
 	});
 </script>