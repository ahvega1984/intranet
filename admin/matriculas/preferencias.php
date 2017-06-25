<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {
// Optativas ESO
	for ($i=1; $i<5; $i++) { 
		if (isset($_POST['opt'.$i.'_0'])) {
			for ($z=0; $z<10 ; $z++) { 
				${opt.$i}[] = $_POST['opt'.$i.'_'.$z.''];
			}
		}
		${c_.$i} = count(array_filter(${opt.$i}));
	}

// Actividades ESO
	for ($i=1; $i<4; $i++) { 
		if (isset($_POST['a'.$i.'_0'])) {
			for ($z=0; $z<10 ; $z++) { 
				${a.$i}[] = $_POST['a'.$i.'_'.$z.''];
			}
		}
		${ca_.$i} = count(array_filter(${a.$i}));
	}

// Itinerarios 1º Bach.
		if (isset($_POST['it1_0'])) {
			for ($z=0; $z<4 ; $z++) { 
				$it1[] = $_POST['it1_'.$z.''];
			}
		}

// Itinerarios 2º Bach.
		if (isset($_POST['it2_0'])) {
			for ($z=0; $z<4 ; $z++) { 
				$it2[] = $_POST['it2_'.$z.''];
			}
		}
	for ($i=1; $i<6; $i++) { 
		if (isset($_POST['it2'.$i.'_0'])) {
			for ($z=0; $z<5 ; $z++) { 
				${it2.$i}[] = $_POST['it2'.$i.'_'.$z.''];
			}
		}
		${cit2_.$i} = count(array_filter(${it2.$i}));
	}

	$prefInicio	= limpiar_string($_POST['prefInicio']);
	$prefFin	= limpiar_string($_POST['prefFin']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE MATRICULACIÓN\r\n");

		fwrite($file, "\$config['matriculas']['fecha_inicio']\t= '$prefInicio';\r\n");	
		fwrite($file, "\$config['matriculas']['fecha_fin']\t= '$prefFin';\r\n");

		fwrite($file, "\$opt1\t= array('$opt1[0]', '$opt1[1]', '$opt1[2]', '$opt1[3]', '$opt1[4]', '$opt1[5]');\r\n");
		fwrite($file, "\$opt2\t= array('$opt2[0]', '$opt2[1]', '$opt2[2]', '$opt2[3]', '$opt2[4]', '$opt2[5]');\r\n");
		fwrite($file, "\$opt3\t= array('$opt3[0]', '$opt3[1]', '$opt3[2]', '$opt3[3]', '$opt3[4]', '$opt3[5]', '$opt3[6]');\r\n");
		fwrite($file, "\$opt4\t= array('$opt4[0]', '$opt4[1]', '$opt4[2]', '$opt4[3]', '$opt4[4]');\r\n");

		fwrite($file, "\$count_1\t= '$c_1';\r\n");
		fwrite($file, "\$count_2\t= '$c_2';\r\n");
		fwrite($file, "\$count_3\t= '$c_3';\r\n");
		fwrite($file, "\$count_4\t= '$c_4';\r\n");


		fwrite($file, "\$a1\t= array('$a1[0]', '$a1[1]', '$a1[2]', '$a1[3]', '$a1[4]', '$a1[5]', '$a1[6]');\r\n");
		fwrite($file, "\$a2\t= array('$a2[0]', '$a2[1]', '$a2[2]', '$a2[3]', '$a2[4]', '$a2[5]', '$a2[6]');\r\n");
		fwrite($file, "\$a3\t= array('$a3[0]', '$a3[1]', '$a3[2]', '$a3[3]', '$a3[4]', '$a3[5]', '$a3[6]');\r\n");

		fwrite($file, "\$count_a1\t= '$ca_1';\r\n");
		fwrite($file, "\$count_a2\t= '$ca_2';\r\n");
		fwrite($file, "\$count_a3\t= '$ca_3';\r\n");

		fwrite($file, "\$it1\t= array('1'=>'$it1[0]', '2'=>'$it1[1]', '3'=>'$it1[2]', '4'=>'$it1[3]');\r\n");
		fwrite($file, "\$it2\t= array('1'=>'$it2[0]', '2'=>'$it2[1]', '3'=>'$it2[2]', '4'=>'$it2[3]');\r\n");
		fwrite($file, "\$it21\t= array('$it21[0]','$it21[1]','$it21[2]','$it21[3]','$it21[4]');\r\n");
		fwrite($file, "\$it22\t= array('$it22[0]','$it22[1]','$it22[2]','$it22[3]','$it22[4]');\r\n");
		fwrite($file, "\$it23\t= array('$it23[0]','$it23[1]','$it23[2]','$it23[3]','$it23[4]');\r\n");
		fwrite($file, "\$it24\t= array('$it24[0]','$it24[1]','$it24[2]','$it24[3]','$it24[4]');\r\n");


		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");
		
		fclose($file);
		
		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}
	
}

if (file_exists('config.php')) {
	include('config.php');
}

include("../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Matriculación de alumnos <small>Preferencias</small></h2>
	</div>
	
	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>
	
	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>

		<div class="well">
			<form class="form-horizontal" method="post" action="preferencias.php">
									
					<fieldset>
						<h3>Fechas de Matriculación para los alumnos del Centro<br></h3>
						
						<div class="form-group">							
							<label for="prefInicio" class="col-sm-3 control-label">Fecha de inicio de Matriculación</label>
							<div class="col-sm-3" id="datetimepicker1">
							<div class="input-group">
							<input name="prefInicio" type="text"
								class="form-control" value="<?php echo $config['matriculas']['fecha_inicio']; ?>" data-date-format="YYYY-MM-DD" id="prefInicio"> 
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						</div>

						<div class="form-group">
							<label for="prefFin" class="col-sm-3 control-label">Fecha de Fin de Matriculación</label>
							<div class="col-sm-3" id="datetimepicker2">
							<div class="input-group">
							<input name="prefFin" type="text"
								class="form-control" value="<?php echo $config['matriculas']['fecha_fin']; ?>" data-date-format="YYYY-MM-DD" id="prefFin"> 
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							</div>
						</div>
					
					<hr>

					<h3>Optativas, Refuerzos, Ampliaciones, Itinerarios<br></h3>
					<br>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab">1º de ESO</a></li>
						<li><a href="#tab2" data-toggle="tab">2º de ESO</a></li>
						<li><a href="#tab3" data-toggle="tab">3º de ESO</a></li>
						<li><a href="#tab4" data-toggle="tab">4º de ESO</a></li>
						<li><a href="#tab5" data-toggle="tab">1º Bachillerato</a></li>
						<li><a href="#tab6" data-toggle="tab">2º Bachillerato</a></li>
					</ul>

					<div class="tab-content" style="padding-bottom: 9px;">

					<div class="tab-pane fade in active" id="tab1">
						
						<h3 class="text-info">1º de E.S.O.</h3>
						<br>
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt1_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt1_0" type="text"
								class="form-control" value="<?php echo (isset($opt1[0])) ? $opt1[0] : 'Alemán 2º Idioma'; ?>" id="opt1_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt1_1" type="text"
								class="form-control" value="<?php echo (isset($opt1[1])) ? $opt1[1] : 'Cambios Sociales y Género'; ?>" id="opt1_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt1_2" type="text"
								class="form-control" value="<?php echo (isset($opt1[2])) ? $opt1[2] : 'Francés 2º Idioma'; ?>" id="opt1_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_3" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt1_3" type="text"
								class="form-control" value="<?php echo (isset($opt1[3])) ? $opt1[3] : 'Tecnología Aplicada'; ?>" id="opt1_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_4" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt1_4" type="text"
								class="form-control" value="<?php echo (isset($opt1[4])) ? $opt1[4] : ''; ?>" id="opt1_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt1_5" class="col-sm-4 control-label">Optativa 6</label>
							<div class="input-group col-sm-5">
							<input name="opt1_5" type="text"
								class="form-control" value="<?php echo (isset($opt1[5])) ? $opt1[5] : ''; ?>" id="opt1_5"> 
							</div>
						</div>
								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a1_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a1_0" type="text"
								class="form-control" value="<?php echo (isset($a1[0])) ? $a1[0] : 'Actividades de refuerzo de Lengua Castellana'; ?>" id="a1_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a1_1" type="text"
								class="form-control" value="<?php echo (isset($a1[1])) ? $a1[1] : 'Actividades de refuerzo de Matemáticas'; ?>" id="a1_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a1_2" type="text"
								class="form-control" value="<?php echo (isset($a1[2])) ? $a1[2] : 'Actividades de refuerzo de Inglés'; ?>" id="a1_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a1_3" type="text"
								class="form-control" value="<?php echo (isset($a1[3])) ? $a1[3] : 'Ampliación: Taller T.I.C.'; ?>" id="a1_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a1_4" type="text"
								class="form-control" value="<?php echo (isset($a1[4])) ? $a1[4] : 'Ampliación: Matemáticas Recreativas'; ?>" id="a1_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_5" class="col-sm-4 control-label">Actividad 6</label>
							<div class="input-group col-sm-5">
							<input name="a1_5" type="text"
								class="form-control" value="<?php echo (isset($a1[5])) ? $a1[5] : 'Ampliación: Taller de Teatro'; ?>" id="a1_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a1_6" class="col-sm-4 control-label">Actividad 7</label>
							<div class="input-group col-sm-5">
							<input name="a1_6" type="text"
								class="form-control" value="<?php echo (isset($a1[6])) ? $a1[6] : 'Ampliación: Taller de Lenguas Extranjeras'; ?>" id="a1_6"> 
							</div>
						</div>
								</td>
							</tr>
						</table>
					
					</div>

					<div class="tab-pane fade in" id="tab2">

					<h3 class="text-info">2º de E.S.O.</h3>
					<br>					
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt2_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt2_0" type="text"
								class="form-control" value="<?php echo (isset($opt2[0])) ? $opt2[0] : 'Alemán 2º Idioma'; ?>" id="opt2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt2_1" type="text"
								class="form-control" value="<?php echo (isset($opt2[1])) ? $opt2[1] : 'Cambios Sociales y Género'; ?>" id="opt2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt2_2" type="text"
								class="form-control" value="<?php echo (isset($opt2[2])) ? $opt2[2] : 'Francés 2º Idioma'; ?>" id="opt2_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_3" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt2_3" type="text"
								class="form-control" value="<?php echo (isset($opt2[3])) ? $opt2[3] : ''; ?>" id="opt2_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_4" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt2_4" type="text"
								class="form-control" value="<?php echo (isset($opt2[4])) ? $opt2[4] : ''; ?>" id="opt2_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt2_5" class="col-sm-4 control-label">Optativa 6</label>
							<div class="input-group col-sm-5">
							<input name="opt2_5" type="text"
								class="form-control" value="<?php echo (isset($opt2[5])) ? $opt2[5] : ''; ?>" id="opt2_5"> 
							</div>
						</div>
								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a2_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a2_0" type="text"
								class="form-control" value="<?php echo (isset($a2[0])) ? $a2[0] : 'Actividades de refuerzo de Lengua Castellana '; ?>" id="a2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a2_1" type="text"
								class="form-control" value="<?php echo (isset($a2[1])) ? $a2[1] : 'Actividades de refuerzo de Matemáticas'; ?>" id="a2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a2_2" type="text"
								class="form-control" value="<?php echo (isset($a2[2])) ? $a2[2] : 'Actividades de refuerzo de Inglés'; ?>" id="a2_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a2_3" type="text"
								class="form-control" value="<?php echo (isset($a2[3])) ? $a2[3] : 'Ampliación: Taller T.I.C. II'; ?>" id="a2_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a2_4" type="text"
								class="form-control" value="<?php echo (isset($a2[4])) ? $a2[4] : 'Ampliación: Taller de Teatro II'; ?>" id="a2_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a2_5" class="col-sm-4 control-label">Actividad 6</label>
							<div class="input-group col-sm-5">
							<input name="a2_5" type="text"
								class="form-control" value="<?php echo (isset($a2[5])) ? $a2[5] : ''; ?>" id="a2_5"> 
							</div>
						</div>
								</td>
							</tr>
						</table>
					</div>

					<div class="tab-pane fade in" id="tab3">
											
						<h3 class="text-info">3º de E.S.O.</h3>
						<br>
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt3_0" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt3_0" type="text"
								class="form-control" value="<?php echo (isset($opt3[0])) ? $opt3[0] : 'Alemán 2º Idioma'; ?>" id="opt3_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_1" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt3_1" type="text"
								class="form-control" value="<?php echo (isset($opt3[1])) ? $opt3[1] : 'Cambios Sociales y Género'; ?>" id="opt3_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_2" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt3_2" type="text"
								class="form-control" value="<?php echo (isset($opt3[2])) ? $opt3[2] : 'Francés 2º Idioma'; ?>" id="opt3_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_3" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt3_3" type="text"
								class="form-control" value="<?php echo (isset($opt3[3])) ? $opt3[3] : 'Cultura Clásica'; ?>" id="opt3_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_4" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt3_4" type="text"
								class="form-control" value="<?php echo (isset($opt3[4])) ? $opt3[4] : 'Taller T.I.C. III'; ?>" id="opt3_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_5" class="col-sm-4 control-label">Optativa 6</label>
							<div class="input-group col-sm-5">
							<input name="opt3_5" type="text"
								class="form-control" value="<?php echo (isset($opt3[5])) ? $opt3[5] : 'Taller de Cerámica'; ?>" id="opt3_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt3_6" class="col-sm-4 control-label">Optativa 7</label>
							<div class="input-group col-sm-5">
							<input name="opt3_6" type="text"
								class="form-control" value="<?php echo (isset($opt3[6])) ? $opt3[6] : 'Taller de Teatro'; ?>" id="opt3_6"> 
							</div>
						</div>

								</td>
								<td>
									<legend>Ampliaciones y Refuerzos</legend>
						<div class="form-group">
							<label for="a3_0" class="col-sm-4 control-label">Actividad 1</label>
							<div class="input-group col-sm-5">
							<input name="a3_0" type="text"
								class="form-control" value="<?php echo (isset($a3[0])) ? $a3[0] : 'Actividades de refuerzo de Lengua Castellana '; ?>" id="a3_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_1" class="col-sm-4 control-label">Actividad 2</label>
							<div class="input-group col-sm-5">
							<input name="a3_1" type="text"
								class="form-control" value="<?php echo (isset($a3[1])) ? $a3[1] : 'Actividades de refuerzo de Matemáticas'; ?>" id="a3_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_2" class="col-sm-4 control-label">Actividad 3</label>
							<div class="input-group col-sm-5">
							<input name="a3_2" type="text"
								class="form-control" value="<?php echo (isset($a3[2])) ? $a3[2] : 'Actividades de refuerzo de Inglés'; ?>" id="a3_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_3" class="col-sm-4 control-label">Actividad 4</label>
							<div class="input-group col-sm-5">
							<input name="a3_3" type="text"
								class="form-control" value="<?php echo (isset($a3[3])) ? $a3[3] : 'Ampliación: Lengua'; ?>" id="a3_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_4" class="col-sm-4 control-label">Actividad 5</label>
							<div class="input-group col-sm-5">
							<input name="a3_4" type="text"
								class="form-control" value="<?php echo (isset($a3[4])) ? $a3[4] : 'Ampliación: Matemáticas'; ?>" id="a3_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_5" class="col-sm-4 control-label">Actividad 6</label>
							<div class="input-group col-sm-5">
							<input name="a3_5" type="text"
								class="form-control" value="<?php echo (isset($a3[5])) ? $a3[5] : 'Ampliación: Inglés'; ?>" id="a3_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="a3_6" class="col-sm-4 control-label">Actividad 7</label>
							<div class="input-group col-sm-5">
							<input name="a3_6" type="text"
								class="form-control" value="<?php echo (isset($a3[6])) ? $a3[6] : ''; ?>" id="a3_6"> 
							</div>
						</div>
								</td>
							</tr>
						</table>

					</div>

					<div class="tab-pane fade in" id="tab4">

						<h3 class="text-info">4º de E.S.O.</h3>
						<br>
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td>
									<legend>Asignaturas Optativas</legend>
						<div class="form-group">
							<label for="opt4_0" class="col-sm-2 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt4_0" type="text"
								class="form-control" value="<?php echo (isset($opt4[0])) ? $opt4[0] : 'Alemán 2º Idioma'; ?>" id="opt4_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_1" class="col-sm-2 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt4_1" type="text"
								class="form-control" value="<?php echo (isset($opt4[1])) ? $opt4[1] : 'Francés 2º Idioma'; ?>" id="opt4_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_2" class="col-sm-2 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt4_2" type="text"
								class="form-control" value="<?php echo (isset($opt4[2])) ? $opt4[2] : 'TIC'; ?>" id="opt4_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_3" class="col-sm-2 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt4_3" type="text"
								class="form-control" value="<?php echo (isset($opt4[3])) ? $opt4[3] : 'Educación Plástica Visual'; ?>" id="opt4_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt4_4" class="col-sm-2 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="opt4_4" type="text"
								class="form-control" value="<?php echo (isset($opt4[4])) ? $opt4[4] : 'Música'; ?>" id="opt4_4"> 
							</div>
						</div>
								</td>			
							</tr>
							<tr>
								<td>
									
						<legend>Itinerarios 4º ESO</legend>

						<table class='table' style="background-color:transparent;">
						<tr>
							<td>
								<legend>Itinerario 1</legend>
						<div class="form-group">
							<label for="it41_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it41_0" type="text"
								class="form-control" value="<?php echo (isset($it41[0])) ? $it41[0] : '(Bachillerato de Ciencias)'; ?>" id="it41_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it41_1" type="text"
								class="form-control" value="<?php echo (isset($it41[1])) ? $it41[1] : 'Matemáticas Académicas'; ?>" id="it41_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it41_2" type="text"
								class="form-control" value="<?php echo (isset($it41[2])) ? $it41[2] : 'Tecnología (Sólo Ingeniería y Arquitectura)'; ?>" id="it41_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it41_3" type="text"
								class="form-control" value="<?php echo (isset($it41[3])) ? $it41[3] : 'Física y Química'; ?>" id="it41_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_4" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="it41_4" type="text"
								class="form-control" value="<?php echo (isset($it41[4])) ? $it41[4] : 'Biología y Geología'; ?>" id="it41_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it41_5" class="col-sm-4 control-label">Optativa 5</label>
							<div class="input-group col-sm-5">
							<input name="it41_5" type="text"
								class="form-control" value="<?php echo (isset($it41[5])) ? $it41[5] : 'Economía'; ?>" id="it41_5"> 
							</div>
						</div>
								</td>

								<td>
						<legend>Itinerario 2</legend>
						<div class="form-group">
							<label for="it42_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it42_0" type="text"
								class="form-control" value="<?php echo (isset($it42[0])) ? $it42[0] : '(Bachillerato de Humanidades y Ciencias Sociales)'; ?>" id="it42_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it42_1" type="text"
								class="form-control" value="<?php echo (isset($it42[1])) ? $it42[1] : 'Matemáticas Académicas'; ?>" id="it42_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it42_2" type="text"
								class="form-control" value="<?php echo (isset($it42[2])) ? $it42[2] : 'Latín'; ?>" id="it42_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it42_3" type="text"
								class="form-control" value="<?php echo (isset($it42[3])) ? $it42[3] : 'Economía'; ?>" id="it42_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it42_4" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="it42_4" type="text"
								class="form-control" value="<?php echo (isset($it42[4])) ? $it42[4] : ''; ?>" id="it42_4"> 
							</div>
						</div>
								</td>	

								<td>
						<legend>Itinerario 3</legend>
						<div class="form-group">
							<label for="it43_0" class="col-sm-4 control-label">Descripción</label>
							<div class="input-group col-sm-5">
							<input name="it43_0" type="text"
								class="form-control" value="<?php echo (isset($it43[0])) ? $it43[0] : '(Ciclos Formativos y Mundo Laboral)'; ?>" id="it43_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_1" class="col-sm-4 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it43_1" type="text"
								class="form-control" value="<?php echo (isset($it43[1])) ? $it43[1] : 'Matemáticas Aplicadas'; ?>" id="it43_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_2" class="col-sm-4 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it43_2" type="text"
								class="form-control" value="<?php echo (isset($it43[2])) ? $it43[2] : 'Tecnología'; ?>" id="it43_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_3" class="col-sm-4 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it43_3" type="text"
								class="form-control" value="<?php echo (isset($it43[3])) ? $it43[3] : 'Ciencias Aplicadas a la Actividad Profesional'; ?>" id="it43_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it43_4" class="col-sm-4 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="it43_4" type="text"
								class="form-control" value="<?php echo (isset($it43[4])) ? $it43[4] : 'Iniciación a la Actividad Emprendedora y Empresarial'; ?>" id="it43_4"> 
							</div>
						</div>
								</td>		
							</tr>
						</table>
						

						</td>
						</tr>
						</table>	
						</div>

						<div class="tab-pane fade in" id="tab5">
						
						<h3 class="text-info">1º de Bachillerato</h3>
						<br>
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td colspan=3>
									<legend>Itinerarios</legend>
						<div class="form-group">
							<label for="it1_0" class="col-sm-2 control-label">Itinerario 1</label>
							<div class="input-group col-sm-5">
							<input name="it1_0" type="text"
								class="form-control" value="<?php echo (isset($it1[1])) ? $it1[1] : 'Ciencias e Ingeniería y Arquitectura'; ?>" id="it1_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it1_1" class="col-sm-2 control-label">Itinerario 2</label>
							<div class="input-group col-sm-5">
							<input name="it1_1" type="text"
								class="form-control" value="<?php echo (isset($it1[2])) ? $it1[2] : 'Ciencias y Ciencias de la Salud'; ?>" id="it1_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it1_2" class="col-sm-2 control-label">Itinerario 3</label>
							<div class="input-group col-sm-5">
							<input name="it1_2" type="text"
								class="form-control" value="<?php echo (isset($it1[3])) ? $it1[3] : 'Humanidades'; ?>" id="it1_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it1_3" class="col-sm-2 control-label">Itinerario 4</label>
							<div class="input-group col-sm-5">
							<input name="it1_3" type="text"
								class="form-control" value="<?php echo (isset($it1[4])) ? $it1[4] : 'Ciencias Sociales y Jurídicas'; ?>" id="it1_3"> 
							</div>
						</div>
								</td>
							</tr>
							<tr>
								<td>
									<legend>Optativas de los distintos itinerarios</legend>
						<table class='table' style="background-color:transparent;">
						<tr>
							<td>
								<legend>Itinerario 1</legend>
						<div class="form-group">
							<label for="opt11_0" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt11_0" type="text"
								class="form-control" value="<?php echo (isset($opt11[0])) ? $opt11[0] : ''; ?>" id="opt11_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt11_1" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt11_1" type="text"
								class="form-control" value="<?php echo (isset($opt11[1])) ? $opt11[1] : ''; ?>" id="opt11_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt11_2" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt11_2" type="text"
								class="form-control" value="<?php echo (isset($opt11[2])) ? $opt11[2] : ''; ?>" id="opt11_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt11_3" class="col-sm-5 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt11_3" type="text"
								class="form-control" value="<?php echo (isset($opt11[3])) ? $opt11[3] : ''; ?>" id="opt11_3"> 
							</div>
						</div>
							</td>

								<td>
						<legend>Itinerario 2</legend>
						<div class="form-group">
							<label for="opt12_0" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt12_0" type="text"
								class="form-control" value="<?php echo (isset($opt12[0])) ? $opt12[0] : ''; ?>" id="opt12_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt12_1" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt12_1" type="text"
								class="form-control" value="<?php echo (isset($opt12[1])) ? $opt12[1] : ''; ?>" id="opt12_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt12_2" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt12_2" type="text"
								class="form-control" value="<?php echo (isset($opt12[2])) ? $opt12[2] : ''; ?>" id="opt12_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt12_3" class="col-sm-5 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt12_3" type="text"
								class="form-control" value="<?php echo (isset($opt12[3])) ? $opt12[3] : ''; ?>" id="opt12_3"> 
							</div>
						</div>
								</td>	

								<td>
						<legend>Itinerario 3</legend>
						<div class="form-group">
							<label for="opt13_0" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt13_0" type="text"
								class="form-control" value="<?php echo (isset($opt13[0])) ? $opt13[0] : ''; ?>" id="opt13_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt13_1" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt13_1" type="text"
								class="form-control" value="<?php echo (isset($opt13[1])) ? $opt13[1] : ''; ?>" id="opt13_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt13_2" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt13_2" type="text"
								class="form-control" value="<?php echo (isset($opt13[2])) ? $opt13[2] : ''; ?>" id="opt13_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt13_3" class="col-sm-5 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt13_3" type="text"
								class="form-control" value="<?php echo (isset($opt13[3])) ? $opt13[3] : ''; ?>" id="opt13_3"> 
							</div>
						</div>
								</td>
							<td>
						<legend>Itinerario 4</legend>
						<div class="form-group">
							<label for="opt14_0" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="opt14_0" type="text"
								class="form-control" value="<?php echo (isset($opt14[0])) ? $opt14[0] : ''; ?>" id="opt14_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt14_1" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="opt14_1" type="text"
								class="form-control" value="<?php echo (isset($opt14[1])) ? $opt14[1] : ''; ?>" id="opt14_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt14_2" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="opt14_2" type="text"
								class="form-control" value="<?php echo (isset($opt14[2])) ? $opt14[2] : ''; ?>" id="opt14_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt14_3" class="col-sm-5 control-label">Optativa 4</label>
							<div class="input-group col-sm-5">
							<input name="opt14_3" type="text"
								class="form-control" value="<?php echo (isset($opt14[3])) ? $opt14[3] : ''; ?>" id="opt14_3"> 
							</div>
						</div>
						</div>
								</td>			
							</tr>
						</table>
								</td>
							</tr>
						</table>
					
					</div>

					<div class="tab-pane fade in" id="tab6">
						
						<h3 class="text-info">2º de Bachillerato</h3>
						<br>
						<table class='table table-bordered' style='border:none;'>
							<tr>
								<td colspan=3 align=left>
									<legend>Itinerarios</legend>
						<div class="form-group">
							<label for="it2_0" class="col-sm-2 control-label">Itinerario 1</label>
							<div class="input-group col-sm-5">
							<input name="it2_0" type="text"
								class="form-control" value="<?php echo (isset($it2[1])) ? $it2[1] : 'Ciencias e Ingeniería y Arquitectura'; ?>" id="it2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it2_1" class="col-sm-2 control-label">Itinerario 2</label>
							<div class="input-group col-sm-5">
							<input name="it2_1" type="text"
								class="form-control" value="<?php echo (isset($it2[2])) ? $it2[2] : 'Ciencias y Ciencias de la Salud'; ?>" id="it2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it2_2" class="col-sm-2 control-label">Itinerario 3</label>
							<div class="input-group col-sm-5">
							<input name="it2_2" type="text"
								class="form-control" value="<?php echo (isset($it2[3])) ? $it2[3] : 'Humanidades'; ?>" id="it2_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it2_3" class="col-sm-2 control-label">Itinerario 4</label>
							<div class="input-group col-sm-5">
							<input name="it2_3" type="text"
								class="form-control" value="<?php echo (isset($it2[4])) ? $it2[4] : 'Ciencias Sociales y Jurídicas'; ?>" id="it2_3"> 
							</div>
						</div>
								</td>
							</tr>
							<tr>
								<td>
									<legend>Optativas de los distintos itinerarios</legend>
						<table class='table' style="background-color:transparent;">
						<tr>
							<td>
								<legend>Itinerario 1</legend>
						<div class="form-group">
							<label for="it21_0" class="col-sm-5 control-label">Nombre</label>
							<div class="input-group col-sm-5">
							<input name="it21_0" type="text"
								class="form-control" value="<?php echo (isset($it21[0])) ? $it21[0] : 'Bachillerato de Ciencias'; ?>" id="it21_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it21_1" class="col-sm-5 control-label">Modalidad</label>
							<div class="input-group col-sm-5">
							<input name="it21_1" type="text"
								class="form-control" value="<?php echo (isset($it21[1])) ? $it21[1] : 'Arquitectura e Ingeniería y Ciencias'; ?>" id="it21_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it21_2" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it21_2" type="text"
								class="form-control" value="<?php echo (isset($it21[2])) ? $it21[2] : 'Matemáticas II'; ?>" id="it21_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it21_3" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it21_3" type="text"
								class="form-control" value="<?php echo (isset($it21[3])) ? $it21[3] : 'Física'; ?>" id="it21_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it21_4" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it21_4" type="text"
								class="form-control" value="<?php echo (isset($it21[4])) ? $it21[4] : 'Dibujo Técnico II'; ?>" id="it21_4"> 
							</div>
						</div>
							</td>

								<td>
						<legend>Itinerario 2</legend>
						<div class="form-group">
							<label for="it22_0" class="col-sm-5 control-label">Nombre</label>
							<div class="input-group col-sm-5">
							<input name="it22_0" type="text"
								class="form-control" value="<?php echo (isset($it22[0])) ? $it22[0] : 'Bachillerato de Ciencias'; ?>" id="it22_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it22_1" class="col-sm-5 control-label">Modalidad</label>
							<div class="input-group col-sm-5">
							<input name="it22_1" type="text"
								class="form-control" value="<?php echo (isset($it22[1])) ? $it22[1] : 'Ciencias y Ciencias de la Salud'; ?>" id="it22_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it22_2" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it22_2" type="text"
								class="form-control" value="<?php echo (isset($it22[2])) ? $it22[2] : 'Matemáticas II'; ?>" id="it22_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it22_3" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it22_3" type="text"
								class="form-control" value="<?php echo (isset($it22[3])) ? $it22[3] : 'Química'; ?>" id="it22_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it22_4" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it22_4" type="text"
								class="form-control" value="<?php echo (isset($it22[4])) ? $it22[4] : 'Biología'; ?>" id="it22_4"> 
							</div>
						</div>
								</td>	

								<td>
						<legend>Itinerario 3</legend>
						<div class="form-group">
							<label for="it23_0" class="col-sm-5 control-label">Nombre</label>
							<div class="input-group col-sm-5">
							<input name="it23_0" type="text"
								class="form-control" value="<?php echo (isset($it23[0])) ? $it23[0] : 'Bachillerato de Humanidades'; ?>" id="it23_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it23_1" class="col-sm-5 control-label">Modalidad</label>
							<div class="input-group col-sm-5">
							<input name="it23_1" type="text"
								class="form-control" value="<?php echo (isset($it23[1])) ? $it23[1] : 'Humanidades'; ?>" id="it23_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it23_2" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it23_2" type="text"
								class="form-control" value="<?php echo (isset($it23[2])) ? $it23[2] : 'Latín II'; ?>" id="it23_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it23_3" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it23_3" type="text"
								class="form-control" value="<?php echo (isset($it23[3])) ? $it23[3] : 'Historia del Arte'; ?>" id="it23_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it23_4" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it23_4" type="text"
								class="form-control" value="<?php echo (isset($it23[4])) ? $it23[4] : 'Griego II'; ?>" id="it23_4"> 
							</div>
						</div>
								</td>
							<td>
						<legend>Itinerario 4</legend>
						<div class="form-group">
							<label for="it24_0" class="col-sm-5 control-label">Nombre</label>
							<div class="input-group col-sm-5">
							<input name="it24_0" type="text"
								class="form-control" value="<?php echo (isset($it24[5])) ? $it24[5] : 'Bachillerato de Ciencias Sociales'; ?>" id="it24_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it24_1" class="col-sm-5 control-label">Modalidad</label>
							<div class="input-group col-sm-5">
							<input name="it24_1" type="text"
								class="form-control" value="<?php echo (isset($it24[1])) ? $it24[1] : 'Ciencias Sociales y Jurídicas'; ?>" id="it24_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it24_2" class="col-sm-5 control-label">Optativa 1</label>
							<div class="input-group col-sm-5">
							<input name="it24_2" type="text"
								class="form-control" value="<?php echo (isset($it24[2])) ? $it24[2] : 'Matemáticas de las Ciencias Sociales II'; ?>" id="it24_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it24_3" class="col-sm-5 control-label">Optativa 2</label>
							<div class="input-group col-sm-5">
							<input name="it24_3" type="text"
								class="form-control" value="<?php echo (isset($it24[3])) ? $it24[3] : 'Geografía'; ?>" id="it24_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="it24_4" class="col-sm-5 control-label">Optativa 3</label>
							<div class="input-group col-sm-5">
							<input name="it24_4" type="text"
								class="form-control" value="<?php echo (isset($it24[4])) ? $it24[4] : 'Economía'; ?>" id="it24_4"> 
							</div>
						</div>
						
								</td>			
							</tr>
							<tr>
								<td colspan=3>
								<legend>Optativas de 2 horas de 2º de Bachillerato</legend>	
						<div class="form-group">
							<label for="opt_aut2_0" class="col-sm-3 control-label">Optativa de 2 horas 1</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_0" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[0])) ? $opt_aut2[0] : 'Educación Física'; ?>" id="opt_aut2_0"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_1" class="col-sm-3 control-label">Optativa de 2 horas 2</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_1" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[1])) ? $opt_aut2[1] : 'Estadística'; ?>" id="opt_aut2_1"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_2" class="col-sm-3 control-label">Optativa de 2 horas 3</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_2" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[2])) ? $opt_aut2[2] : 'Introducción Ciencias de la Salud'; ?>" id="opt_aut2_2"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_3" class="col-sm-3 control-label">Optativa de 2 horas 4</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_3" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[3])) ? $opt_aut2[3] : 'Electrotecnia'; ?>" id="opt_aut2_3"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_4" class="col-sm-3 control-label">Optativa de 2 horas 5</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_4" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[4])) ? $opt_aut2[4] : 'Alemán 2º Idioma'; ?>" id="opt_aut2_4"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_5" class="col-sm-3 control-label">Optativa de 2 horas 6</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_5" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[5])) ? $opt_aut2[5] : 'Francés 2º Idioma'; ?>" id="opt_aut2_5"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_6" class="col-sm-3 control-label">Optativa de 2 horas 7</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_6" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[6])) ? $opt_aut2[6] : 'Inglés 2º Idioma'; ?>" id="opt_aut2_6"> 
							</div>
						</div>
						<div class="form-group">
							<label for="opt_aut2_7" class="col-sm-3 control-label">Optativa de 2 horas 8</label>
							<div class="input-group col-sm-5">
							<input name="opt_aut2_7" type="text"
								class="form-control" value="<?php echo (isset($opt_aut2[7])) ? $opt_aut2[0] : ''; ?>" id="opt_aut2_7"> 
							</div>
						</div>
								</td>
							</tr>
						</table>
								</td>
							</tr>
						</table>
					
					</div>

					</div>
				</fieldset>
				
				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>
			
			</form>
		</div>	
	</div>

<?php include("../../pie.php"); ?>
<script>  
$(function ()  
{ 
	$('#datetimepicker1').datetimepicker({
		language: 'es',
		pickTime: false
	});
	
	$('#datetimepicker2').datetimepicker({
		language: 'es',
		pickTime: false
	});
});  
</script>
</body>
</html>
