<?php
require('../../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$organos = array('Claustro de Profesores','DFEIE', 'Equipo directivo', 'ETCP', 'Coord. Enseñanzas Bilingües', 'Área Artística', 'Área Científico-Tecnológica', 'Área Social-Lingüística', 'Área Formación Profesional');

$bloquea_campos = 0;

if (isset($_POST['departamento']) || isset($_GET['organo'])) {

	$esOrgano = 0;

	if (isset($_POST['departamento'])) {
		$departamento = mysqli_real_escape_string($db_con, $_POST['departamento']);
		$titulo = 'Departamento de '.$departamento;

		if (! acl_permiso($_SESSION['cargo'], array('1')) && $dpto != $_POST['departamento']) {
			acl_acceso();
		}
	}
	else {
		$departamento = mysqli_real_escape_string($db_con, $_GET['organo']);
		$organo_centro = $_GET['organo'];
		$titulo = $organo_centro;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL CLAUSTRO
	if ($departamento == 'Claustro de Profesores') {
		acl_acceso($_SESSION['cargo'], array('1'));

		$result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Educador' AND departamento <> 'Servicio Técnico y/o Mantenimiento' AND cargo LIKE '%1%' ORDER BY nombre ASC");
		$existe_secretario_claustro = mysqli_num_rows($result);

		if ((! isset($config['actas_depto']['secretario_claustro']) || $config['actas_depto']['secretario_claustro'] == "") && ! $existe_secretario_claustro) {
			$msg_alerta = "No se ha definido al secretario del Claustro de Profesores en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		elseif (isset($config['actas_depto']['secretario_claustro'])) {
			$jefe_departamento = $config['actas_depto']['secretario_claustro'];
			$bloquea_campos = 0;
		}
		else {
			$row = mysqli_fetch_row($result);
			$jefe_departamento = $row[0];
			$bloquea_campos = 0;
		}

		$titulo = 'Claustro de Profesores';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL DFEIE
	if ($departamento == 'DFEIE') {
		acl_acceso($_SESSION['cargo'], array('1','f'));

		$result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Educador' AND departamento <> 'Servicio Técnico y/o Mantenimiento' AND cargo LIKE '%f%' ORDER BY nombre ASC");
		$existe_secretario_dfeie = mysqli_num_rows($result);

		if ((! isset($config['actas_depto']['secretario_dfeie']) || $config['actas_depto']['secretario_dfeie'] == "") && ! $existe_secretario_dfeie) {
			$msg_alerta = "No se ha definido al secretario del Departamento de Formación, Evaluación e Innovación Educativa en los perfiles de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		elseif (isset($config['actas_depto']['secretario_dfeie'])) {
			$jefe_departamento = $config['actas_depto']['secretario_dfeie'];
			$bloquea_campos = 0;
		}
		else {
			$row = mysqli_fetch_row($result);
			$jefe_departamento = $row[0];
			$bloquea_campos = 0;
		}

		$titulo = 'Departamento de Formación, Evaluación e Innovación Educativa';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. DIRECTIVO
	if ($departamento == 'Equipo directivo') {
		acl_acceso($_SESSION['cargo'], array('1'));

		if ((! isset($config['actas_depto']['secretario_ed']) || $config['actas_depto']['secretario_ed'] == "") && ! isset($config['directivo_secretaria'])) {
			$msg_alerta = "No se ha definido al secretario del Equipo Directivo en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		elseif (isset($config['actas_depto']['secretario_ed'])) {
			$jefe_departamento = $config['actas_depto']['secretario_ed'];
			$bloquea_campos = 0;
		}
		else {
			$jefe_departamento = $config['directivo_secretaria'];
			$bloquea_campos = 0;
		}

		$titulo = 'Equipo Directivo';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ETCP
	if ($departamento == 'ETCP') {
		acl_acceso($_SESSION['cargo'], array('1','9'));

		if (! isset($config['actas_depto']['secretario_etcp']) || $config['actas_depto']['secretario_etcp'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo Técnico de Coordinación Pedagógica en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_etcp'];
		}

		$titulo = 'Equipo Técnico de Coordinación Pedagógica';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. COORD. ENSEÑANZAS BILINGÜES
	if ($departamento == 'CEB') {
		if (! isset($config['actas_depto']['secretario_ceb']) || $config['actas_depto']['secretario_ceb'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo de Coordinación de Enseñanzas Bilingües en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_ceb'];
		}

		$titulo = 'Equipo de Coordinación de Enseñanzas Bilingües';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL EQ. COORD. ENSEÑANZAS BILINGÜES
	if ($departamento == 'Coord. Enseñanzas Bilingües') {
		acl_acceso($_SESSION['cargo'], array('1','a'));

		if (! isset($config['actas_depto']['secretario_ceb']) || $config['actas_depto']['secretario_ceb'] == "") {
			$msg_alerta = "No se ha definido al secretario del Equipo de Coordinación de Enseñanzas Bilingües en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_ceb'];
		}

		$titulo = 'Equipo de Coordinación de Enseñanzas Bilingües';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ÁREA DE COMPETENCIA ARTÍSTICA
	if ($departamento == 'Área Artística') {
		if (! isset($config['actas_depto']['secretario_aca']) || $config['actas_depto']['secretario_aca'] == "") {
			$msg_alerta = "No se ha definido al coordinador del Área de Competencia Artística en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_aca'];

			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_aca']) {
				acl_acceso();
			}
		}

		$titulo = 'Área de Competencia Artística';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ÁREA DE COMPETENCIA CIENTIFICO-TECNOLÓGICA
	if ($departamento == 'Área Científico-Tecnológica') {
		if (! isset($config['actas_depto']['secretario_acct']) || $config['actas_depto']['secretario_acct'] == "") {
			$msg_alerta = "No se ha definido al coordinador del Área de Competencia Científico-Tecnológica en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_acct'];

			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_acct']) {
				acl_acceso();
			}
		}

		$titulo = 'Área de Competencia Científico-Tecnológica';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ÁREA DE COMPETENCIA SOCIAL-LINGÜISTICA
	if ($departamento == 'Área Social-Lingüística') {
		if (! isset($config['actas_depto']['secretario_acsl']) || $config['actas_depto']['secretario_acsl'] == "") {
			$msg_alerta = "No se ha definido al coordinador del Área de Competencia Social-Lingüistica en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_acsl'];

			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_acsl']) {
				acl_acceso();
			}
		}

		$titulo = 'Área de Competencia Social-Lingüistica';
		$esOrgano = 1;
	}

	// COMPROBAMOS SI SE HA ASIGNADO EL PERFIL DE SECRETARIO DEL ÁREA DE FORMACIÓN PROFESIONAL
	if ($departamento == 'Área Formación Profesional') {
		if (! isset($config['actas_depto']['secretario_afp']) || $config['actas_depto']['secretario_afp'] == "") {
			$msg_alerta = "No se ha definido al coordinador del Área de Formación Profesional en la configuración de la aplicación ni en las preferencias del módulo.";
			$bloquea_campos = 1;
		}
		else {
			$jefe_departamento = $config['actas_depto']['secretario_afp'];

			if (! acl_permiso($_SESSION['cargo'], array('1')) && $pr != $config['actas_depto']['secretario_afp']) {
				acl_acceso();
			}
		}

		$titulo = 'Área de Formación Profesional';
		$esOrgano = 1;
	}

}
else {
	$departamento = $dpto;
	$titulo = 'Departamento de '.$departamento;
}

// REGISTRAMOS EL ACTA
if (isset($_POST['guardar'])) {

	$input_jefe_departamento = mysqli_real_escape_string($db_con, $_POST['jefe_departamento']);
	$input_fecha_reunion = mysqli_real_escape_string($db_con, $_POST['fecha_reunion']);
	$exp_input_fecha_reunion = explode('-', $input_fecha_reunion);
	$fecha_reunion_sql = $exp_input_fecha_reunion[2].'-'.$exp_input_fecha_reunion[1].'-'.$exp_input_fecha_reunion[0];
	$input_num_acta = mysqli_real_escape_string($db_con, $_POST['num_acta']);
	$input_texto_acta = $_POST['texto_acta'];
	$fecha_hoy = date('Y-m-d H:i:s');

	$result = mysqli_query($db_con, "INSERT INTO r_departamento (contenido, jefedep, timestamp, DEPARTAMENTO, fecha, impreso, numero) VALUES ('$input_texto_acta', '$input_jefe_departamento', '$fecha_hoy', '$departamento', '$fecha_reunion_sql', 0, $input_num_acta)");
	if (! $result) $msg_error = "Ha ocurrido un error al registrar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido registrada correctamente";
}

if (isset($_GET['edit_id'])) {

	$id_acta = mysqli_real_escape_string($db_con, $_GET['edit_id']);

	$result = mysqli_query($db_con, "SELECT * FROM r_departamento WHERE id = $id_acta");
	$row = mysqli_fetch_array($result);
	$departamento = $row['DEPARTAMENTO'];
	$jefe_departamento = $row['jefedep'];
	$fecha_reunion_sql = $row['fecha'];
	$exp_fecha_reunion = explode('-', $fecha_reunion_sql);
	$fecha_reunion = $exp_fecha_reunion[2].'-'.$exp_fecha_reunion[1].'-'.$exp_fecha_reunion[0];
	$numero_acta = $row['numero'];
	$texto_acta = $row['contenido'];
}

// ACTUALIZAMOS EL ACTA
if (isset($_POST['actualizar'])) {

	$id_acta = mysqli_real_escape_string($db_con, $_POST['id_acta']);
	$jefe_departamento = mysqli_real_escape_string($db_con, $_POST['jefe_departamento']);
	$fecha_reunion = mysqli_real_escape_string($db_con, $_POST['fecha_reunion']);
	$exp_fecha_reunion = explode('-', $fecha_reunion);
	$fecha_reunion_sql = $exp_fecha_reunion[2].'-'.$exp_fecha_reunion[1].'-'.$exp_fecha_reunion[0];
	$numero_acta = mysqli_real_escape_string($db_con, $_POST['num_acta']);
	$texto_acta = $_POST['texto_acta'];
	$fecha_hoy = date('Y-m-d H:i:s');

	$result = mysqli_query($db_con, "UPDATE r_departamento SET contenido = '$texto_acta', fecha = '$fecha_reunion_sql', numero = $numero_acta WHERE id = $id_acta");
	if (! $result) $msg_error = "Ha ocurrido un error al registrar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido registrada correctamente";
}

// ELIMINAR ACTA
if (isset($_GET['eliminar_id'])) {

	$eliminar_id = mysqli_real_escape_string($db_con, $_GET['eliminar_id']);

	$result = mysqli_query($db_con, "DELETE FROM r_departamento WHERE id = $eliminar_id");
	if (! $result) $msg_error = "Ha ocurrido un error al eliminar el acta. Error: ".mysqli_error($db_con);
	else $msg_success = "El acta ha sido eliminada correctamente.";
}


// URI módulo
if (isset($_GET['organo'])) {
	$uri = 'index.php?organo='.$_GET['organo'].'&amp;';
}
else {
	$uri = 'index.php?';
}

// PLUGINS
$PLUGIN_DATATABLES = 1;

include ("../../../menu.php");
include('../menu.php');
include('menu.php');

?>

<div class="container">

		<div class="page-header">
			<h2><?php echo $titulo; ?> <small>Registro de actas</small></h2>

			<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
				<br>
			<div class="row">
				<form method="post" action="" class="form-horizontal">
				<div class="col-md-6 col-lg-5">
					<label for="departamento" class="control-label">Selecciona órgano o departamento del centro.</label>
					<br><br>
					<select class="form-control input text-info" id="departamento" name="departamento" onchange="submit()">
						<option value=""></option>
						<optgroup label="Órganos del centro">
							<?php foreach($organos as $organo): ?>
							<option value="<?php echo $organo; ?>"<?php echo (isset($departamento) && $departamento == $organo) ? ' selected' : ''; ?>><?php echo $organo; ?></option>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="Departamentos del centro">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento <> '' AND departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Educador' AND departamento <> 'Servicio Técnico y/o Mantenimiento' ORDER BY departamento ASC"); ?>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<option value="<?php echo $row['departamento']; ?>"<?php echo (isset($departamento) && $departamento == $row['departamento']) ? ' selected' : ''; ?>><?php echo $row['departamento']; ?></option>
							<?php endwhile; ?>
						</optgroup>
					</select>
				</div><!-- /.col-sm-6 -->
			</form>
			</div><!-- /.row -->
			<?php elseif (isset($organo) && in_array($organo, $organos)): ?>
			<h3><?php echo $departamento; ?></h3>
			<?php endif; ?>

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

		<form method="post" action="">

		<div class="row">

			<div class="col-md-9">

				<div class="well">

					<?php
					// RECOLECTAMOS DATOS PARA RELLENAR EL ACTA

					if (! isset($id_acta)) {
						// Jefe de departamento
						if (! isset($jefe_departamento)) {
							$result = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE departamento = '$departamento' AND cargo LIKE '%4%'");
							$row = mysqli_fetch_row($result);
							$jefe_departamento = $row[0];
							mysqli_free_result($result);
						}

						// Número última acta
						$result = mysqli_query($db_con, "SELECT MAX(numero) FROM r_departamento WHERE departamento = '$departamento'");
						$row = mysqli_fetch_row($result);
						$numero_acta = $row[0] + 1;
						mysqli_free_result($result);

						// Hora reunión
						$result = mysqli_query($db_con, "SELECT hora, hora_inicio FROM tramos WHERE tramos.hora = (SELECT horw.hora FROM horw WHERE prof = '$jefe_departamento' AND c_asig = '51')");
						if (mysqli_num_rows($result)) {
							$row = mysqli_fetch_array($result);
							$hora_reunion = $row['hora_inicio'];
							$hora_fin_reunion = $row['hora_inicio'] + 1;
							mysqli_free_result($result);
						}
						else {
							$hora_reunion = date('H:i');
							$hora_fin_reunion = strtotime('+1 hour', strtotime(date('H:i')));
							$hora_fin_reunion = date('H:i', $hora_fin_reunion);
						}
					}

					if (acl_permiso($_SESSION['cargo'], array('1')) || nomprofesor($jefe_departamento) == nomprofesor($pr)) $bloquea_campos = 0;
					else $bloquea_campos = 1;
					?>

					<fieldset>

						<div class="row">

							<div class="col-sm-6">

								<div class="form-group">
									<label for="jefe_departamento"><?php echo ($esOrgano) ? 'Secretario / Coordinador' : 'Jefe del departamento'; ?></label>
									<input type="text" class="form-control" id="jefe_departamento" name="jefe_departamento" value="<?php echo $jefe_departamento; ?>" <?php echo ($bloquea_campos) ? 'disabled' : 'readonly'; ?>>
								</div>

							</div><!-- /.col-sm-6 -->

							<div class="col-sm-4">

								<div class="form-group" id="datetimepicker1">
									<label for="fecha_reunion">Fecha de la reunión</label>
									<div class="input-group">
										<input type="text" class="form-control" id="fecha_reunion" name="fecha_reunion" value="<?php echo (isset($fecha_reunion)) ? $fecha_reunion : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>>
										<span class="input-group-addon"><span class="far fa-calendar"></span></span>
									</div>
								</div>

							</div><!-- /.col-sm-4 -->

							<div class="col-sm-2">

								<div class="form-group">
									<label for="num_acta">Nº Acta</label>
									<input type="text" class="form-control" id="num_acta" name="num_acta" value="<?php echo ($numero_acta > 1) ? $numero_acta : '1'; ?>" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>>
								</div>

							</div><!-- /.col-sm-2 -->

						</div><!-- /.row -->

<?php
$html_textarea = "<p>".$titulo."</p>
<p>".$config['centro_denominacion']." (".$config['centro_localidad'].")</p>
<p>Curso escolar: ".$config['curso_actual']."</p>
<p>Acta nº NUMERO_ACTA</p>
<p><br></p>
<p style=\"text-align: center;\"><strong style=\"text-decoration: underline;\">ACTA DE REUNIÓN DEL ".mb_strtoupper($titulo, 'UTF-8')."</strong></p>
<p><br></p>
<p>En ".$config['centro_localidad'].", a las ".$hora_reunion." horas del FECHA_DE_LA_REUNION, se re&uacute;ne el ".$titulo." del ".$config['centro_denominacion']." de ".$config['centro_localidad'].", con el siguiente <span style=\"text-decoration: underline;\">orden del d&iacute;a</span>:</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p><u>Profesores Asistentes:</u></p>
<p><br></p>
<p><br></p>
<p><u>Profesores Ausentes:</u></p>
<p><br></p>
<p><br></p>
<p><br></p>
<p>Sin más asuntos que tratar, se levanta la sesión a las ".$hora_fin_reunion." horas.</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p>Fdo.: ".$jefe_departamento."</p>";
?>


						<div class="form-group">
							<label for="texto_acta">Acta</label>
							<textarea class="form-control" id="texto_acta" name="texto_acta" rows="20" <?php echo ($bloquea_campos) ? 'disabled' : 'required'; ?>><?php echo (isset($texto_acta)) ? $texto_acta : $html_textarea; ?></textarea>
						</div>

					</fieldset>

					<?php if (! isset($id_acta)): ?>
					<button class="btn btn-primary" id="guardar" name="guardar"<?php echo ($bloquea_campos) ? ' disabled' : ''; ?>>Registrar acta</button>
					<?php else: ?>
					<input type="hidden" name="id_acta" value="<?php echo $id_acta; ?>">
					<button class="btn btn-primary" id="actualizar" name="actualizar"<?php echo ($bloquea_campos) ? ' disabled' : ''; ?>>Actualizar acta</button>
					<a class="btn btn-default" href="<?php echo $uri; ?>">Registrar nueva acta</a>
					<?php endif; ?>
				</div>

			</div>

			<div class="col-md-3">

				<?php $result = mysqli_query($db_con, "SELECT id, fecha, numero, impreso FROM r_departamento WHERE departamento = '$departamento' ORDER BY numero DESC"); ?>
				<?php if (mysqli_num_rows($result)): ?>

				<table class="table table-bordered table-hover datatable">
					<thead>
						<th>Acta</th>
						<th>Opciones</th>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<tr>
							<td>
								<?php echo strftime('%d-%m-%Y', strtotime($row['fecha'])); ?><br />
								<small class="text-muted">Acta nº <?php echo $row['numero']; ?></small>
							</td>
							<td>
								<?php if ($bloquea_campos): ?>
								<a href="pdf.php?id=<?php echo $row['id']; ?>" target="_blank" data-bs="tooltip" title="Ver acta"><span class="far fa-eye fa-fw fa-lg"></span></a>
								<?php else: ?>
								<?php if (! $row['impreso']): ?>
								<a href="<?php echo $uri; ?>edit_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Editar acta"><span class="far fa-edit fa-fw fa-lg"></span></a>
								<a href="pdf.php?id=<?php echo $row['id']; ?>&amp;imprimir=1" target="_blank" data-bs="tooltip" title="Imprimir acta"><span class="fas fa-print fa-fw fa-lg"></span></a>
								<a href="<?php echo $uri; ?>eliminar_id=<?php echo $row['id']; ?>" data-bs="tooltip" title="Eliminar acta" data-bb="confirm-delete"><span class="far fa-trash-alt fa-fw fa-lg"></span></a>
								<?php else: ?>
								<a href="pdf.php?id=<?php echo $row['id']; ?>" target="_blank" data-bs="tooltip" title="Ver acta"><span class="far fa-eye fa-fw fa-lg"></span></a>
								<?php endif; ?>

								<?php endif; ?>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>

				<?php else: ?>
				<p class="lead text-muted text-center">No se ha registrado ningún acta en este departamento.</p>
				<?php endif; ?>

			</div>

		</div><!-- /.row -->

	</form>
</div>

<?php include("../../../pie.php"); ?>

<script>
	$(function ()
	{
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		})
	});

	$(document).ready(function() {

		// EDITOR DE TEXTO
		tinymce.init({
			selector: 'textarea#texto_acta',
			language: 'es',
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


		var numacta = 'NUMERO_ACTA';
		var fecha = 'FECHA_DE_LA_REUNION';

		function reemplazar_numacta() {
			texto = $("#texto_acta").html();
			valor_numacta = " "+$('#num_acta').val();
		    texto = texto.replace(numacta, valor_numacta);
		    numacta = valor_numacta;
		    $("#texto_acta").html(texto);
		}

		function reemplazar_fecha() {
			texto = $("#texto_acta").html();
			valor_fecha = $('#fecha_reunion').val();
			formateo_fecha = moment(valor_fecha, 'DD-MM-YYYY', true).format('LL')
		    texto = texto.replace(fecha, formateo_fecha);
		    fecha = formateo_fecha;
		    $("#texto_acta").html(texto);
		}

		reemplazar_numacta();
		reemplazar_fecha();

		$("#num_acta").keyup(function() {
			reemplazar_numacta();
		});

		$("#datetimepicker1").on("dp.change", function (e) {
			reemplazar_fecha();
        });

		var table = $('.datatable').DataTable({
			"paging":   true,
		    "ordering": false,
		    "info":     false,
		    "searching":   false,

			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],

			"language": {
	            "lengthMenu": "_MENU_",
	            "zeroRecords": "Sin resultados.",
	            "info": "Página _PAGE_ de _PAGES_",
	            "infoEmpty": "No hay resultados disponibles.",
	            "infoFiltered": "(filtrado de _MAX_ resultados)",
	            "search": "",
	            "paginate": {
	                  "first": "Primera",
	                  "next": "Última",
	                  "next": "",
	                  "previous": ""
	                }
	        }
		});

	});
	</script>

</body>
</html>
