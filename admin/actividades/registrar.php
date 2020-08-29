<?php
require('../../bootstrap.php');

if (file_exists("config.php")) {
  include("config.php");
}

include("../../menu.php");
include("menu.php");
  ?>
<div class="container">
<div class="row">
<div class="page-header">
  <h2>Actividades Complementarias y Extraescolares <small> Registro de alumnos</small></h2>
</div>
</div>

<?php
$tutor = $_SESSION['profi'];

if (isset($_GET['id'])) {$id = limpiarInput($_GET['id'], 'numeric');}elseif (isset($_POST['id'])) {$id = limpiarInput($_POST['id'], 'numeric');}else{$id="";}
if (isset($_GET['eliminar'])) {$id = limpiarInput($_GET['eliminar'], 'numeric');}elseif (isset($_POST['eliminar'])) {$eliminar = limpiarInput($_POST['eliminar'], 'numeric');}else{$eliminar="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['crear'])) {$crear = $_GET['crear'];}elseif (isset($_POST['crear'])) {$crear = $_POST['crear'];}else{$crear="";}
if (isset($_GET['buscar'])) {$buscar = limpiarInput($_GET['buscar'], 'alphanumericspecial');}elseif (isset($_POST['buscar'])) {$buscar = limpiarInput($_POST['buscar'], 'alphanumericspecial');}else{$buscar="";}

if (isset($_GET['calendario'])) {$calendario = limpiarInput($_GET['calendario'], 'alphanumericspecial');}elseif (isset($_POST['calendario'])) {$calendario = limpiarInput($_POST['calendario'], 'alphanumericspecial');}else{$calendario="";}
if (isset($_GET['act_calendario'])) {$act_calendario = limpiarInput($_GET['act_calendario'], 'alphanumericspecial');}elseif (isset($_POST['act_calendario'])) {$act_calendario = limpiarInput($_POST['act_calendario'], 'alphanumericspecial');}else{$act_calendario="";}
if (isset($_GET['confirmado'])) {$confirmado = limpiarInput($_GET['confirmado'], 'alphanumericspecial');}elseif (isset($_POST['confirmado'])) {$confirmado = limpiarInput($_POST['confirmado'], 'alphanumericspecial');}else{$confirmado="";}
if (isset($_GET['detalles'])) {$detalles = limpiarInput($_GET['detalles'], 'alphanumericspecial');}elseif (isset($_POST['detalles'])) {$detalles = limpiarInput($_POST['detalles'], 'alphanumericspecial');}else{$detalles="";}
if (isset($_GET['fecha'])) {$fecha = limpiarInput($_GET['fecha'], 'alphanumericspecial');}elseif (isset($_POST['fecha'])) {$fecha = limpiarInput($_POST['fecha'], 'alphanumericspecial');}else{$fecha="";}
if (isset($_GET['horario'])) {$horario = limpiarInput($_GET['horario'], 'alphanumericspecial');}elseif (isset($_POST['horario'])) {$horario = limpiarInput($_POST['horario'], 'alphanumericspecial');}else{$horario="";}
if (isset($_GET['profesor'])) {$profesor = limpiarInput($_GET['profesor'], 'alphanumericspecial');}elseif (isset($_POST['profesor'])) {$profesor = limpiarInput($_POST['profesor'], 'alphanumericspecial');}else{$profesor="";}
if (isset($_GET['actividad'])) {$actividad = limpiarInput($_GET['actividad'], 'alphanumericspecial');}elseif (isset($_POST['actividad'])) {$actividad = limpiarInput($_POST['actividad'], 'alphanumericspecial');}else{$actividad="";}
if (isset($_GET['descripcion'])) {$descripcion = limpiarInput($_GET['descripcion'], 'alphanumericspecial');}elseif (isset($_POST['descripcion'])) {$descripcion = limpiarInput($_POST['descripcion'], 'alphanumericspecial');}else{$descripcion="";}

// PDF
$fecha2 = date('Y-m-d');
$hoy = formatea_fecha($fecha);


  $fecha1 = explode("-",$fecha);
  $dia = $fecha[0];
  $mes = $fecha[1];
  $ano = $fecha[2];

 // Borramos registros anteriores
 mysqli_query($db_con,"delete from actividadalumno where cod_actividad='$id'");

  foreach($_POST as $key => $value)
  {
//  echo "$key --> $value<br>";
if(is_numeric(trim($key))){
mysqli_query($db_con, "insert into actividadalumno (claveal,cod_actividad) values ('".$value."','".$id."')");
}
}
echo '
<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			Los alumnos seleecionados han sido regsitrados en la Actividad Extraescolar o Complementaria.
			</div></div>';
?>
