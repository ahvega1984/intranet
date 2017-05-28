<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($config['informe_tutoria']['activa_administrativo']) && $config['informe_tutoria']['activa_administrativo'] == 1) {
	acl_acceso($_SESSION['cargo'], array(1, 2, 7));
}
else {
	acl_acceso($_SESSION['cargo'], array(1, 2));
}


include("../../menu.php");
include("menu.php");
?>
<div class="container">
<div class="row">
<div class="page-header">
<h2>Informes de Tutoría <small> Activar Informe</small></h2>
</div>
<br>
 <?php
if(empty($_POST['alumno']) or empty($_POST['tutor']))
{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>Atención:</legend>
Debes rellenar todos los datos, y parece que te has olvidado del Alumno o del Tutor.<br>Vuelve atrás e inténtalo de nuevo.<br /><br />
<input name="volver" type="button" onClick="history.go(-1)" value="Volver" class="btn btn-primary">
</div></div><hr>';
exit;
}
#Vamos a rellenar los datos del alumno objeto del informe en la base de datos infotut

// 
$fecha = cambia_fecha($fecha);
if ($_POST['alumno']=="Informe de Grupo") {
	$claveal = rand(1,100000);
	$apellidos = "Informe general del Grupo";
	$nombre = "Grupo ".$_POST['grupo'].". ";
	$nombre_comp = "Informe general del Grupo";
	$unidad = $_POST['grupo'];
}
else{
	$trozos = explode (" --> ", $alumno);
	$claveal = $trozos[1];
	$nombre_comp = $trozos[0];
	$trozos1 = explode (", ", $nombre_comp);
	$apellidos = $trozos1[0];
	$nombre = $trozos1[1];
	$falumno=mysqli_query($db_con, "SELECT CLAVEAL, APELLIDOS, NOMBRE, unidad, matriculas, COMBASI FROM alma WHERE claveal ='$claveal'");
	$dalumno = mysqli_fetch_array($falumno);
	$asignaturas=chunk_split($dalumno[5],3,"-");
	$asig=explode("-",$asignaturas);
	$unidad = $dalumno[3];
}

$hoy = date('Y\-m\-d');
if (stristr($apellidos, "Informe general")==TRUE) {
	$duplicado = mysqli_query($db_con, "select claveal from infotut_alumno where (apellidos like 'Informe general%' and unidad = '$unidad') and f_entrev = '$fecha'");
}
else{
	$duplicado = mysqli_query($db_con, "select claveal from infotut_alumno where claveal = '$claveal' and f_entrev = '$fecha'");
}

if(mysqli_num_rows($duplicado)>0)
{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>Atención:</legend>';
			echo "Ya hay un <b>Informe Tutorial</b> activado para <b> $nombre $apellidos </b>para el día
<b>";
echo formatea_fecha($fecha);
echo "</b>, y no queremos duplicarlo, verdad?";
echo '<br /><br /><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-primary">
		</div></div>';
exit;
}

$insertar=mysqli_query($db_con, "INSERT infotut_alumno (CLAVEAL,APELLIDOS,NOMBRE,unidad,F_ENTREV,TUTOR,FECHA_REGISTRO,motivo)
VALUES ('$claveal',\"$apellidos\",'$nombre','$unidad',
'$fecha','".$_POST['tutor']."', '$hoy', '$motivo')") or die ("Error en la activación del informe: " . mysqli_error($db_con));

if (stristr($apellidos, "Informe general")==TRUE) {
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo "El <b>Informe general sobre el Grupo </b> $unidad para el día <b>";
}
else{
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo "El <b>Informe Tutorial</b> del alumno/a <b> $nombre $apellidos </b>para el día <b>";
}
 
echo formatea_fecha($fecha);
echo "</b> se ha activado.";
echo '</div>
</div>';
exit;
?>
</div>
</div>
	<?php include("../../pie.php");?>								
</body>
</html>


