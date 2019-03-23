<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}


$pr = $_SESSION['profi'];
$cargo = $_SESSION['cargo'];


$tut = mysqli_query($db_con, "select unidad from FTUTORES where tutor = '$pr'");
$tuto = mysqli_fetch_array($tut);
$unidad = $tuto[0];

include("../../menu.php");
include("menu.php");
?>
<div class="container">

	<div class="page-header">
		<h2>Informes de Tutoría <small> Informes activos</small></h2>
	</div>

	<div class="row">

		<div class="col-md-8 col-md-offset-2">

<?php
 //Validación del Informe por el Tutor o Directivo
if (isset($_GET['validar'])) {
	$validar = $_GET['validar'];

if ($validar=='1') {
	mysqli_query($db_con, "update infotut_alumno set valido='0' where id = '$id'");
		echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido marcado como <b>NO VALIDADO</b> por el Tutor. Esto significa que el Informe no podrá ser visto por los Padres del Alumno desde la página pública del Centro
		</div></div>';
}
elseif ($validar=='0') {
	mysqli_query($db_con, "update infotut_alumno set valido='1' where id = '$id'");
		echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido marcado como <b>VALIDADO</b> por el Tutor. Esto significa que el Informe podrá ser visto por los Padres del Alumno desde la página pública del Centro
		</div></div>';
}
}
?>
	<p class='lead text-info'>Informes de tutoría para tus alumnos</p>
<?php

// Buscamos los grupos que tiene el Profesor, con su asignatura y nivel
	$SQLcurso = "select distinct grupo, materia, nivel from profesores where profesor = '$pr'";
	$resultcurso = mysqli_query($db_con, $SQLcurso);
	while($rowcurso = mysqli_fetch_array($resultcurso))
	{
	$grupo = $rowcurso[0];
	$asignatura = trim($rowcurso[1]);


// Buscamos el código de la asignatura (materia) de cada grupo al que da el profesor
	$asigna0 = "select codigo, nombre from asignaturas where nombre = '$asignatura' and curso = '$rowcurso[2]' and abrev not like '%\_%'";
	//echo "$asigna0<br>";
	$asigna1 = mysqli_query($db_con, $asigna0);
	$asigna2 = mysqli_fetch_array($asigna1);
	$c_asig = $asigna2[0];
	$n_asig = $asigna2[1];
	$hoy = date('Y-m-d');
	$nuevafecha = strtotime ( '-2 day' , strtotime ( $hoy ) ) ;
	$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

// Buscamos los alumnos de esos grupos que tienen informes de Tutoría activos y además tienen esa asignatura en el campo combasi; o bien si hay un Informe general de Grupo activo.
	if ($c_asig == '21' || $c_asig == '136') {

		$result_alumnos_pt_o_ref = mysqli_query($db_con, "SELECT alumnos FROM grupos WHERE profesor = '$pr' AND curso = '$grupo' LIMIT 1");
		if (mysqli_num_rows($result_alumnos_pt_o_ref)) {
			$row_alumnos_pt_o_ref = mysqli_fetch_array($result_alumnos_pt_o_ref);
			$alumnos_pt_o_ref = explode(',', $row_alumnos_pt_o_ref['alumnos']);
		}
		$query_pt_o_ref = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, FECHA_REGISTRO, valido, alma.claveal FROM infotut_alumno, alma WHERE alma.claveal = infotut_alumno.claveal and date(F_ENTREV)>='$nuevafecha' and alma.unidad = '$grupo' ORDER BY F_ENTREV asc";
		$pre_alumnos = array();
		$pre_result = mysqli_query($db_con, $query_pt_o_ref);
		while ($pre_row = mysqli_fetch_array($pre_result)) {
			array_push($pre_alumnos, $pre_row['claveal']);
		}

		$alumnos_candidatos = 0;
		foreach ($pre_alumnos as $alumno) {
			if (in_array($alumno, $alumnos_pt_o_ref)) $alumnos_candidatos = 1;
		}

		if ($alumnos_candidatos) {
			$query = $query_pt_o_ref;
			$result_pt_o_ref = mysqli_query($db_con, $query_pt_o_ref);
		}
	}
	else {
		$query = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, FECHA_REGISTRO, valido, alma.claveal FROM infotut_alumno, alma WHERE alma.claveal = infotut_alumno.claveal and date(F_ENTREV)>='$nuevafecha' and alma.unidad = '$grupo' and combasi like '%$c_asig%' ORDER BY F_ENTREV asc";
	}
	$query_g = "SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, FECHA_REGISTRO, motivo, alma.claveal FROM infotut_alumno WHERE date(F_ENTREV)>='$nuevafecha' and infotut_alumno.unidad = '$grupo' and apellidos like 'Informe %' ORDER BY F_ENTREV asc";
	//echo $query_g."<br>";

	$result = mysqli_query($db_con, $query);
	$result_g = mysqli_query($db_con, $query_g);

	$result0 = mysqli_query($db_con, "select tutor, unidad from FTUTORES where unidad = '$grupo'" );
	$row0 = mysqli_fetch_array ( $result0 );
	$tuti = mb_strtoupper($row0[0]);
	$tuti_grupo = $row0[1];

	if (mysqli_num_rows($result) > 0 or mysqli_num_rows($result_g) > 0 or mysqli_num_rows($result_pt_o_ref) > 0){
	$si_al.=1;
	echo "<form name='consulta' method='POST' action='tutoria.php'>";
	//$num_informe = mysqli_num_rows($sql1);
	echo "<p class='lead text-success'>$grupo <br /><small class='text-muted'>$n_asig</small></p>";
	echo "<table align=left class='table table-striped'><tr class='active'>";
	echo "<th>Alumno</th>
	<th>Cita padres</th>
	<th>Fecha alta</th>
	<th></th>
	<th></th>
	</tr>";

	$count = "";
	$count_g = "";

	// Informe de un alumno

	while($row = mysqli_fetch_array($result))
	{

		$validado="";
		$validado =  $row[5];
		$count = $count + 1;
		echo "<tr><TD>
		$row[1], $row[2]</td>
		<TD>$row[3]</td>
		<TD>$row[4] </td>
		<td>";
		echo "
		<input type='hidden' name='profesor' value='$profesor'>";
		if (mysqli_num_rows($si) > 0 and $count < 1) {

		}
		else{
			//echo "$grupo == ".$_SESSION['mod_tutoria']['unidad'];
			echo "<a href='infocompleto.php?id=$row[0]&c_asig=$asignatura' class=''><i class='fas fa-search fa-fw fa-lg' data-bs='tooltip'  title='Ver Informe'> </i></a>";
			if (stristr($cargo,'1') == TRUE or ($tuti == mb_strtoupper($_SESSION['profi']) and ($grupo == $_SESSION['mod_tutoria']['unidad']))) {
				echo "<a href='borrar_informe.php?id=$row[0]&del=1' class='' data-bb='confirm-delete'><i class='far fa-trash-alt fa-fw fa-lg' data-bs='tooltip' title='Borrar Informe' ></i></a>";
			}
		}

		if (mysqli_num_rows($si) > 0 and $count < 1){

		}
		else {
			echo "<a href='informar.php?id=$row[0]' class=''><i class='fas fa-pencil-alt fa-fw fa-lg' data-bs='tooltip' title='Redactar Informe'></i></a>";
		}
		echo "</td><td>";
		//echo "$tuti == ".$_SESSION['profi']."<br>";
		if (stristr($cargo,'1') == TRUE or ($tuti == mb_strtoupper($_SESSION['profi']))) {
			if ($validado==1) {
				echo "<a href='index.php?id=$row[0]&validar=1' class='text-info'><i class='fas fa-check-squarefa-fw fa-lg' data-bs='tooltip' title='Informe validado por el Tutor' > </i></a>";
			}
			else{
				echo "<a href='index.php?id=$row[0]&validar=0' class='text-danger'><i class='far fa-minus-circle fa-fw fa-lg' data-bs='tooltip' title='Informe no validado por el Tutor' > </i> </a> 	";
			}
		}
		echo "</td>
		</tr>";

	}

	// Informe de Grupo

	while($row = mysqli_fetch_array($result_g))
	{
	$count_g = $count_g + 1;
	$fecha_no = cambia_fecha($row[3]);
	$fecha_reg = cambia_fecha($row[4]);
	echo "<tr><TD>
	$row[5]</td>
   <TD>$fecha_no</td>
   <TD>$fecha_reg</td>
   <td>";
	 echo "
	 <input type='hidden' name='profesor' value='$profesor'>";
		 if (mysqli_num_rows($si) > 0 and $count < 1)
		{} else{
		//echo "$grupo == ".$_SESSION['mod_tutoria']['unidad'];
			echo "<a href='infocompleto.php?id=$row[0]&c_asig=$asignatura' class=''><i class='fas fa-search fa-fw fa-lg' data-bs='tooltip'  title='Ver Informe'> </i></a>";
			if (stristr($cargo,'1') == TRUE or ($tuti == mb_strtoupper($_SESSION['profi']) and ($grupo == $_SESSION['mod_tutoria']['unidad']))) {
				echo "&nbsp;<a href='borrar_informe.php?id=$row[0]&del=1' class=''>
				<i class='far fa-trash-alt fa-fw fa-lg' data-bs='tooltip' title='Borrar Informe' > </i> </a> 	";
			}
		}

	  if (mysqli_num_rows($si) > 0 and $count_g < 1)
		{} else{
echo "&nbsp;<a href='informar_general.php?id=$row[0]' class=''><i class='fas fa-pencil-alt fa-fw fa-lg' data-bs='tooltip'  title='Redactar Informe'> </i> </a>";
				}
		echo "</td><td>";
   echo "</td>
   </tr>";
	}

	echo "</table>";

	echo "</form><hr>";
	}
}


if (strstr($si_al,"1")==FALSE and $n_infotut < 1) {
	 echo "<div class='alert alert-warning' align='center'><p><i class='fas fa-check-square'> </i> No hay Informes de Tutoría activos para los alumnos de tus grupos. </p></div><br>";
 }

// Alumnos pendientes con asignaturas sin continuidad para los Jefes de Departamento
if(strstr($_SESSION['cargo'],"4")==TRUE){
	$n_pend=0;
?>
	<div style="height:100px;"> </div>
	<p class='lead text-info'>Alumnos con materias pendientes de tu Departamento<small class="text-muted"> (<?php echo $_SESSION['dpt'];?>)</small></p>
<?php

$hoy = date("Y-m-d");

$query = mysqli_query($db_con,"SELECT id, infotut_alumno.apellidos, infotut_alumno.nombre, F_ENTREV, infotut_alumno.claveal, alma.unidad FROM infotut_alumno, alma WHERE infotut_alumno.claveal = alma.claveal and date(F_ENTREV) >= '$hoy' ORDER BY F_ENTREV asc");

while($row = mysqli_fetch_array($query)){
	$query2 = mysqli_query($db_con,"select distinct claveal, nombre from pendientes, asignaturas where pendientes.codigo = asignaturas.codigo and claveal = '$row[4]' and nombre not in (select materia from profesores where grupo = '$row[5]')");
	if (mysqli_num_rows($query2)>0) {
		while($row2 = mysqli_fetch_array($query2)){

			$query3 = mysqli_query($db_con,"select * from profesores, asignaturas where materia = nombre and materia = '$row2[1]' and abrev like '%\_%' and profesor in (select distinct nombre from departamentos where departamento like '".$_SESSION['dpt']."')");

			if (mysqli_num_rows($query3)>0) {
				$cabecera++;
				if ($cabecera==1) {

?>

	<table align=left class='table table-striped'>
	<tr class='active'>
	<th>Alumno</th>
	<th>Cita padres</th>
	<th>Asignatura</th>
	<th></th>
	</tr>

<?php
			}
				$fecha_n = cambia_fecha($row[3]);
				echo "<tr><TD>$row[1], $row[2] <b>($row[5]</b>)</td>
				   <TD>$fecha_n</td>
				   <TD>$row2[1]</td>
				   <td>";

				$n_pend++;

				echo "<a href='infocompleto.php?id=$row[0]&c_asig=$row2[1]' class='pull-right'><i class='fas fa-search fa-fw fa-lg' data-bs='tooltip'  title='Ver Informe'> </i></a>";
				echo "<a href='informar.php?id=$row[0]&materia=$row2[1]' class='pull-right'><i class='fas fa-pencil-alt fa-fw fa-lg' data-bs='tooltip' title='Redactar Informe'></i></a>";

		?>
			</td>
		</tr>
		<?php
			}
				if ($cabecera>1) {
				?>
				</table>
				<?php
				}
			}
		}
	}
}
?>
</table>
<?
 if (strstr($_SESSION['cargo'],"4")==TRUE and $n_pend < 1) {
	 echo "<div class='alert alert-info' align='center'><p><i class='fas fa-check-square'> </i> No hay Informes de Tutoría activos para alumnos con materias pendientes de tu Departamento. </p></div><br>";
 }
?>
  </div>
  </div>
  </div>
<?php include("../../pie.php");?>
</body>
</html>
