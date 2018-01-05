
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
include 'includes/head.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');

$password = trim($password);
$errors = array();
?>
<style>
	body{
		background-image: url("/project/images/headerlogo/background.png");
		background-size: 100vw 100vh; 
		background-attachment: fixed; 
	}
</style>

<div id ="login-form">
	<div>
		
		<?php
          if($_POST){
          	//form validation
          	if(empty($_POST['email']) || empty($_POST['password'])){
          		$errors[] = "you must enter email and password.";
          	}

          	//validate email
          	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
          		$errors[] = "you must enter valid email address";
          	}

          	//password must be more than 6 characters
          	if(strlen($password)<6){
          		$errors[] = "password must be of more than 6 characters";

          	}
          	//check if email is in db
          	$result = $db->query("SELECT * FROM users WHERE email = '$email'");
          	$user = mysqli_fetch_assoc($result);
          	$user_count = mysqli_num_rows($result);
          	if($user_count<1){
          		$errors[] = "that email does not exists";
          	}

          	if(!password_verify($password, $user['password'])){
          		$errors[] = "password does not match";
          	}

          	//check for the errors
          	if(!empty($errors)){
          		echo display_errors($errors);
          	}else
          	{
          		//log the users in
          		$user_id = $user['id'];
          		login($user_id);


          	}
          }
		?>
	</div>
	<h2 class = "text-center">Login</h2><hr>
	<form action = login.php method="POST">
		<div class= "form-group">
			<label for="email">Email:</label>
			<input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">

		</div>
		<div class= "form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
		</div>
		<div class="form-group">
			<input type="submit" value="Login" class="btn btn-primary">
			
		</div>
	</form>
	<p class="text-right"><a href="/project/index.php" alt="home">Visit Site</a></p>
</div>




<?php

include 'includes/footer.php';
?>