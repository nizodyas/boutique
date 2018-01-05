
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
if(!is_logged_in()){
     lgin_error_redirect();
}
$hashed = $user_data['password'];
include 'includes/head.php';
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password,PASSWORD_DEFAULT); 
$user_id =$user_data['id'];
$errors = array();
?>


<div id ="login-form">
	<div>
		
		<?php
          if($_POST){
          	//form validation
          	if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
          		$errors[] = "you must fill out all fields.";
          	}


          	//password must be more than 6 characters
          	if(strlen($password)<6){
          		$errors[] = "password must be of more than 6 characters";

          	}
               // check if new password matches confirm
               if($password != $confirm){
                    $errors[] = "passwords does not match";
               }
          
          	if(!password_verify($old_password, $hashed)){
          		$errors[] = "old password does not match";
          	}

          	//check for the errors
          	if(!empty($errors)){
          		echo display_errors($errors);
          	}else
          	{
          		//change password
          		$db->query("UPDATE users SET password = '$new_hashed' WHERE id ='$user_id'");
                    $_SESSION['success_flash'] = 'you have sucessfully changed ur password';

                    header('Location: index.php');


          	}
          }
		?>
	</div>
	<h2 class = "text-center">Change Password</h2><hr>
	<form action = change_password.php method="POST">
		<div class= "form-group">
			<label for="old_password">Old Password:</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">

		</div>
		<div class= "form-group">
			<label for="password">New Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>
          <div class= "form-group">
               <label for="confirm">confirm new password:</label>
               <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
          </div>
		<div class="form-group">
               <a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="Login" class="btn btn-primary">
			
		</div>
	</form>
	<p class="text-right"><a href="/project/index.php" alt="home">Visit Site</a></p>
</div>




<?php

include 'includes/footer.php';
?>