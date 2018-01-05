<?php
function display_errors($errors){
	$display = '<ul class="bg-danger">';
	foreach ($errors as $error){
		$display .= '<li class= "text-danger">'.$error.'</li>';
	}
 $display .= '</ul>';
 return $display;

}

function sanitize($dirty){
	return htmlentities($dirty,ENT_QUOTES,"UTF-8");

}

function money($number){
    return 'Rs.'.number_format($number,2);
}
function login($user_id){
	$_SESSION['SBuser'] = $user_id;
	global $db;
	$date = date("Y-m-d H:i:s");
	$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
	$_SESSION['success_flash'] = "you are now logged in";
	header('Location: index.php');
}

function is_logged_in(){
	if(isset($_SESSION['SBuser']) && $_SESSION['SBuser'] > 0){
		return true;
	}
	else {
		return false;
	}
}

function login_error_redirect($url = 'login.php'){
   $_SESSION['error_flash'] = "you must login!!";
   header('Location: '.$url);
}


function permission_error_redirect($url = 'login.php'){
	$_SESSION['error_flash'] = "you do not have permission to acces this page";
	header('Location: '.$url);
}

function has_permission($permission = 'admin'){
	global $user_data;
	$permissions = explode(',', $user_data['permissions']);
	//chesck if admin is in db(permissions)
	if(in_array($permission, $permissions,true)){
		return true;
	}
	return false;

}

function pretty_date($date){
	return date("M d,Y h:i A",strtotime($date));
}
























