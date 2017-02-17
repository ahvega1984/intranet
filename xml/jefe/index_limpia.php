<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administración <small>Depuración de horarios</small></h2>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6 col-sm-offset-3">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="limpia_hor.php">
					<fieldset>
						<legend>Depuración de horarios</legend>
						
						<p class="help-block">La depuración del horario se debe realizar cuando los horarios de los profesores se encuentran en Séneca y han sido completamente revisados y en perfecto estado de revista. Si consideras que ya no caben más cambios en los horarios, comienza actualizando Profesores y Departamentos (con el archivo RelPerCen.txt de Séneca: <a href="http://<?php echo $config['dominio']; ?>/intranet/xml/jefe/index_departamentos2.php" target="_blank">Actualizar Profesores y Departamentos</a>), y luego los Profesores, Materias y Unidades (con el archivo RelMatProUni.txt de Séneca: <a href="http://<?php echo $config['dominio']; ?>/intranet/xml/jefe/index_profesores.php" target="_blank">Actualizar Profesores, Materias y Unidades</a>). Una vez actualizados los profesores, puedes proceder a ejecutar esta función, la cual eliminará los elementos del horario generado por Horw que ya no son necesarios.</p>
						
					  <button type="submit" class="btn btn-primary" name="enviar">Depurar horarios</button>
					  <a class="btn btn-default" href="../index.php">Volver</a>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
