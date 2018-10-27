<?php
require('../../bootstrap.php');

include("../../menu.php");
include("../../faltas/menu.php");
?>

<div class="container">

<div class="page-header">
  <h2>Faltas de Asistencia <small> Resumen de faltas por Asignatura</small></h2>
  </div>
<br />
<div class="row">
<div class="col-sm-10 col-sm-offset-1">

<?php

if (isset($profe)) {}else{$profe= $_SESSION['profi'];}
if (isset($materia)) {

$tr = explode(" -> ",$materia);
$cod_asig = $tr[0];
$asignatura = $tr[1];
$grupo = $tr[2];
$nivel = $tr[3];
$nivel_bach = substr($nivel,0,9);
//echo "$asignatura --> $grupo --> $nivel<br>";
$SQL = "select FALTAS.claveal, count(*) as numero, codasi, CONCAT( apellidos, ', ', nombre ) as ncompleto from FALTAS, alma where FALTAS.claveal = alma.claveal and codasi like '$cod_asig' and FALTAS.unidad = '$grupo' and falta='F' group by  FALTAS.claveal, codasi, ncompleto order BY alma.apellidos, alma.nombre";
//echo $SQL;
$result = mysqli_query($db_con, $SQL);
if ($result) {
	echo "<center><p class='lead'><small>$asignatura ( $grupo )</small></p>";
}
  if ($row = mysqli_fetch_array($result))
        {
        echo "<table class='table table-striped' style='width:auto'>\n";
        echo "<thead><th width=\"60\"></th><th>Alumno</th><th>Total</th><th>Fechas</th></thead><tbody>";
        do {
			echo "<tr><td>";
			if ($foto = obtener_foto_alumno($row[0])) {
				echo '<img class="img-thumbnail" src="../../xml/fotos/'.$foto.'" style="width: 45px !important;" alt="">';
			}
			else {
				echo '<span class="img-thumbnail far fa-user fa-fw fa-2x" style="width: 45px !important;"></span>';
			}
			echo "</td><td nowrap>";
			echo "<a href='informes.php?claveal=$row[0]&codigo=$cod_asig&fechasp1=".$config['curso_inicio']."&fechasp3=".$config['curso_fin']."&submit2=2' target='_blank'>$row[3]</a></td><td ><strong>$row[1]</strong></td><td>";
			$result_fechas = mysqli_query($db_con, "SELECT DISTINCT fecha, dia, hora, falta FROM FALTAS WHERE claveal = '$row[0]' AND codasi = '$cod_asig' ORDER BY fecha ASC");

			$diasem = array('1'=>'Lunes', '2'=>'Martes', '3'=>'Miercoles', '4'=>'Jueves', '5'=>'Viernes');

			while ($fechas = mysqli_fetch_array($result_fechas)) {
				if ($fechas[3]=="F") {
					echo "<strong class='text-danger'>".cambia_fecha($fechas[0])."</strong> (<small class='text-muted'>".$diasem[$fechas[1]].": $fechas[2]ª</small>); ";
				}elseif ($fechas[3]=="J") {
					echo "<strong class='text-success'>".cambia_fecha($fechas[0])."</strong> (<small class='text-muted'>".$diasem[$fechas[1]].": $fechas[2]ª</small>); ";
				}
			}


			echo "</td></tr>\n";
        } while($row = mysqli_fetch_array($result));
        echo "</tbody></table></center>";
        }
        else
        {
			echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCIÓN:</legend>
No hay registros coincidentes, bien porque te has equivocado
        al introducir los datos, bien porque ningun dato se ajusta a tus criterios.
		</div></div><br />';
?>
        <?php
        }
}
else{
				echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;text-align:left">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCIÓN:</legend>
Debes seleccionar una asugnatura con su Grupo y Nivel. Vuelve atrás e inténtalo de nuevo.
		</div></div><br />';
}
  ?>
</div>
</div>
</div>

	<?php include("../../pie.php"); ?>

</body>
</html>
