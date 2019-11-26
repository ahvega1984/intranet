<?php 
require('bootstrap.php');

session_unset();
session_destroy();

if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org") {
	header("Location://".$config['dominio']."/intranet/loginSeneca.php");
	exit();
}
else {
	header("Location://".$config['dominio']."/intranet/login.php");
exit();
}