<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}
?>
<script>
function confirmacion() {
	var answer = confirm("ATENCI�N:\n �Est�s seguro de que quieres borrar los datos? Esta acci�n es irreversible. Para borrarlo, pulsa Aceptar; de lo contrario, pulsa Cancelar.")
	if (answer){
return true;
	}
	else{
return false;
	}
}
</script>
<?
include("../../menu.php");
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['no_dia'])) {$no_dia = $_GET['no_dia'];}elseif (isset($_POST['no_dia'])) {$no_dia = $_POST['no_dia'];}else{$no_dia="";}
if (isset($_GET['profeso'])) {$profeso = $_GET['profeso'];}elseif (isset($_POST['profeso'])) {$profeso = $_POST['profeso'];}else{$profeso="";}
if (isset($_GET['hora'])) {$hora = $_GET['hora'];}elseif (isset($_POST['hora'])) {$hora = $_POST['hora'];}else{$hora="";}
if (isset($_GET['borrar'])) {$borrar = $_GET['borrar'];}elseif (isset($_POST['borrar'])) {$borrar = $_POST['borrar'];}else{$borrar="";}

if ($no_dia== '1') {$nombre_dia = 'Lunes';}
if ($no_dia== '2') {$nombre_dia = 'Martes';}
if ($no_dia== '3') {$nombre_dia = 'Mi�rcoles';}
if ($no_dia== '4') {$nombre_dia = 'Jueves';}
if ($no_dia== '5') {$nombre_dia = 'Viernes';}
$mes=date('m');
$dia_n = date('d');
$ano = date('Y');
$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia_n,$ano));

if ($no_dia> $numerodiasemana) {
	$dif = $no_dia- $numerodiasemana;
	$g_dia = date('d')+$dif;
 } 
 if ($no_dia< $numerodiasemana) {
	$dif = $numerodiasemana - $no_dia;
	$g_dia = date('d')-$dif;
 } 
 if ($no_dia== $numerodiasemana) {
 	$dif = 0;
 	$g_dia = date('d');
 }
 if ($g_dia=="") {
 	$g_dia = date('d');
 }
 	$g_fecha = date("Y-m-$g_dia");
 	$fecha_sp = formatea_fecha($g_fecha);
?>
<br />
<div align=center>
 <div class="page-header">
  <h2>Guardias de Aula <small> <? echo $fecha_sp;?></small></h2>
</div>
<div class="container-fluid">
<div class="row">
<div class="well" style="width:500px;">
	   <FORM action="guardias_admin.php" method="POST" name="Cursos">

             <legend>Selecciona Profesor</legend>
              <SELECT  name=profeso onchange="submit()" class="input-xlarge">
              <option><? echo $profeso;?></option>
		        <?
  $profe = mysql_query(" SELECT distinct prof FROM horw where a_asig='GU' order by prof asc");
  if ($filaprofe = mysql_fetch_array($profe))
        {
        do {

	      $opcion1 = printf ("<OPTION>$filaprofe[0]</OPTION>");
	      echo "$opcion1";

	} while($filaprofe = mysql_fetch_array($profe));
        }
	?>
              </select>         
          </FORM>
          </div>
<div class="col-sm-5 col-sm-offset-1">
<?
if ($profeso) {
echo '<h3>'.$profeso.'</h3><br />';	
echo '  <div align="center" class="well well-large">';
}
?>

<?
if ($borrar=='1') {
	mysql_query("delete from guardias where id='$id'");
	echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
La sustituci�n ha sido borrada correctamente. Puedes comprobarlo en la tabla de la derecha.
          </div></div>';
}
?>

<? 
	if ($profeso) {
$link = "1";
$pr=$profeso;
include("../../horario.php");

}
echo "</div>";
?>
</div>
<div class="col-sm-5">

<?
$fech_hoy = date("Y-m-d");
$hoy0 = mysql_query("select id, profesor, profe_aula, hora, fecha from guardias where dia = '$no_dia' and hora = '$hora' and date(fecha_guardia) = '$g_fecha'");
if (mysql_num_rows($hoy0) > 0) {
	echo '<br />';
	echo "<h3>Sustituciones registradas para la Guardia de hoy</h3><br />";
	echo '<table class="table table-striped">';
	echo "<tr><th>Profesor de Guardia</th><th>Profesor ausente</th></tr>";
	while ($hoy = mysql_fetch_array($hoy0)) {
			echo "<tr><td>$hoy[1]</td><td>$hoy[2]</td></tr>";
	}
	echo "</table>";
}
if ($profeso and $no_dia and $hora) {
	echo '<a name="marca"></a><br />';
?>
<h3>Sustituciones realizadas durante la <? echo "<span style=''>".$hora."�</span>";?> hora del <? echo "<span style=''>$nombre_dia</span>";?></h3><br />
<?
}
?>
<?
echo '<table class="table table-striped" style="width:500px;">';
$h_gu0= mysql_query("select prof from horw where dia = '$no_dia' and hora = '$hora' and a_asig = 'GU'");

while ($h_gu = mysql_fetch_array($h_gu0)) {
	echo "<td>";
		echo "<span>$h_gu[0]</span>";

	echo "</td><td>";
	$num_g0=mysql_query("select id from guardias where profesor = '$h_gu[0]' and dia = '$no_dia' and hora = '$hora'");
	$ng_prof = mysql_num_rows($num_g0);
	echo $ng_prof;
	echo "</td>";
	echo "</tr>";
}
echo "</table>";
?>

<?
if ($profeso) {
$extra = " and hora = '$hora' and dia = '$no_dia'";
$extra1 = " a ".$hora."� hora del ".$nombre_dia;		
$h_hoy0 = mysql_query("select id, profesor, profe_aula, hora, fecha_guardia, dia from guardias where profesor = '$profeso'");
if (mysql_num_rows($h_hoy0) > 0) {

	echo "<h3>Sustituciones realizadas por el profesor</h3><br />";
	echo '<table class="table table-striped">';
	echo "<tr><th>Profesor Ausente</th><th>Fecha de la Guardia</th><th>D�a</th><th>Hora</th><th></th></tr>";
	while ($h_hoy = mysql_fetch_array($h_hoy0)) {
$nu_dia = $h_hoy[5];
if ($nu_dia == '1') {$nom_dia = 'Lunes';}
if ($nu_dia == '2') {$nom_dia = 'Martes';}
if ($nu_dia == '3') {$nom_dia = 'Mi�rcoles';}
if ($nu_dia == '4') {$nom_dia = 'Jueves';}
if ($nu_dia == '5') {$nom_dia = 'Viernes';}
$fecha_sp = formatea_fecha($h_hoy[4]);
echo "<tr><td>$h_hoy[2]</td><td >$fecha_sp</td><td >$nom_dia</td><td >$h_hoy[3]</td><td ><a href='guardias_admin.php?id=$h_hoy[0]&borrar=1&profeso=$profeso' style='margin-top:5px;color:brown;'><i class='fa fa-trash-o' title='Borrar' onClick='return confirmacion();'> </i> </a>";
	}
	echo "</table>";
}
else{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No hay datos sobre las Guardias del profesor.
</div></div>';
}
}
?>
</div>
</div>
</div>
<? include("../../pie.php");?>
</BODY>
</HTML>
