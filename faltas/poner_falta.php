<?php
require('../bootstrap.php');
include("../menu.php");
if (isset($_GET['menu_cuaderno'])) {
	include("../cuaderno/menu.php");
	echo "<br>";
	$extra = "&menu_cuaderno=1&profesor=".$_SESSION['profi']."&dia=$dia&hora=$hora&curso=$curso&asignatura=$asignatura";
}
else {
	include("menu.php");
}
// nprofe hora ndia hoy codasi profesor clave
if (isset($_POST['nprofe'])) {$nprofe = $_POST['nprofe'];} else{$nprofe = $_SESSION['nprofe'];}
if (isset($_POST['hora'])) {$hora = $_POST['hora'];} else{$hora="";}
if (isset($_POST['ndia'])) {$ndia = $_POST['ndia'];} else{$ndia="";}
if (isset($_POST['hoy'])) {$hoy = $_POST['hoy'];} else{$hoy="";}
if (isset($_POST['codasi'])) {$codasi = $_POST['codasi'];} else{$codasi="";}
if (isset($_POST['profesor'])) {$profesor = $_POST['profesor'];} else{$profesor="";}
if (isset($_POST['clave'])) {$clave = $_POST['clave'];} else{$clave="";}
if (isset($_POST['fecha_dia'])) {$fecha_dia = $_POST['fecha_dia'];} else{$fecha_dia="";}
?>

<div class="container">

<div class="page-header">
<h2>Faltas de Asistencia <small> Poner faltas</small></h2>
</div>

<div class="row">
<?php		
// Borramos faltas para luego colocarlas de nuevo.
$borra = mysqli_query($db_con, "delete from FALTAS where HORA = '$hora' and FECHA = '$hoy' and profesor = '$nprofe' and (FALTA = 'F' or FALTA = 'J' or FALTA = 'R')");

$db_pass = trim($clave);

$unidades = "";
$contador = "";
$codasis = "";

foreach($_POST as $clave => $valor)
{
	if(strlen(strstr($clave,"falta_")) > 0)
	{
		$contador++;
		$nc0 = explode("_", $clave, 3);
		$nc = $nc0[1];
		$unidad = $nc0[2];
		if (stristr($unidades, $unidad)==FALSE) {
			$unidades.=$unidad."; ";
		}

		$nv = mysqli_query($db_con,"select distinct curso from alma where unidad='$unidad'");
		$nivel_grupo = mysqli_fetch_row($nv);
		$curso_grupo = $nivel_grupo[0];

		$clave1 = "select claveal from FALUMNOS where NC = '$nc' and unidad = '$unidad'";
		$clave0 = mysqli_query($db_con, $clave1);
		$clave2 = mysqli_fetch_row($clave0);
		$claveal = $clave2[0];

			// Comprobamos problema de varios códigos en Bachillerato y otros

			$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$codasi' limit 1) and grupo like '$unidad' and abrev not like '%\_%'");
				while($cod_bch = mysqli_fetch_array($asig_bach)){
					if (stristr($codasis, $codasi)==FALSE) {
						$codasis.=$cod_bch[0].";";
					}
				
				$comb = mysqli_query($db_con,"select * from alma where claveal='$claveal' and combasi like '%$cod_bch[0]%'");
				if (mysqli_num_rows($comb)>0) {
						$codigo_asignatura = $cod_bch[0];
					}
				}					
				if (strlen($codigo_asignatura)>0) {}
					else{
						$codigo_asignatura = $codasi;
					}

		// Insertamos las faltas de TODOS los alumnos.
		$t0 = "insert INTO  FALTAS (  CLAVEAL , unidad ,  NC ,  FECHA ,  HORA , DIA,  PROFESOR ,  CODASI ,  FALTA ) VALUES ('$claveal',  '$unidad', '$nc',  '$hoy',  '$hora', '$ndia',  '$nprofe',  '$codigo_asignatura', '$valor')";
		// echo $t0;
		$t1 = mysqli_query($db_con, $t0) or die("No se han podido insertar los datos");
		$count += mysqli_affected_rows();	

	}
}

// Control de los profesores que registran o no faltas
$unidades = substr($unidades,0,-2);
$codasis = substr($codasis,0,-1);
$dia_actual = date("Y-m-d");

$control = mysqli_query($db_con,"insert into control_faltas VALUES ('','$nprofe','$unidades','$ndia','$hora','$hoy','$codasis','$contador','$dia_actual')");

//Faltas en una Guardia
if (!empty($_POST['profesor_ausente'])) {
	$tiempo = '7000';
	$profesor_ausente = $_POST['profesor_ausente'];
	$profesor_real = $_POST['profesor'];
	$n_dia = $_POST['ndia'];
	
	// Cambiamos fecha
	$inicio1=$_POST['hoy'];
	$fin1 = $inicio1;
	
	//Horas
	$horas=$_POST['hora'];

	// Registramos o actualizamos ausencia del profesor sustituído en la guardia
	$ya = mysqli_query($db_con, "select * from ausencias where profesor = '$profesor_ausente' and date(inicio)<= date('$inicio1') and date(fin) >= date('$fin1')");
		if (mysqli_num_rows($ya) > '0') {
			$ya_hay = mysqli_fetch_array($ya);
			$horas_ya = $ya_hay['horas'];
			if (strstr($horas_ya,$horas)==FALSE and $horas_ya!=="0" and $horas_ya!=="") {
				$horas=$horas_ya.$horas;
				$actualiza = mysqli_query($db_con, "update ausencias set horas = '$horas' where id = '$ya_hay[0]'");
				echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos de la ausencia de '.$profesor_ausente.' se han actualizado correctamente.
          </div>';	
			}
			}
		else{
		$inserta = mysqli_query($db_con, "insert into ausencias VALUES ('', '$profesor_ausente', '$inicio1', '$fin1', '$horas', '', NOW(), '', '')");
			echo '<div class="alert alert-info">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	Se ha registrado la ausencia del profesor '.$profesor_ausente.'.
	  </div>';		
		}

	//Registramos sustitución en la tabla de Guardias
		if (stristr($curso_grupo, "E.S.O.") or (stristr($curso_grupo, "Bach") and $horas > 1 and $horas < 6)) {		
	$gu = mysqli_query($db_con, "select * from guardias where profe_aula = '$profesor_ausente' and dia = '$n_dia' and hora = '$horas' and fecha_guardia = '$inicio1'");
		if (mysqli_num_rows($gu)>0) {
			$guardi = mysqli_fetch_row($gu);
			echo '<div class="alert alert-warning alert-block fade in">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
		<legend>ATENCIÓN:</legend>No ha sido posible registrar la guardia porque el profesor aparentemente ya ha sido sustituído por un compañero de guardia: '.$guardi[1].'
	</div>';
		}
		else{
		$r_profe = mb_strtoupper($_SESSION['profi'], "UTF-8");

		mysqli_query($db_con, "insert into guardias (profesor, profe_aula, dia, hora, fecha, fecha_guardia, turno) VALUES ('$r_profe', '$profesor_ausente', '$n_dia', '$horas', NOW(), '$inicio1', '1')");
		if (mysqli_affected_rows($db_con) > 0) {
		echo '<div class="alert alert-info alert-block fade in">
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
Has registrado correctamente a '.$profesor_ausente.' a '.$horas.' hora para sustituirle en al Aula.
</div>';
			}	
		}			
	}
}

if (empty($mens_fecha)) {
	echo '<div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Las Faltas han sido registradas.
          </div>'; 
}
else{
	echo '<div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            '. $mens_fecha.'</div>'; 
}

if (empty($tiempo)) {
	$tiempo="3000";
}
?> 

<script language="javascript">
setTimeout("window.location='index.php?fecha_dia=<?php if (!empty($fecha_dia)) {  echo $fecha_dia;}else {echo date('d-m-Y');}?>&hora_dia=<?php echo $hora; ?><?php echo $extra;?>'", <?php echo $tiempo;?>) 
</script> 

</body>
</html>
