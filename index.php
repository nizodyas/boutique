<?php 
require_once 'core/initialise.php';
include 'includes/head.php'; 
include 'includes/navigation.php';
include 'includes/header_full.php';
include 'includes/left_bar.php';

$sql = "SELECT * FROM products WHERE featured = 1 AND deleted = 0";

$featured = $db->query($sql); 

?>
    <!--main-->
        <div class="col-md-8">
            <h2 class="text-center">Feature products</h2><br>

    		<div class="row">

                <?php while ($product = mysqli_fetch_assoc($featured)) : ?>


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



    