<?php
require('../../bootstrap.php');


include("../../menu.php");
include("../informes/menu_alumno.php");

?>
  
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Fotografías <small>Alumnos del centro</small></h2>
	</div>
	
	<br>
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<div class="col-sm-4 col-sm-offset-4">
			
			<div class="well">
				
				<form method="post" action="fotos_alumnos.php" target="_blank">
					<fieldset>
						<legend>Selecciona el grupo</legend>
						
						<div class="form-group">
						  <select class="form-control" name="curso">
						  	<?php unidad();?>
						  </select>
						</div>
					  
					  <button type="submit" class="btn btn-primary" name="submit1">Consultar</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
</div><!-- /.container -->  

<?php include("../../pie.php"); ?>
</body>
</html>
