<?php 
require_once 'core/initialise.php';
include 'includes/head.php'; 
include 'includes/navigation.php';
include 'includes/header_partial.php';
include 'includes/left_bar.php';

if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
}
else{
    $cat_id = '';
}
$sql = "SELECT * FROM products WHERE categories = '$cat_id'";
$product_query = $db->query($sql); 
$category = get_category($cat_id);

?>
    <!--main-->
        <div class="col-md-8">
            <h2 class="text-center"><?=$category['parent'].' '.$category['child']; ?></h2><br>

    		<div class="row">

                <?php while ($product = mysqli_fetch_assoc($product_query)) : ?>


    		  <div class="col-md-3 text-center">
    			<h4><?= $product['title']; ?></h4>
    			<img src="<?= $product['image']; ?>"" alt=<?= $product['title']; ?> class="img-thumb"/>
    			<p class="list-price text-danger">Least price: <s><?= $product['list_price']; ?></s></p>
    			<p class="price">Our price: <?= $product['price']; ?></p>
    			<button type="button" class="btn btn-sn btn-success"  onclick = "details_modal(<?= $product['id']; ?>)">Details
    			</button>
    		  </div>
    	
               <?php endwhile; ?>
    	
    	   </div>

        </div>
    <?php 
      
       include 'includes/right_bar.php';
       include 'includes/footer.php'
    ?>



    