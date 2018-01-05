<?php
require_once '..\core\initialise.php';
if(!is_logged_in()){
	header('Location: login.php');
}


include 'includes\head.php';
include 'includes\navigation.php';


?>

Users Home
<?php
include 'includes\footer.php'
?>