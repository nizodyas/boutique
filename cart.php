<?php
	require 'core/initialise.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/header_partial.php';

    if ($cart_id != ''){
        $cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $result = mysqli_fetch_assoc($cart_query);
        $items = json_decode($result['items'],true); 
        $i = 1;
        $sub_total = 0;
        $item_count  = 0;


    }

?>
<div class = "col-md-12">
	<div class="row">
		<h2 class = "text-center">My Shopping Cart</h2><hr>
		<?php if($cart_id == ''): ?>
			<div class = "bg-danger">
				<p class = "text-center text-danger">
					Your shopping cart is empty!!
				</p>
			</div>
        <?php else: ?>
        	<table class = "table table-bordered table-striped table-condensed">
        		<thead>
        			<th>#</th>
        			<th>item</th>
        			<th>price</th>
        			<th>quantity</th>
        			<th>size</th>
        			<th>sub-total</th>
        		</thead>
        		<tbody>
        			<?php
                        foreach ($items as $item) {
                          $product_id = $item['id'];
                          $product_query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'"); 
                          $product = mysqli_fetch_assoc($product_query);
                          $sArray = explode(',',$product['sizes']);
                          foreach ($sArray as $sizeString) {
                              $s = explode(':', $sizeString);
                              if($s[0] == $item['size']){
                                $available = $s[1];
                              }
                          }
                          ?>
                          <tr>
                              <td><?= $i;?></td>
                              <td><?= $product['title']; ?></td>
                              <td><?= money($product['price']); ?></td>
                              <td>
                                <button class = "btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>');">-</button>
                                <?= $item['quantity'];?>
                                <?php if($item['quantity'] < $available): ?>
                                   <button class = "btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>');">+</button></td>
                                <?php else: ?>
                                    <span class="text-danger">Max</span>
                                <?php endif; ?>
                              <td><?= $item['size'];?></td>
                              <td><?= money($item['quantity']*$product['price']);?></td>
                          </tr>

                          <?php $i++; 
                                $item_count += $item['quantity'];
                                $sub_total += ($product['price'] * $item['quantity']);

                      }

                            $tax = TAXRATE * $sub_total;
                            $tax = number_format($tax,2);
                            $grand_total = $tax + $sub_total;
                       ?>
                        
                
        		</tbody>
        	</table>
            <table class = " table table-bordered table-condensed text-right">
                <legend>totals</legend>
                <thead class="totals-table-header">
                    <th>Total items</th>
                    <th>sub total</th>
                    <th>tax</th>
                    <th>grand total
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $item_count;?></td>
                        <td><?= money($sub_total); ?></td>
                        <td><?= money($tax);?></td>
                        <td class = "bg-success"><?= money($grand_total);?></td>
                    </tr>
                </tbody>
            </table>

 <!-- checkout button -->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
 <span class = "glyphicon glyphicon-shopping-cart"></span> Check Out >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
      </div>
      <div class="modal-body">
        <div class = "row">
           <form action = "thank_you.php" method = "post" id = "payment-form">
            <span class="bg-danger" id = "payment_errors"></span>
                 <div id = "step1" style="display: block;">
                    <div class = "form-group col-md-6">
                      <label for ="full_name">Full Name :</label>
                      <input type="text" name="full_name"  id ="full_name" class="form-control">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="email">Email :</label>
                      <input type="email" name="email"  id ="email" class="form-control">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="street">Street Address :</label>
                      <input type="text" name="street"  id ="street" class="form-control" data-stripe="address_line1">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="street2">Street Address2 :</label>
                      <input type="text" name="street2"  id ="street2" class="form-control" data-stripe="address_line2">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="city">City :</label>
                      <input type="text" name="city"  id ="city" class="form-control" data-stripe="address_city">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="state">State :</label>
                      <input type="text" name="state"  id ="state" class="form-control" data-stripe="address_state">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="zip_code">Zip Code :</label>
                      <input type="text" name="zip_code"  id ="zip_code" class="form-control" data-stripe="address_zip">
                    </div>
                    <div class = "form-group col-md-6">
                      <label for ="country">Country :</label>
                      <input type="text" name="country"  id ="country" class="form-control" data-stipe="address_country">
                    </div>
                  </div>
                  <div id = "step2" style="display: none;">
                    <div class = "form-goup col-md-3">
                      <label for = "name">Name on a Card:</label>
                      <input type="text" class="form-control" id ="name" data-stripe="name">
                    </div>
                    <div class = "form-goup col-md-3">
                      <label for = "number">Card No:</label>
                      <input type="text" class="form-control" id ="number" data-stripe="number">
                    </div>
                    <div class = "form-goup col-md-2">
                      <label for = "cvc">CVC:</label>
                      <input type="text" class="form-control" id ="cvc" data-stripe="cvc">
                    </div>
                    <div class = "form-goup col-md-2">
                      <label for = "exp_month">Expire Month:</label>
                      <select class="form-control" id ="exp_month" data-stripe="exp_month">
                        <option value =""></option>
                        <?php for($i = 1 ; $i < 13 ; $i++): ?>
                          <option value = "<?=$i?>"><?=$i?></option>
                        <?php endfor; ?>
                      </select>
                    </div>
                    <div class = "form-goup col-md-2">
                      <label for = "exp_year">Expire Year:</label>
                      <select  class="form-control" id ="exp_year" data-stripe="exp_year">
                      <option value = ""></option>
                      <?php $yr = date("Y") ?>
                      <?php for($i = 0 ; $i < 11; $i++): ?>
                        <option value = "<?= $yr + $i;?>"><?= $yr + $i;?></option>
                      <?php endfor; ?>
                    </select>
                    </div>
                  </div>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_address();" id = "next_btn">Next >></button>
        <button type="button" class="btn btn-primary" onclick="back_address();" id = "back_btn" style="display: none;"><< Back </button>
          <button type="submit" class="btn btn-primary" id = "checkout_btn" style="display: none;">Check Out >></button>
      </div>
    </div>
  </div>
</div>

		<?php endif; ?>
	</div> 
</div>

<script>
  function back_address(){
     jQuery('#payment_errors').html('');
     jQuery('#step1').css("display","block");
     jQuery('#step2').css("display","none");
     jQuery('#next_btn').css("display","inline-block");
     jQuery('#back_btn').css("display","none");
     jQuery('#checkout_btn').css("display","none");
     jQuery('#checkoutModalLabel').html("Shipping Address");
  }
  function check_address(){
    var data = {
      'full_name' : jQuery('#full_name').val(),
      'email' : jQuery('#email').val(),
      'street' : jQuery('#street').val(),
      'street2' : jQuery('#street2').val(),
      'city' : jQuery('#city').val(),
      'state' : jQuery('#state').val(),
      'zip_code' : jQuery('#zip_code').val(),
      'country' : jQuery('#country').val(),
      };
      jQuery.ajax({
        url : '/project/admin/parsers/check_address.php',
        method : 'POST',
        data : data,
        success : function(data){
          if(data != 'passed'){
            jQuery('#payment_errors').html(data);

          }
          if(data =='passed'){
            jQuery('#payment_errors').html('');
            jQuery('#step1').css("display","none");
            jQuery('#step2').css("display","block");
            jQuery('#next_btn').css("display","none");
            jQuery('#back_btn').css("display","inline-block");
            jQuery('#checkout_btn').css("display","inline-block");
            jQuery('#checkoutModalLabel').html("enter ur card details");
          }
        },
        error : function(){alert("something went wrong!!");},  

      });

      }
  
  Stripe.setPublishableKey('<?=//STRIPE_PUBLIC;?>');

  function stripeResponseHandler(status, response){
    var $form = $('#payment-form');

    if(response.error){
      //show the errors in the form
      $form.find('#payment_errors').text(response.error.message);
      $form.find('button').prop('disabled',false);
    }else{
      //response contains id and card,which contains additional details
      var token = response.id;
      //insert the token to the form so that it gets sunmttedto the server
      $form.append($('<input type ="hidden" name="stripeToken"/>').val(token));
      //and submitt
      $form.get(0).submit();
    }
  };

  jQuery.(function($){
    $('payment-form').submit(function(event){
        var $form = $(this);

        //disable button to prevent repeated clicks
        $form.find('button').prop('disabled',true);

        Stripe.card.createToken($form, stripeResponseHandler); 

        //prevent the form from submitting the default action
        return false;
    });
  }); 



</script>

<?php 
   include 'includes/footer.php';
?>