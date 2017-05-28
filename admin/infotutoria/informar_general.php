<?php
if (isset($_POST['submit1']) and $_POST['submit1']=="Enviar Datos") {
	include("rellenainf.php");
	exit;
}

require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$pr = $_SESSION['profi'];

include("../../menu.php");
include("menu.php");
?>
<div class="container">
<div class="row">
<div class="page-header">
  <h2>Informes sobre Grupos <small> Redactar Informe por asignatura</small></h2>
</div>
<br>

<div class="col-md-6 col-md-offset-3">	
        
<?php
$asignatura = $_POST['asignatura'];
$alumno=mysqli_query($db_con, "SELECT infotut_alumno.CLAVEAL, infotut_alumno.APELLIDOS, infotut_alumno.NOMBRE, infotut_alumno.unidad, infotut_alumno.id, infotut_alumno.motivo FROM infotut_alumno WHERE ID='$id'");
$dalumno = mysqli_fetch_array($alumno);

if (empty($dalumno[0])) {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCIÓN:</legend>
Debes seleccionar un alumno en primer lugar.<br>Vuelve atrás e inténtalo de nuevo
<br><br /><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-primary">
		</div></div>';
	exit;	
}
?>
<div class="well well-large">
 <form name="informar" method="POST" action="informar_general.php?id=<?php echo $id;?>"> 
<?php
echo "<input type='hidden'  name='ident' value='$id'>";
echo "<input type='hidden'  name='profesor' value='$pr'>";
$claveal=trim($dalumno[0]);
if (empty($dalumno[0])) {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCIÓN:</legend>
Debes seleccionar un alumno en primer lugar.<br>Vuelve atrás e inténtalo de nuevo.<br /><br /
><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-danger">
</div></div><hr>';
	exit();	
}

echo "<p align=center class='lead'>Informe general de Grupo ( $dalumno[3] )</p>";

echo "<br />";
echo "<label>Motivo del Informe:</label>";
$motivo_reunion = ($dalumno['motivo']) ? $dalumno['motivo'] : 'No se ha especificado el motivo de la reunión.';
echo "<p class=\"text-info\">".$motivo_reunion."</p>";

$coinciden = mysqli_query($db_con, "SELECT distinct materia FROM profesores WHERE grupo = '$dalumno[3]' and profesor = '$pr'");

if(mysqli_num_rows($coinciden)<1 and stristr($_SESSION['cargo'],'1') == TRUE){
$coinciden = mysqli_query($db_con, "SELECT distinct materia FROM profesores WHERE grupo = '$dalumno[3]'");	
}
echo "<div class='form-group'><label>Asignatura</label><select name='asignatura' class='form-control' required onChange='submit()'>";
echo"<OPTION>$asignatura</OPTION>";
while($coinciden0 = mysqli_fetch_row($coinciden)){
$n_asig = $coinciden0[0];
	echo"<OPTION value='$n_asig'>$n_asig</OPTION>";
}
echo "</select></div>";

$ya_hay=mysqli_query($db_con, "select informe from infotut_profesor where asignatura = '$asignatura' and id_alumno = '$id'");
$ya_hay1=mysqli_fetch_row($ya_hay);
if (isset($asignatura)) {
						$informe=$ya_hay1[0];
					}
					else{
						$informe="";
						//$materia = $n_asig;
						//$extra = " selected='selected'";
					}
echo "<div class='form-group'><label>Informe</label><textarea rows='6' name='informe' class='form-control' required>$informe</textarea></div>";
?>
<input name="submit1" type=submit value="Enviar Datos" class="btn btn-primary btn-block">
</form>
</div>
</div>
</div>
</div>

<?php include("../../pie.php");?>		
</body>
</html>
