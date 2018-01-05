<?php include(login_process.php); ?> 
  
<div class="wrapper">
  
    <form class="form-signin">       
      <h2 class="form-signin-heading">Please login</h2>
      <input type="text" class="form-control" name="username" value="<?= $email ?>" placeholder="Email Address"  autofocus="" />
      <span class="error"><? =$email_error ?></span>
      <input type="password" class="form-control" name="password" value="<?= $password ?>" placeholder="Password" />    
      <span class="error"><? =$password_error ?></span>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
    </form>
  </div>



