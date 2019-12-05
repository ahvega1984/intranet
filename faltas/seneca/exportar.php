<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (isset($_GET['iniciofalta'])) {$iniciofalta = $_GET['iniciofalta'];}elseif (isset($_POST['iniciofalta'])) {$iniciofalta = $_POST['iniciofalta'];}
if (isset($_GET['finfalta'])) {$finfalta = $_GET['finfalta'];}elseif (isset($_POST['finfalta'])) {$finfalta = $_POST['finfalta'];}
if (isset($_GET['Submit'])) {$Submit = $_GET['Submit'];}elseif (isset($_POST['Submit'])) {$Submit = $_POST['Submit'];}

// Borramos faltas de alumnos que no están matriculados
mysqli_query($db_con,"delete from FALTAS where claveal not in (select distinct claveal from alma)");

$dir = "./origen/";
$fecha0 = explode("/",$_GET['iniciofalta']);
$fecha10 = explode("/",$_GET['finfalta']);

$fecha_inicio_sql = $fecha0[2].'-'.$fecha0[1].'-'.$fecha0[0];
$fecha_final_sql = $fecha10[2].'-'.$fecha10[1].'-'.$fecha10[0];

if (empty($iniciofalta) && empty($finfalta) || $fecha_inicio_sql > $fecha_final_sql) {
  header('Location:'.'index.php?msg_error=1');
  exit;
}

$dir0 = "./exportado/";
$ficheroseliminados = 0;
$handle0 = opendir($dir0);
while ($file0 = readdir($handle0)) {
  if (is_file($dir0.$file0) && strstr($file0,"xml") == TRUE) {
    if (unlink($dir0.$file0) ){
      $ficheroseliminados++;
    }
  }
}

if ($handle = opendir($dir)) {
  $ni = 0;

  while (FALSE !== ($file = readdir($handle))) {

    //header('Content-Type: text/xml');
    $doc = new DOMDocument('1.0', 'UTF-8');

    if (stristr($file, '.xml') == TRUE) {
      /*Cargo el XML*/
      $doc->load('./origen/'.$file);

      // Variables comunes
      $curso = explode("_",$file);
      $nivel = strtoupper(substr($curso[0],0,2));
      $grupo = strtoupper(substr($curso[0],2,1));

      $ni++;
      $x_ofert = $doc->getElementsByTagName( "X_OFERTAMATRIG" );
      $d_ofert = $doc->getElementsByTagName( "D_OFERTAMATRIG" );
      $x_unida = $doc->getElementsByTagName( "X_UNIDAD" );
      $t_nombr = $doc->getElementsByTagName( "T_NOMBRE" );
      $x_oferta = $x_ofert->item(0)->nodeValue;
      $d_oferta = $d_ofert->item(0)->nodeValue;
      $x_unidad = $x_unida->item(0)->nodeValue;
      $t_nombre = $t_nombr->item(0)->nodeValue;
      $n_curso=$d_oferta;
      $n_curso1 = $n_curso;
      $hoy = date('d/m/Y')." 08:00:00";
      $ano_curso=substr($config['curso_inicio'],0,4);
      $xml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>

      <SERVICIO>
      \t<DATOS_GENERALES>
      \t\t<MODULO>FALTAS DE ASISTENCIA</MODULO>
      \t\t<TIPO_INTERCAMBIO>I</TIPO_INTERCAMBIO>
      \t\t<AUTOR>SENECA</AUTOR>
      \t\t<FECHA>$hoy</FECHA>
      \t\t<C_ANNO>$ano_curso</C_ANNO>
      \t\t<FECHA_DESDE>$iniciofalta</FECHA_DESDE>
      \t\t<FECHA_HASTA>$finfalta</FECHA_HASTA>
      \t\t<CODIGO_CENTRO>".$config['centro_codigo']."</CODIGO_CENTRO>
      \t\t<NOMBRE_CENTRO>".$config['centro_denominacion']."</NOMBRE_CENTRO>
      \t\t<LOCALIDAD_CENTRO>".$config['centro_localidad']."</LOCALIDAD_CENTRO>
      \t</DATOS_GENERALES>
      \t<CURSOS>
      \t\t<CURSO>
      \t\t\t<X_OFERTAMATRIG>$x_oferta</X_OFERTAMATRIG>
      \t\t\t<D_OFERTAMATRIG>$n_curso</D_OFERTAMATRIG>
      \t\t\t<UNIDADES>
      \t\t\t\t<UNIDAD>
      \t\t\t\t\t<X_UNIDAD>$x_unidad</X_UNIDAD>
      \t\t\t\t\t<T_NOMBRE>$t_nombre</T_NOMBRE>
      \t\t\t\t\t<ALUMNOS>";
      $alumn = $doc->getElementsByTagName( "ALUMNO" );
      foreach ($alumn as $alumno){
      	$x_matricul = $alumno->getElementsByTagName( "X_MATRICULA" );
      	$x_matricula = $x_matricul->item(0)->nodeValue;
      	$clavea = $alumno->getElementsByTagName( "C_NUMESCOLAR" );
      	$claveal = $clavea->item(0)->nodeValue;

      	$xml.="
        \t\t\t\t\t\t<ALUMNO>
        \t\t\t\t\t\t\t<X_MATRICULA>$x_matricula</X_MATRICULA>
        \t\t\t\t\t\t\t<FALTAS_ASISTENCIA>";

        include 'exportado.php';

        $xml.="
        \t\t\t\t\t\t\t</FALTAS_ASISTENCIA>
        \t\t\t\t\t\t</ALUMNO>";
      }
      $xml.="
      \t\t\t\t\t</ALUMNOS>
      \t\t\t\t</UNIDAD>
      \t\t\t</UNIDADES>
      \t\t</CURSO>
      \t</CURSOS>
      </SERVICIO>";
      if (file_exists("exportado/".$file)) unlink("exportado/".$file);
      $fp1=fopen("exportado/".$file,"w");
      fwrite($fp1,$xml);
      //unlink('./origen/'.$file);
    }
  }
}

if ($ni > 0) {
  $zip = new ZipArchive();

  $filename = $dir0.'ImportacionFaltasAlumnado.zip';
  if ($zip->open($filename,ZIPARCHIVE::CREATE) === true) {

    if ($handle = opendir($dir0)) {
      while (FALSE !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..' && $file != 'index.php' && $file != 'ImportacionFaltasAlumnado.zip') {
          $zip->addFile($dir0.'/'.$file);
        }
      }
    }
    $zip->close();

    $handle0 = opendir($dir0);
    while ($file0 = readdir($handle0)) {
      if (is_file($dir0.$file0) && strstr($file0,"xml") == TRUE) {
        unlink($dir0.$file0);
      }
    }

    if (is_file($filename)) {
      $size = filesize($filename);
      if (function_exists('mime_content_type')) {
        $type = mime_content_type($filename);
      } else if (function_exists('finfo_file')) {
        $info = finfo_open(FILEINFO_MIME);
        $type = finfo_file($filename);
        finfo_close($info);
      }
      if ($type == '') {
        $type = "application/force-download";
      }
      // Set Headers
      header("Content-Type: $type");
      header("Content-Disposition: attachment; filename=ImportacionFaltasAlumnado.zip");
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: " . $size);
      // Download File
      readfile($filename);
    }
    unlink($filename);
  }
  else {
    header('Location:'.'index.php?msg_error=3');
    exit;
  }
}
else {
  header('Location:'.'index.php?msg_error=2');
  exit;
}
