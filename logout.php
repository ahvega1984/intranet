<?php 
require('bootstrap.php');

session_unset();
session_destroy();

header("Location://".$config['dominio']."/intranet/login.php");
exit();
