<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/project/core/initialise.php';
unset($_SESSION['SBuser']);
header('Location: login.php');
 ?>