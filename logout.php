<?php 
require('bootstrap.php');

$_SESSION = array(); 
session_destroy();
header("Location://".$config['dominio']."/intranet/login.php");
?> 
