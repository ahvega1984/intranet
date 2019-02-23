<?php
require_once("../bootstrap.php");

if (! isset($_GET['mod']) && empty($_GET['mod']) && ! isset($_GET['file']) && empty($_GET['file'])) {
  acl_acceso();
}

$modulo = htmlentities($_GET['mod']);
$nombre_archivo = htmlentities($_GET['file']);

switch ($modulo) {
  case 'convivencia': $directorio = INTRANET_DIRECTORY.'/admin/fechorias/adjuntos/'; break;
  case 'mensajes': $directorio = INTRANET_DIRECTORY.'/varios/externos/'; break;
}

@ignore_user_abort();
@set_time_limit(0);
@ini_set("zlib.output_compression", "Off");
@session_write_close();

if(!$fdl=@fopen($directorio.$nombre_archivo,'rb')){
    die("Cannot Open File!");
} else {
  header("Cache-Control: ");// leave blank to avoid IE errors
  header("Pragma: ");// leave blank to avoid IE errors
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"".htmlentities($nombre_archivo)."\"");
  header("Content-length:".(string)(filesize($directorio.$nombre_archivo)));
  header ("Connection: close");
  sleep(1);
  fpassthru($fdl);
}
exit;
