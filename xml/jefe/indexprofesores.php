<?php
require('../../bootstrap.php');


$profe = $_SESSION['profi'];
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header('Location:'.'http://'.$dominio.'/intranet/logout.php');
exit;	
}

include("../../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Importaci�n de profesores</small></h2>
	</div>
	
	<?php $result = mysqli_query($db_con, "SELECT * FROM profesores LIMIT 1"); ?>
	<?php if(mysqli_num_rows($result)): ?>
	<div class="alert alert-warning">
		Ya existe informaci�n en la base de datos. Este proceso actualizar� la informaci�n de los profesores. Es recomendable realizar una <a href="copia_db/index.php" class="alert-link">copia de seguridad</a> antes de proceder a la importaci�n de los datos.
	</div>
	<?php endif; ?>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="profesores.php">
					<fieldset>
						<legend>Importaci�n de profesores</legend>
						
						<div class="form-group">
						  <label for="archivo"><span class="text-info">RelMatProUni.txt</span></label>
						  <input type="file" id="archivo" name="archivo" accept="text/plain">
						</div>
						
						<br>
						
					  <button type="submit" class="btn btn-primary" name="enviar">Importar</button>
					  <a class="btn btn-default" href="../index.php">Volver</a>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<div class="col-sm-6">
			
			<h3>Informaci�n sobre la importaci�n</h3>
			
			<p>Este apartado se encarga de importar la relaci�n de <strong>profesores, aulas y asignaturas</strong> que imparten. Esto permitir� comprobar incoherencias en el horario de Horw y S�neca.</p>
			
			<p>Para obtener el archivo de exportaci�n de alumnos debe dirigirse al apartado <strong>Personal</strong>, <strong>Personal del centro</strong>, <strong>Unidades y materias</strong> <em>(icono arriba a la derecha)</em>. Muestre todos los profesores y haga clic en el bot�n <strong>Exportar datos</strong>. El formato de exportaci�n debe ser <strong>Texto plano</strong>.</p>

		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
