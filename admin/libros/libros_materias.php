<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

if (isset($_POST['nivel'])) {
	$nivel = $_POST['nivel'];
} 
elseif (isset($_GET['nivel'])) {
	$nivel = $_GET['nivel'];
} 
else
{
$nivel = "";
}

include("../../menu.php");
?>
<div class="container">

	<div class="page-header">
		<h2 style="display:inline;">Programa de Ayudas al Estudio <small>Informe sobre Alumnos y Materias</small></h2>
		
		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
			<span class="fa fa-question fa-lg"></span>
		</a>
	
		<!-- Modal -->
		<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
					</div>
					<div class="modal-body">
						<p>El módulo permite gestionar los Libros de Texto dentro del Programa de Ayudas al 
						Estudio de la Junta de Andalucía. Se activa durante los meses de Junio y Septiembre, 
						y desaparece posteriormente.</p>
						<p>El Tutor marca el estado de los libros según las siguientes opciones: Bien, Regular, 
						Mal, No hay libro y Septiembre (en caso de que el alumno haya suspendido la asignatura 
						en Junio, y pueda entregarlo tras la Evaluación Extraordinaria).</p>
						<p>Para evitar un cierre inesperado de la página y la pérdida de datos, la aplicación 
						envía el formulario cada 9 minutos, recargando la página. El comportamiento, por lo 
						tanto, es normal.</p>
						<p>Una vez terminado de marcar el estado de los libros de los alumnos de la Tutoría, 
						se envían los datos del formulario por última vez para guardarlos. El Tutor puede 
						modificar el estado hasta que desaparece el formulario en Septiembre.</p>
						<p>El Secretario del Centro puede emitir con la aplicación Certificados de entrega y 
						estado de los libros, así como Facturas en caso de que los libros se encuentren 
						desaparecidos o en un estado inaceptable.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>

		<h2 class="text-info"><?php echo (isset($unidad) && $unidad != "") ? $unidad : $_POST['nivel']; ?></h2>
	</div>
<br />
<?php
$tarari=0;

$curso = $nivel;

$unid = mysqli_query($db_con,"select distinct unidad from alma where curso = '$nivel' order by unidad");
while($unidad_gr = mysqli_fetch_array($unid)){
$unidad = $unidad_gr[0];

echo "<h3 align='center' class='text-danger'>$unidad</h3>";

echo "<br /><table class='table table-bordered' style='width:850px;margin:auto;'>";

if(stristr($_SESSION['cargo'],'1') == TRUE){
echo "<thead><tr><th></th>";
}
if (isset($_POST['nivel'])) {
	$p_nivel=$_POST['nivel'];
}

$asignaturas0 = "select distinct nombre, codigo, abrev from asignaturas where (curso like '".$curso."') and abrev not like '%\_%' and nombre in (select distinct materia from textos_gratis where textos_gratis.nivel = '".$p_nivel."') order by codigo";
// echo $asignaturas0;
$num_col = 1;
$asignaturas1 = mysqli_query($db_con, $asignaturas0);
$num_asig = mysqli_num_rows($asignaturas1);
while ($asignaturas = mysqli_fetch_array($asignaturas1)) {	
	$col{$num_col} = $asignaturas[1];
	echo "<th style='text-align:center;'>$asignaturas[2]</th>";
	$num_col = $num_col + 1;
}
	$extra=" and alma.unidad = '$unidad'";

echo "</thead><tbody>";
$alumnos0 = "select matriculas, alma.apellidos, alma.nombre, combasi, alma.claveal, alma.unidad from alma where alma.curso = '".$p_nivel."' $extra order by alma.apellidos, alma.nombre"; 
//echo $alumnos0;
$fila_asig=0;
$alumnos1 = mysqli_query($db_con, $alumnos0);
while ($alumnos = mysqli_fetch_array($alumnos1)) {
	$nc="$alumnos[1], $alumnos[2]";
	
	$fila_asig+=1;
	if(stristr($_SESSION['cargo'],'1') == TRUE){}else{}
	if($fila_asig == "14" or $fila_asig == "28" or $fila_asig == "42")
{
echo "</thead><tbody><tr><td style='background-color:#eee'></td>";
$asignaturas0 = "select distinct nombre, codigo, abrev from asignaturas where curso like '".$curso."' and abrev not like '%\_%' and nombre in (select distinct materia from textos_gratis where textos_gratis.nivel = '".$curso."') order by codigo";
// echo $asignaturas0."<br>";
$num_col = 1;
$asignaturas1 = mysqli_query($db_con, $asignaturas0);
$num_asig = mysqli_num_rows($asignaturas1);
while ($asignaturas = mysqli_fetch_array($asignaturas1)) {	
	$col{$num_col} = $asignaturas[1];
	echo "<th style='background-color:#eee;text-align:center';>$asignaturas[2]</th>";
	$num_col = $num_col + 1;
}
}

	echo "<tr><td>$nc";
	$clave = $alumnos[4];    	
	echo "</td>";
	for ($i=1;$i<$num_asig+1;$i++){
		echo "<td nowrap>";
		//echo $col{$i}."-";
		if(strstr($alumnos[3], $col{$i}))
		{
		$r_nombre = $alumnos[4]."-".$col{$i};
		$trozos = explode("-",$r_nombre);
		$claveal = $trozos[0];
		$asignatura = $trozos[1];
?>
		<label style="color:black;">
    	<span class="label label-info">X</span>
<?php
//			echo $col{$i};
		}
		echo "</td>";
		
	}

echo "</tr>";
	}
echo "</table><br><hr>";
}
?>
<br />
</div>
<?php include("../../pie.php");?>
</body>
</html>