<?php

if (isset($_POST['submit1'])) {
	include("imprimir.php");
}
elseif(isset($_POST['submit2'])){
	include("registrar.php");
}

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
  <h2>Actividades Complementarias y Extraescolares <small> Selección de alumnos</small></h2>
</div>
</div>

<?php


if (isset($_POST['id'])) {
	$id = $_POST['id'];
}
else{
	$id = $_GET['id'];
}

if (isset($_POST['poner_falta'])) {
	$datos0 = "SELECT fechaini, horaini, fechafin, horafin, profesores FROM calendario WHERE id ='$id'";
	$datos1 = mysqli_query($db_con, $datos0);
	$datos = mysqli_fetch_array($datos1);
	$fecha0 = explode("-",$datos[0]);
	$hoy = $datos[0];
	$fecha  = $fecha0[2]."-". $fecha0[1]."-". $fecha0[0];
	$hora_ini = $datos[1];
	$fecha1 = explode("-",$datos[2]);
	$fecha2  = $fecha1[2]."-". $fecha1[1]."-". $fecha1[0];
	$hora_fin = $datos[3];
	$prof = explode(";",$datos[4]);
	$profes = mysqli_query($db_con,"select distinct c_prof from horw where prof = '$prof[0]'");
	$c_prof = mysqli_fetch_array($profes);	
	$nprofe = $c_prof[0];
	$ndia = date('N', strtotime($datos[0]));

	$borra = mysqli_query($db_con, "delete from FALTAS where FECHA = '$hoy' and profesor = '$nprofe' and CODASI = '289'");

	$hr = mysqli_query($db_con,"select hora from tramos where hora_inicio >= '$hora_ini' and hora_fin <= '$hora_fin'");
	while ($horas_act = mysqli_fetch_array($hr)) {
		if (is_numeric($horas_act[0])) {
			$horas.=$horas_act[0].";";
		}		
	}
	
	foreach($_POST as $key => $val)
		{
			if(strstr($key,"falta_"))
			{

				$claveal = $val;

				$nv = mysqli_query($db_con,"select unidad from alma where claveal='$claveal'");
				$n_uni = mysqli_fetch_row($nv);
				$unidad = $n_uni[0];

				$faltas_horas = explode(";",$horas);
				foreach ($faltas_horas as $hora_falta) {
					if (!empty($hora_falta)) {
						$insertar_falta = "insert INTO  FALTAS (  CLAVEAL , unidad , FECHA ,  HORA , DIA,  PROFESOR ,  CODASI ,  FALTA ) VALUES ('$claveal',  '$unidad', '$hoy',  '$hora_falta', '$ndia',  '$nprofe',  '289', 'F')";
						//echo $insertar_falta."<br>";
						mysqli_query($db_con, $insertar_falta);
					}
				}

			}
		}
?>

<div class="alert alert-success col-sm-10 col-sm-offset-1">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Las faltas de asistencia se han regsitrado correctamente.
</div><br>

<?php          	
}


if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'4') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE)
{
	$jefes=1;
}

$profes_actividad = $_GET['profesores'];
?>

<div class="col-sm-8 col-sm-offset-2">

<form action="extraescolares.php" method="POST" name="imprime">

  <?php
$cursos0 = mysqli_query($db_con, "select unidades, profesores, nombre from calendario where id = '$id'");
while($cursos = mysqli_fetch_array($cursos0))
{
$actividad=$cursos[2];
echo "<legend align='center' class='text-info'>$actividad</legend>";
$profes_actividad = $cursos[1];
$profesor="";
$profes="";
$profes = explode(";",$cursos[1]);
foreach ($profes as $n_profe)
{
$profe = explode(", ",$n_profe);
$profesor.=$profe[1]." ".$profe[0].", ";
//$profesor.=$profeso.",";
}
$profesor = substr($profesor,0,-5);
$uni=substr($cursos[0],0,strlen($cursos[0])-1);
$trozos = explode(";",$uni);

if (($jefes==1 or strstr(mb_strtoupper($profes_actividad),mb_strtoupper($_SESSION['profi']))==TRUE) and $_GET['ver_lista']!=="1" and !isset($_POST['poner_falta'])) {
?>
<div class="col-sm-8 col-sm-offset-2">
<a href="javascript:seleccionar_todo()" class="btn btn-primary btn-sm hidden-print">Marcar todos</a>
<a href="javascript:deseleccionar_todo()" class="btn btn-primary btn-sm pull-right hidden-print">Desmarcar todos</a>
<br />
<br />
</div>

<?php } 
foreach($trozos as $valor)
{
$unidad = trim($valor);
?>

<table class="table table-striped" align="center" style="width:350px">
<tr><td colspan="2">
	<h4><?php echo "Alumnos de $unidad";?></h4></td>
</tr>
<?php
$alumnos0 = "SELECT claveal, nombre, apellidos FROM alma where unidad = '$unidad' ORDER BY apellidos ASC, nombre ASC";
//echo $cursos[0]." => ".$alumnos0."<br>";
$alumnos1 = mysqli_query($db_con, $alumnos0);
$num = mysqli_num_rows($alumnos1);

$datos0 = "SELECT fechaini, horaini, profesores, nombre, descripcion, observaciones, fechafin, horafin, lugar FROM calendario WHERE id ='$id'";
$datos1 = mysqli_query($db_con, $datos0);
$datos = mysqli_fetch_array($datos1);
$fecha0 = explode("-",$datos[0]);
$fecha  = $fecha0[2]."-". $fecha0[1]."-". $fecha0[0];
$horario = $datos[1];
$actividad = $datos[3];
$descripcion = $datos[4];
$observaciones = $datos[5];
$fecha1 = explode("-",$datos[6]);
$fecha2  = $fecha1[2]."-". $fecha1[1]."-". $fecha1[0];
$horario2 = $datos[7];
$lugar = $datos[8];
?>
<?php

if ($jefes==1 OR strstr(mb_strtoupper($profes_actividad),mb_strtoupper($_SESSION['profi']))==TRUE) {
?>
<input name="lugar" type="hidden" value="<?php echo $lugar;?>">
<input name="fecha" type="hidden" value="<?php echo $fecha;?>">
<input name="horario" type="hidden" value="<?php echo $horario;?>">
<input name="fechafin" type="hidden" value="<?php echo $fecha2;?>">
<input name="horariofin" type="hidden" value="<?php echo $horario2;?>">
<input name="profesor" type="hidden" value="<?php echo $profesor;?>">
<input name="actividad" type="hidden" value="<?php echo htmlspecialchars($actividad);?>">
<input name="descripcion" type="hidden" value="<?php echo $descripcion;?>">
<input name="observaciones" type="hidden" value="<?php echo $observaciones;?>">
<input name="id" type="hidden" value="<?php echo $id;?>">
<?php }
$nc = 0;
while($alumno = mysqli_fetch_array($alumnos1)){
$nc++;
$apellidos = $alumno['apellidos'];
$nombre = $alumno['nombre'];
$claveal = $alumno['claveal'];
$extra_al="";
$ya = mysqli_query($db_con,"select * from actividadalumno where cod_actividad='$id' and claveal='$claveal'");
if (mysqli_num_rows($ya)>0) {
	$extra_al = 'checked';
}
if(($_GET['ver_lista']=="1" and $extra_al!=="" ) or isset($_POST['poner_falta'])){
?>
<tr>
<td >
<?php if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE)
{ ?>
<?php

	$datos0 = "SELECT fechaini, horaini, fechafin, horafin FROM calendario WHERE id ='$id'";
	$datos1 = mysqli_query($db_con, $datos0);
	$datos = mysqli_fetch_array($datos1);
	$fecha0 = explode("-",$datos[0]);
	$hoy = $datos[0];
	$fecha  = $fecha0[2]."-". $fecha0[1]."-". $fecha0[0];
	$hora_ini = $datos[1];
	$hora_fin = $datos[3];
	$ndia = date('N', strtotime($datos[0]));

	$hr = mysqli_query($db_con,"select hora from tramos where hora_inicio >= '$hora_ini' and hora_fin <= '$hora_fin'");
	while ($horas_act = mysqli_fetch_array($hr)) {
		if (is_numeric($horas_act[0])) {
			$horas.=$horas_act[0].";";
		}		
	}

	$faltas_horas = explode(";",$horas);

	foreach ($faltas_horas as $hora_falta) {

		$falta_d = mysqli_query($db_con, "select distinct falta from FALTAS where dia = '$ndia' and hora = '$hora_falta' and claveal = '$claveal' and fecha = '$hoy' and CODASI = '289'");
		if(mysqli_num_rows($falta_d)>0){
			$extra_falta = "checked";
		}
	}
?>	
<input name="falta_<?php echo $claveal;?>" type="checkbox" class="checkbox pull-right hidden-print" value="<?php echo $claveal;?>" <?php echo $extra_falta;?>>
<?php } ?>
<?php
echo " $nc. $apellidos, $nombre";
?>
</td>
</tr>
<?php
}
elseif($_GET['ver_lista']!=="1"){
?>
<tr>
<td>
<input name="<?php echo $nc.$claveal;?>" type="checkbox" value="<?php echo $claveal;?>" <?php echo $extra_al;?>>
</td>
<td>
<?php
echo " $nc. $apellidos, $nombre";
?>
</td></tr>
<?php
}
}
?>
</table>
<?php
}
}
?>

<br />
	<div align="center">
	<?php
	if (($jefes==1 OR strstr(mb_strtoupper($profes_actividad),mb_strtoupper($_SESSION['profi']))==TRUE) and $_GET['ver_lista']!=="1" and !isset($_POST['poner_falta'])) {
	?>
	<button type="submit" name="submit1" value="Imprimir Carta para Padres" class="btn btn-primary hidden-print">Imprimir carta para padres</button>&nbsp;
	<button type="submit" name="submit2" value="Registrar Alumnos" class="btn btn-info hidden-print">Registrar alumnos</button>&nbsp;
	<?php } ?>
	<?php if ($_GET['ver_lista']==1 or isset($_POST['poner_falta'])) { ?>
	<input type="button" name="print"  class="btn btn-success hidden-print" value="Imprimir Lista de Alumnos" onclick="window.print();">
	<?php if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'5') == TRUE)
	{ ?>	
	<button type="submit" name="poner_falta" value="F" class="btn btn-danger hidden-print">Registrar ausencia</button>&nbsp;
	<?php } ?>
	<?php } else{ ?>
	<a href="extraescolares.php?id=<?php echo $_GET['id'] ?>&ver_lista=1"  class="btn btn-success hidden-print">Lista para imprimir</a>
	<?php } ?>
	</div>
  </form>

  </div>
</div>
  
 <script>
function seleccionar_todo(){
	for (i=0;i<document.imprime.elements.length;i++)
		if(document.imprime.elements[i].type == "checkbox")
			document.imprime.elements[i].checked=1
}
function deseleccionar_todo(){
	for (i=0;i<document.imprime.elements.length;i++)
		if(document.imprime.elements[i].type == "checkbox")
			document.imprime.elements[i].checked=0
}
</script>

	<?php include("../../pie.php"); ?>

</body>
</html>
