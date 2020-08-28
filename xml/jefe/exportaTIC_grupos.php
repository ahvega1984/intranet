<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('0', '1'));

require('../../menu.php');

if (isset($_POST['unidad'])) {$unidad = mysqli_real_escape_string($db_con, $_POST['unidad']);}
?>
<div class="container">

	<div class="page-header">
		<h2>Administración <small>Exportación de alumnos para GSuite</small></h2>
	</div>



	<div class="row">
			
		<div class="col-sm-6">
			<br>	
			<div class="well">
				<form method="GET" action="exportaTIC_gsuite.php" class="form-horizontal">

					<fieldset>
						<legend>Selecciona unidad para exportar los datos</legend>
						<div class="col-sm-6">
							<select class="form-control" id="unidad" name="unidad" onchange="submit()">
								<option value=""></option>
									<?php $result = mysqli_query($db_con, "select nomunidad from unidades order by idunidad"); ?>
									<?php while ($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['nomunidad']; ?>"<?php echo (isset($departamento) && $departamento == $row['nomunidad']) ? ' selected' : ''; ?>><?php echo $row['nomunidad']; ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</fieldset>
				</form>
				<br>
				<a href="../index.php" class="btn btn-default">Volver</a>
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
			
		<div class="col-sm-6">
			
			<h3>Información sobre la exportación</h3>
			
			<p class="help-block">Si el centro dispone de la versión educativa o corporativa de <b>GSuite</b> y ya se han subido los alumnos hacia la misma, este módulo permite subir alumnos en bloque hacia los grupos creados dentro de la aplicación. De esta manera, la tarea de añadir alumnos a los grupos se realiza de una sola pasada, permitiendo a los profesores dirigirse a todos los alumnos mediante el correo de grupo (por ejemplo, <em class="text-info">1eso-a@iesmonterroso.org</em>, etc.) o enviar una sola invitación de Google Meet a todos los miembros del grupo.</p>
		
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
</div>


<?php include("../../pie.php"); ?>


