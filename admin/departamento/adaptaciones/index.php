<?php
require('../../../bootstrap.php');

if (isset($_POST['departamento'])) {
		$departamento = mysqli_real_escape_string($db_con, $_POST['departamento']);
		$titulo = 'Departamento de '.$departamento;
}
else {
	$departamento = $dpto;
	$titulo = 'Departamento de '.$departamento;
}
	$profesor = mysqli_real_escape_string($db_con, $_SESSION['profi']);
	
	if (isset($_POST['id_acta'])) { $id_acta = $_POST['id_acta'];} elseif (isset($_GET['id_acta'])) { $id_acta = $_GET['id_acta'];} else{ $id_acta = "";}
	if (isset($_POST['grupo'])) { $grupo = mysqli_real_escape_string($db_con, $_POST['grupo']);} else{ $grupo = '';}
	if (isset($_POST['materia_codigo'])) { $materia_codigo = mysqli_real_escape_string($db_con,$_POST['materia_codigo']);} else{ $materia_codigo = '';}
	if (isset($_POST['alumno_claveal'])) { $alumno_claveal = mysqli_real_escape_string($db_con, $_POST['alumno_claveal']);} else{ $alumno_claveal = '';}
	if (isset($_POST['curso'])) { $curso = mysqli_real_escape_string($db_con, $_POST['curso']);} else{ $curso="";}
	if (isset($_POST['texto_acta'])) { $texto_acta = mysqli_real_escape_string($db_con, $_POST['texto_acta']);} else{ $texto_acta="";}

	if (!empty($materia_codigo)) {
		$tr_materia = explode(";", $materia_codigo);
		$materia = $tr_materia[0];
		$codigo = $tr_materia[1];
	}
	if (!empty($alumno_claveal)) {
		$tr_alumno = explode(";", $alumno_claveal);
		$alumno = $tr_alumno[0];
		$claveal = $tr_alumno[1];
		$apel_nombre = explode(", ", $alumno);
		$nombre_alumno = $apel_nombre[1]." ".$apel_nombre[0];
	}

	$fecha_hoy = date('Y-m-d');	

	$registrado = "";

// REGISTRAMOS EL ACTA

if (isset($_POST['guardar'])) {
	if (!empty($alumno) and !empty($curso) and !empty($materia) and !empty($grupo) and !empty($texto_acta)) {
		$result = mysqli_query($db_con, "INSERT INTO adaptaciones (alumno, unidad, materia, departamento, profesor, texto, fecha, curso) VALUES ('$claveal', '$grupo', '$materia', '$departamento', '$profesor', '$texto_acta', '$fecha_hoy', '$curso')");	
		if (! $result) $msg_error = "Ha ocurrido un error al registrar el acta. Error: ".mysqli_error($db_con);
		else $msg_success = "El documento ha sido registrado correctamente";
		$ya_hay = mysqli_query($db_con,"select * from adaptaciones where profesor = '$profesor' and alumno = '$claveal' and materia = '$materia'");
		if (mysqli_num_rows($ya_hay)) { 
			$ya_texto = mysqli_fetch_array($ya_hay);
			$texto_acta = $ya_texto['texto'];
			$id_acta = $ya_texto['id'];
			$registrado = 1;
		}
		else{
			$id_acta="";
		}
	}
	else{
		echo "Error de inserción";
	}
}

// ACTUALIZAMOS EL ACTA
if (isset($_POST['actualizar'])) {
	$result = mysqli_query($db_con, "UPDATE adaptaciones SET texto = '$texto_acta', fecha = '$fecha_hoy' WHERE id = $id_acta");
	//echo "UPDATE adaptaciones SET texto = '$texto_acta', fecha = '$fecha_hoy' WHERE id = $id_acta";
	if (! $result) $msg_error = "Ha ocurrido un error al actualizar el documento. Error: ".mysqli_error($db_con);
	else $msg_success = "El documento ha sido actualizado correctamente";
}

// ELIMINAR ACTA
if (isset($_GET['eliminar_id'])) {

	$eliminar_id = mysqli_real_escape_string($db_con, $_GET['eliminar_id']);

	$result = mysqli_query($db_con, "DELETE FROM adaptaciones WHERE id = $eliminar_id");
	if (! $result) $msg_error = "Ha ocurrido un error al eliminar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El documento ha sido eliminado correctamente.";
}

// Datos de los alumnos
if (isset($_POST['alumno_claveal'])) {
		$grupo = mysqli_real_escape_string($db_con, $_POST['grupo']);
		
		$materia_codigo = mysqli_real_escape_string($db_con, $_POST['materia_codigo']);
		$tr_materia = explode(";", $materia_codigo);
		$materia = $tr_materia[0];
		$codigo = $tr_materia[1];

		$alumno_claveal = mysqli_real_escape_string($db_con, $_POST['alumno_claveal']);
		$tr_alumno = explode(";", $alumno_claveal);
		$alumno = $tr_alumno[0];
		$claveal = $tr_alumno[1];
		$apel_nombre = explode(", ", $alumno);
		$nombre_alumno = $apel_nombre[1]." ".$apel_nombre[0];

		$fecha = $_POST['fecha'];

		$curso = $_POST['curso'];

		$ya_hay = mysqli_query($db_con,"select * from adaptaciones where profesor = '$profesor' and alumno = '$claveal' and materia = '$materia'");
		if (mysqli_num_rows($ya_hay)) { 
			$ya_texto = mysqli_fetch_array($ya_hay);
			$texto_acta = $ya_texto['texto'];
			$id_acta = $ya_texto['id'];
			$registrado = 1;
		}
		else{
			$id_acta="";
		}
	}

// URI módulo
$uri = 'index.php';

include ("../../../menu.php");
include ("menu.php");


$profesor = $_SESSION['profi'];
?>
<div class="container">

	<form method="post" action="index.php">

		<div class="page-header">
			<h2>Adaptaciones curriculares <small>Registrar adaptación</small></h2>

			<h3><?php echo $departamento; ?></h3>

		</div><!-- /.page-header -->

		<?php if (isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<?php if (isset($msg_alerta)): ?>
		<div class="alert alert-warning">
			<?php echo $msg_alerta; ?>
		</div>
		<?php endif; ?>

		<?php if (isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>

		<div class="row">

			<div class="col-md-8">

			<?php
			// RECOLECTAMOS DATOS PARA RELLENAR EL ACTA
			if ( isset($_GET['edit_id'])) {
				$id_acta = $_GET['edit_id'];
				$result = mysqli_query($db_con, "SELECT id, alumno, unidad, materia, departamento, profesor, texto, fecha, curso FROM adaptaciones WHERE id = '$id_acta'");
				while ( $row = mysqli_fetch_row($result)) {							
					$alumno = $row[1];
					$al = mysqli_query($db_con, "select apellidos, nombre from alma where claveal = '$alumno'");
					$alum = mysqli_fetch_array($al);
					$alumno_claveal = "$alum[0], $alum[1];$alumno";
					$nombre_alumno = $alum[1]." ".$alum[0];
					$grupo = $row[2];
					$materia = $row[3];
					$cod = mysqli_query($db_con, "select codigo from asignaturas where nombre = '$materia' and abrev not like '%\_%' limit 1");
					$codi = mysqli_fetch_array($cod);
					$codigo = $codi[0];
					$materia_codigo = "$materia;$codigo";
					$departamento = $row[4];
					$profeor = $row[5];
					$texto = $row[6];
					$fecha = $row[7];
					$curso = $row[8];
					$texto_acta = $row[6];
					$registrado=1;
				}
			}
			?>

			<legend class="text-muted">Nueva adaptación curricular</legend>
					
				<div class="well">
			
					<fieldset>

						<div class="row">

							<div class="col-sm-2">

								<select class="form-control text-info" id="grupo" name="grupo" onchange="submit()">
									<option value=""></option>
										<?php $result = mysqli_query($db_con, "SELECT DISTINCT grupo FROM profesores WHERE profesor like '$profesor' ORDER BY grupo ASC"); ?>
										<?php while ($row = mysqli_fetch_array($result)): 
											if (!isset($grupo)) { $grupo = $row['grupo'];}
										?>
										<option value="<?php echo $row['grupo']; ?>"<?php echo (isset($grupo) && $grupo == $row['grupo']) ? ' selected' : ''; ?>><?php echo $row['grupo']; ?></option>
										<?php endwhile; ?>
								</select>

							</div><!-- /.col-sm-4 -->


							<div class="col-sm-5">

								<select class="form-control text-info" id="materia_codigo" name="materia_codigo" onchange="submit()">
									<option value=""></option>
										<?php $result = mysqli_query($db_con, "SELECT distinct materia, nivel FROM profesores WHERE profesor like '$profesor' and grupo = '$grupo'"); ?>
										<?php while ($row = mysqli_fetch_array($result)): 
											$curso = $row[1];
										?>
										<?php $result1 = mysqli_query($db_con, "SELECT distinct codigo FROM asignaturas WHERE nombre like '$row[0]' and curso = '$row[1]' and abrev not like '%\_%' limit 1"); 
											$cod = mysqli_fetch_row($result1);
											$codigo = $cod[0];
										?>	
										<option value="<?php echo $row['materia'].";".$codigo; ?>"<?php echo (isset($materia_codigo) && $materia_codigo == $row['materia'].";".$codigo) ? ' selected' : ''; ?>><?php echo $row['materia']." - ".$curso; ?></option>
										<?php endwhile; ?>
								</select>
								<input type="hidden" name="curso" value="<?php echo $curso; ?>">
							</div><!-- /.col-sm-4 -->

							<div class="col-sm-5">
								<select class="form-control text-info" id="alumno_claveal" name="alumno_claveal" onchange="submit()">
									<option value=""></option>
									<optgroup label="Alumnos del grupo">
										<?php $result = mysqli_query($db_con, "SELECT CONCAT(apellidos, ',',' ', nombre) as alumno, claveal FROM alma WHERE unidad like '$grupo' and combasi like '%$codigo%' ORDER BY apellidos, nombre ASC"); ?>
										<?php while ($row = mysqli_fetch_array($result)):?>
										<option value="<?php echo $row['alumno'].";".$row['claveal']; ?>"<?php echo (isset($alumno_claveal) && $alumno_claveal == $row['alumno'].";".$row['claveal']) ? ' selected' : ''; ?>><?php echo $row['alumno']; ?></option>
										<?php endwhile; ?>
									</optgroup>
								</select>

							</div><!-- /.col-sm-4 -->

						</div><!-- /.row -->

<?php
$html_textarea = '
<p>ADAPTACI&Oacute;N CURRICULAR NO SIGNIFICATIVA</p>
<p>PROPUESTA CURRICULAR</p>
<P style="display:inline;">ALUMNO/A:&nbsp;</P><h3 style="display:inline;"> '.mb_strtoupper($nombre_alumno).'</h3>
<p>&nbsp;</p>
<table style="border-collapse: collapse;" border="1" cellspacing="0" cellpadding="0"><colgroup><col width="134" /><col width="609" /></colgroup>
<tbody>
<tr>
<td style="width: 3.069cm; background-color: #eee; text-align: left;">
<p><b>ASIGNATURA</b></p>
</td>
<td style="text-align: left; width: 13.929cm;">
'.$materia.'
</td>
</tr>
<tr>
<td style="width: 3.069cm; background-color: #eee; text-align: left;">
<p><b>CURSO</b></p>
</td>
<td style="text-align: left; width: 13.929cm;">';

if (!empty($grupo) and !empty($curso)) {
	$grupo_curso = '<b>'.$grupo.'</b> (<em>'.$curso.'</em>)';
}
else{
	$grupo_curso = "";
}
$html_textarea .= $grupo_curso.'</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<hr>
<p>&nbsp;</p>

<table style="border-collapse: collapse;" border="1" cellspacing="0" cellpadding="0"><colgroup><col width="743" /></colgroup>
<tbody>
<tr>
<td style="width: 16.999cm; background-color: #eee; text-align: left;">
<h4>&nbsp;Contenidos: (Adaptaciones en la organizaci&oacute;n: priorizaci&oacute;n, secuenciaci&oacute;n, presentaci&oacute;n...) &nbsp;</h4>
</td>
</tr>
</tbody>
</table>

<br>
<p>Adaptaciones en la organizaci&oacute;n, priorizaci&oacute;n y secuenciaci&oacute;n de contenidos:</p>
<p style="padding-left: 40px;">En este apartado se recomienda que cada Departamento establezca los contenidos m&iacute;nimos que el alumno/a debe adquirir para superar la asignatura en el curso en que est&aacute; escolarizado/a.</p>
<p>Adaptaciones en la presentaci&oacute;n de los contenidos:</p>
<ul>
<li >Las metodolog&iacute;as r&iacute;gidas y de car&aacute;cter transmisivo son menos recomendables para lograr una adecuada atenci&oacute;n a la diversidad en el aula, siendo, por el contrario, m&aacute;s adecuados los m&eacute;todos basados en el descubrimiento y en el papel activo del alumno.</li>
<li >Siempre que sea posible, ser&iacute;a conveniente utilizar esas metodolog&iacute;as favorecedoras de la inclusi&oacute;n, entre las que cabe destacar el aprendizaje basado en proyectos y el aprendizaje cooperativo.</li>
<li>El criterio fundamental para un buen desarrollo del proceso de ense&ntilde;anza-aprendizaje con este alumno/a es la atenci&oacute;n individualizada.</li>
<li>Se trabajar&aacute; con una metodolog&iacute;a participativa, partiendo de sus conocimientos previos, y de sus intereses y motivaciones.</li>
<li>Se utilizar&aacute;n juegos y actividades l&uacute;dicas siempre que sea posible, as&iacute; como la utilizaci&oacute;n de recursos inform&aacute;ticos que motiven al alumno/a.</li>
<li>Se favorecer&aacute; el uso de la agenda como medio para organizar el trabajo.</li>
<li>Se potenciar&aacute; la v&iacute;a visual del aprendizaje. Empleo de apoyos visuales en cualquier proceso de ense&ntilde;anza: listas, esquemas, pictogramas, que faciliten su comprensi&oacute;n.</li>
<li>Se asegurar&aacute; un ambiente estable y predecible, evitando cambios inesperados en la medida de lo posible.</li>
<li>Se favorecer&aacute; la generalizaci&oacute;n de los aprendizajes a distintas situaciones.</li>
<li>Se proporcionar&aacute;n las ayudas necesarias para garantizar el &eacute;xito en las tareas y reforzar mucho sus logros, principalmente con reforzadores sociales y afectivos.</li>
<li>Se descompondr&aacute;n las tareas en pasos m&aacute;s peque&ntilde;os.</li>
<li>Se ofrecer&aacute;n oportunidades de hacer elecciones; con ello se ir&aacute; mejorando la capacidad para la toma de decisiones.</li>
<li>Se incluir&aacute;n temas de su inter&eacute;s para motivar el aprendizaje de nuevos contenidos.</li>
</ul>

<p>&nbsp;</p>

<table style="border-collapse: collapse;" border="1" cellspacing="0" cellpadding="0"><colgroup><col width="743" /></colgroup>
<tbody>
<tr>
<td style="width: 16.999cm; background-color: #eee; text-align: left;">
<h4>&nbsp;Tipos de actividades y tareas: (comunes, de refuerzo, adaptadas, espec&iacute;ficas) &nbsp;</h4>
</td>
</tr>
</tbody>
</table>
<br>

<p>Actividades tipo:</p>
<ul>
<li>Con el objetivo de hacer frente a su ritmo lento, se hace necesario seleccionar aquellas actividades tipo o fundamentales para el profesor/a.</li>
<li>Se le proporcionar&aacute;n actividades similares a las que se realizan en su grupo-clase, pero adaptadas a su nivel.</li>
<li>Priorizaremos las actividades pr&aacute;cticas y manipulativas.</li>
<li>Realizaci&oacute;n de su cuaderno de actividades adaptadas.</li>
<li>Realizaci&oacute;n de actividades en el ordenador en las que se trabajen los contenidos de la adaptaci&oacute;n.</li>
</ul>

<p>&nbsp;</p>

<table style="border-collapse: collapse;" border="1" cellspacing="0" cellpadding="0"><colgroup><col width="743" /></colgroup>
<tbody>
<tr>
<td style="width: 728.219px; height: 53px; background-color: #eee; text-align: left;">
<h4>&nbsp;Recursos did&aacute;cticos, agrupamientos, distribuci&oacute;n de espacios y tiempos: &nbsp;</h4>
</td>
</tr>
</tbody>
</table>
<br>

<ul>
<li>Materiales did&aacute;cticos: Realizaci&oacute;n de un cuaderno de actividades elaborado para el alumno/a en funci&oacute;n de su nivel de competencia (especificar el material: nombre, editorial,...).&nbsp;</li>
<li>Agrupamientos: Ser&iacute;a bueno situarlo junto a un compa&ntilde;ero que ejerza de tutor cuando se encuentre en el aula.&nbsp;</li>
<li>El alumno/a ser&aacute; atendido en su aula ordinaria en la que trabajar&aacute; con el material adaptado, guiado por el profesor/a de ...............&nbsp;</li>
<li>Necesitar&aacute; tiempo extra para la realizaci&oacute;n de las actividades.&nbsp;</li>
<li>Se debe situar en clase lo m&aacute;s cerca posible del profesor/a.&nbsp;</li>
<li>Realizar&aacute; menos actividades que el resto del grupo-clase</li>
</ul>

<p>&nbsp;</p>

<table style="border-collapse: collapse;" border="1" cellspacing="0" cellpadding="0"><colgroup><col width="743" /></colgroup>
<tbody>
<tr>
<td style="text-align: left; width: 16.999cm; background-color: #eee;">
<h4>&nbsp;Procedimientos e Instrumentos de evaluaci&oacute;n: (Adaptaciones en formato y tiempo, utilizaci&oacute;n de recursos did&aacute;cticos e instrumentos como apoyo a la evaluaci&oacute;n, etc.) &nbsp;</h4>
</td>
</tr>
</tbody>
</table>
<br>
<p>En cuanto a los procedimientos e instrumentos de evaluaci&oacute;n, ser&aacute; necesario realizar una evaluaci&oacute;n lo m&aacute;s inclusiva posible a trav&eacute;s de:</p>
<p style="margin-left: 0cm;"><em><span class="T3">Uso de m&eacute;todos de evaluaci&oacute;n alternativos o complementarios a las pruebas escritas</span></em>:</p>
<ul>
<li>observaci&oacute;n diaria del trabajo del alumnado (sin centrarnos &uacute;nicamente en la adquisici&oacute;n final de los contenidos),</li>
<li>portafolios (carpeta en la que el alumnado va archivando sus producciones de clase),</li>
<li>diarios de clase,</li>
<li>listas de control, etc.</li>
</ul>
<p style="margin-left: 0cm;">Todos ellos est&aacute;n basados en la observaci&oacute;n y seguimiento del alumnado, m&aacute;s que en la realizaci&oacute;n de una prueba escrita en un momento determinado.&nbsp;</p>
<p style="margin-left: 0cm;"><em><span class="T3">Adaptaciones en las pruebas escritas</span>: &nbsp;</em></p>
<p style="margin-left: 0cm; padding-left: 40px;"><span style="text-decoration: underline;">Adaptaciones en formato</span><em>:&nbsp;</em></p>
<ul>
<li style="list-style-type: none;">
<ul>
<li>Presentaci&oacute;n de las preguntas de forma secuenciada y separada.&nbsp;</li>
<li>Presentaci&oacute;n de los enunciados de forma gr&aacute;fica o en im&aacute;genes adem&aacute;s de texto escrito.&nbsp;</li>
<li>Hacer la prueba solo con lo b&aacute;sico que queremos que aprendan.&nbsp;</li>
<li>Sustituci&oacute;n de la prueba escrita por una prueba oral o una entrevista.&nbsp;</li>
<li>Lectura de las preguntas por parte del profesor/a.&nbsp;</li>
<li>Supervisi&oacute;n del examen durante su realizaci&oacute;n.&nbsp;</li>
</ul>
</li>
</ul>
<p style="margin-left: 0cm; padding-left: 40px;"><span style="text-decoration: underline;">Adaptaciones en tiempo:&nbsp;</span></p>
<ul>
<li style="list-style-type: none;">
<ul>
<li>Algunos alumnos/as necesitar&aacute;n m&aacute;s tiempo para la realizaci&oacute;n de una prueba escrita. Esta adaptaci&oacute;n de tiempo no tiene porque tener l&iacute;mites.&nbsp;</li>
</ul>
</li>
</ul>
<p style="margin-left: 0cm; padding-left: 40px;">&nbsp;</p>
<p style="margin-left: 0cm;">En definitiva estas adaptaciones en las pruebas escritas deben ser las mismas que ha tenido el alumno en su proceso de aprendizaje. Estas adaptaciones deben ser concebidas como una ayuda para que todo el alumnado pueda demostrar sus competencias y capacidades.&nbsp;</p>

<p>&nbsp;</p>
<p>&nbsp;</p>';
?>

					<hr>
						<div class="form-group">
							<textarea class="form-control" id="texto_acta" name="texto_acta" rows="20" required><?php echo (isset($texto_acta) and ($registrado==1)) ? $texto_acta : $html_textarea; ?></textarea>
						</div>

					</fieldset>
					<?php if (empty($id_acta)): ?>
					<button class="btn btn-primary" id="guardar" name="guardar">Registrar acta</button>
					<?php else: ?>
					<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
					<button class="btn btn-primary" id="actualizar" name="actualizar" >Actualizar acta</button>
					<a class="btn btn-default" href="<?php echo $uri; ?>">Registrar nueva acta</a>
					<?php endif; ?>
				</div>

				<hr>

				<p class="help-block well">La ruta al entrar en <b>SÉNECA</b> para cumplimentar la propuesta curricular de una ACNS para un alumno/a (que previamente ha debido crear el tutor/a) es la siguiente: <em>Alumnado –> Gestión de la Orientación -> Medidas Específicas (alumnado NEAE) -> Adaptación Curricular No Significativa ->  Selecciono el curso académico correspondiente</em>. <br>En el menú que se despliega selecciono <em>Apartados</em>; selecciono la propuesta curricular de mi asignatura, pulso detalle, cumplimento todos los apartados y pulso <em>validar</em>. <br>
				<u>Documentaci&oacute;n</u>: se puede adjuntar alg&uacute;n documento si se considera necesario.</p>

			</div>

		

			<div class="col-md-4">

				<?php $result = mysqli_query($db_con, "SELECT id, apellidos, nombre, adaptaciones.fecha, adaptaciones.materia, adaptaciones.unidad FROM adaptaciones, alma WHERE alma.claveal = adaptaciones.alumno and profesor = '$profesor' ORDER BY apellidos, nombre DESC"); ?>
				<?php if (mysqli_num_rows($result)): ?>
				<legend class="text-muted">Adaptaciones registradas</legend>
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<th>Alumno</th>
						<th>Opciones</th>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<tr>
							<td>
								<?php echo $row['apellidos'].", ".$row['nombre']; ?><br />
								<small class="text-muted"><?php echo "<em>".$row['materia']."</em> (".$row['unidad'].")"; ?></small>
							</td>
							<td>
								<a href="<?php echo $uri; ?>?edit_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar documento"><span class="far fa-edit fa-fw fa-lg"></span></a>
								<a href="pdf.php?id=<?php echo $row['id']; ?>&amp;imprimir=1" target="_blank" data-bs="tooltip" title="Imprimir"><span class="fas fa-print fa-fw fa-lg"></span></a>
								<a href="<?php echo $uri; ?>?eliminar_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar documento" data-bb="confirm-delete"><span class="far fa-trash-alt fa-fw fa-lg"></span></a>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

				<?php else: ?>
				<p class="lead text-muted text-center">No se ha registrado ninguna adaptación curricular.</p>
				<?php endif; ?>

			</div>

		</div><!-- /.row -->

	</form>
</div>

<?php include("../../../pie.php"); ?>

<script>

	$(document).ready(function() {

		// EDITOR DE TEXTO
		tinymce.init({
			selector: 'textarea#texto_acta',
			language: 'es_ES',
			height: 500,
			<?php if ($bloquea_campos): ?>
			readonly : 1,
			<?php endif; ?>
			plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars',
			imagetools_cors_hosts: ['picsum.photos'],
			menubar: 'file edit view insert format tools table help',
			toolbar: 'undo redo | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap | fullscreen  preview save print | insertfile image media template link anchor | ltr rtl',
			toolbar_sticky: true,
			autosave_ask_before_unload: true,
			autosave_interval: "30s",
			autosave_prefix: "{path}{query}-{id}-",
			autosave_restore_when_empty: false,
			autosave_retention: "2m",
			image_advtab: true,
			
			/* enable title field in the Image dialog*/
			image_title: true,
			/* enable automatic uploads of images represented by blob or data URIs*/
			automatic_uploads: true,
			/*
			URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
			images_upload_url: 'postAcceptor.php',
			here we add custom filepicker only to Image dialog
			*/
			file_picker_types: 'image',
			/* and here's our custom image picker*/
			file_picker_callback: function (cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');

			/*
			  Note: In modern browsers input[type="file"] is functional without
			  even adding it to the DOM, but that might not be the case in some older
			  or quirky browsers like IE, so you might want to add it to the DOM
			  just in case, and visually hide it. And do not forget do remove it
			  once you do not need it anymore.
			*/

			input.onchange = function () {
			  var file = this.files[0];

			  var reader = new FileReader();
			  reader.onload = function () {
			    /*
			      Note: Now we need to register the blob in TinyMCEs image blob
			      registry. In the next release this part hopefully won't be
			      necessary, as we are looking to handle it internally.
			    */
			    var id = 'blobid' + (new Date()).getTime();
			    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
			    var base64 = reader.result.split(',')[1];
			    var blobInfo = blobCache.create(id, file, base64);
			    blobCache.add(blobInfo);

			    /* call the callback and populate the Title field with the file name */
			    cb(blobInfo.blobUri(), { title: file.name });
			  };
			  reader.readAsDataURL(file);
			};

			input.click();
			}
		});

	});
	</script>

</body>
</html>
