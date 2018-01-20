<?php
    require_once'../core/initialise.php';
    $id = $_POST['id'];
    $id = (int)$id;
    $sql = "SELECT * FROM products WHERE id = '$id'";
    $result = $db->query($sql);
    $product = mysqli_fetch_assoc($result);
    $brand_id = $product['brand'];
    $sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
    $brand_query = $db->query($sql);
    $brand = mysqli_fetch_assoc($brand_query);

    $size_string = $product['sizes'];
    $size_array = explode(',', $size_string);

?>
      
<?php ob_start(); ?>
     
    <div class="modal fade details-1" id="details_modal" tabindex="-1" role="dialog" aria-labelledby="details_modal"  aria-hidden="true"> 
       <div class="modal-dialog modal-lg">
       	  <div class="modal-content">
       	      <div class="modal-header">
                <h4 class="modal-tile text-center"><?= $product['title']; ?></h4>
                <button class="close " type="button" onclick = "close_modal() " aria-label="Close" >
                <span aria-hidden="true">&times; </span>
                </button>
       	      </div>
       	 
         <div class="modal-body">
       	 	  <div class="container-fluid">
       	 		     <div class="row">
                  <span id ="modal_errors" class="bg-danger"></span>
       	 			      <div class="col-sm-6">
       	 				       <div class="center-block">
       	 					        <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="details img-responsive">
       	 				        </div>
       	 			          </div>
       	 			      <div class="col-sm-6">
       	 				       <h4>Details</h4>
       	 				       <p><?= $product['description']; ?></p>
       	 				       <hr>
       	 				       <p>price:Rs. <?= $product['price']; ?></p>
       	 				       <p>brand: <?= $brand['brand']; ?></p>
                
                      <form action="add_cart.php" method="post" id ="add_product_form">
                        <input type="hidden" name="product_id" value ="<?=$id;?>">
                        <input type="hidden" name="available" id ="available" value =''>
                          <div class="form-group">
                            <div class="col-xs-3">
                             <label for="quantity">Quantity</label>
                              <input type="number" class="form-control" name="quantity" id="quantity"></div><br><div class ="col-xs-9">&nbsp;
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="size">Size:</label>
                            <select name="size" id="size" class="form-control">
                              <option value=""></option>
                              <?php foreach($size_array as $string)
                                {
                                 $string_array = explode(':', $string);
                                 $size = $string_array[0];
                                 $available = $string_array[1];
                                 echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.'('.$available .' Available)</option>';
                                } 
                              ?>
                            </select>
                          </div>
                      </form>
       	 			      </div>
       	 		       </div>
       	         	</div>
       	       </div>
       	          <div class="modal-footer">
       	 	          <button class="btn btn-default" onclick = "close_modal() ">Close</button>
       	 	          <button class=" btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to cart</button>
       	          </div>
          </div>
    </div>
  </div>

     <script>
      jQuery('#size').change(function(){
         var available = jQuery('#size option:selected').data("available");
         jQuery('#available').val(available);
      });
       function close_modal(){
        jQuery('#details_modal').modal('hide');
        setTimeout(function(){
          jQuery('#details_modal').remove();
          jQuery('.modal-backdrop').remove();
         } ,500);
       }
     </script>

    <?php echo ob_get_clean(); ?>