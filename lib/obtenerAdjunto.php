<?php
require_once("../bootstrap.php");

if (! isset($_GET['file']) && empty($_GET['file'])) {
  acl_acceso();
}

$nombre_archivo = htmlentities($_GET['file']);

@ignore_user_abort();
@set_time_limit(0);
@ini_set("zlib.output_compression", "Off");
@session_write_close();

if(!$fdl=@fopen(INTRANET_DIRECTORY.'/varios/externos/'.$nombre_archivo,'rb')){
    die("Cannot Open File!");
} else {
  header("Cache-Control: ");// leave blank to avoid IE errors
  header("Pragma: ");// leave blank to avoid IE errors
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"".htmlentities($nombre_archivo)."\"");
  header("Content-length:".(string)(filesize(INTRANET_DIRECTORY.'/varios/externos/'.$nombre_archivo)));
  header ("Connection: close");
  sleep(1);
  fpassthru($fdl);
}
exit;
