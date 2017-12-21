<?php
session_start();
include("../../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/logout.php');	
	exit();
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/logout.php");
	exit;
}

?>
<?php
include("../../../menu.php");
?>
<br />
<div align="center">
<div class="page-header" align="center">
  <h2>Administraci�n <small> Creaci�n de los Horarios (DEL)</small></h2>
</div>

<FORM ENCTYPE="multipart/form-data" ACTION="horarios.php" METHOD="post">
  <p class="help-block" style="width:500px; text-align:left"><span style="color:#9d261d">(*) </span>El archivo que se debe importar se obtiene de HORW exportando los datos en formato DEL como muestra la imagen de abajo. El Horario se extrae de Horw incluyendo todos los datos del mismo, y lo utilizan los m&oacute;dulos que presentan Horarios de Profesores y Grupos.</p>
  <br />
  <div class="well" style="width:500px; margin:auto;" align="left">
<div class="form-group">
  <label for="file">Selecciona el archivo con los datos del Horario
  </label>
  <input type="file" name="archivo" class="form-control" id="file">
  </div>
  <hr>
 
  <div align="center">
    <INPUT type="submit" name="enviar" value="Aceptar" class="btn btn-primary">
  </div>
  </div>
</FORM>
<br />

<hr />
<img border="0" src="exporta_horw.jpg" width="466" height="478">
<br />
<? include("../../../menu.php");?>
</body>
</html>


