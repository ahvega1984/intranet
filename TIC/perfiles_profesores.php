<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

include("../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Perfiles de profesores</small></h2>
		</div>
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">
				
				<div class="table-responsive">	
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Profesor</th>
								<th>Usuario</th>
								<th>Contraseña<br>Gesuser</th>
								<th>Contraseña<br>Moodle</th>
								<th>Correo electrónico<br>G-Suite y Office 365</th>
								<th>Contraseña<br>G-Suite</th>
							</tr>
						</thead>
						<tbody>
							<?php if (stristr($_SESSION['cargo'],'1') == TRUE) $sql_where = ''; else $sql_where = 'AND idea=\''.$_SESSION['ide'].'\''; ?>
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT idea, nombre, dni, departamento FROM departamentos WHERE departamento <> 'Admin' $sql_where ORDER BY nombre ASC"); ?>
							<?php while ($row = mysqli_fetch_array($result)): ?>
							<?php
							$exp_nombre = explode(', ', $row['nombre']);

							$nombre = trim($exp_nombre[1]);
							$exp_nombrecomp = explode(' ',$nombre);
							$primer_nombre = trim($exp_nombrecomp[0]);

							$apellidos = trim($exp_nombre[0]);
							$exp_apellidos = explode(' ',$apellidos);
							$primer_apellido = trim($exp_apellidos[0]);
							$segundo_apellido = trim($exp_apellidos[1]);

							$nombre_completo = trim($exp_nombre[1].' '.$exp_nombre[0]);

							$caracteres_no_permitidos = array('\'','-','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü','ñ');
							$caracteres_permitidos = array('','','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n');

							$correo = $primer_nombre.'.'.$primer_apellido;
							$correo = str_ireplace('M ª', 'María', $correo);
							$correo = str_ireplace('Mª', 'María', $correo);
							$correo = str_ireplace('M.', 'María', $correo);
							$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
							$correo = mb_strtolower($correo, 'UTF-8');
							$correo = $correo.'@'.$config['dominio'];

							// Si ya existe la cuenta de correo, añadimos el segundo apellido
							if (in_array($correo, $array_correos)) {
								$correo = $primer_nombre.'.'.$primer_apellido.'.'.$segundo_apellido;
								$correo = str_ireplace('M ª', 'María', $correo);
								$correo = str_ireplace('Mª', 'María', $correo);
								$correo = str_ireplace('M.', 'María', $correo);
								$correo = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $correo);
								$correo = mb_strtolower($correo, 'UTF-8');
								$correo = $correo.'@'.$config['dominio'];
							}

							array_push($array_correos, $correo);
							?>
							<tr>
								<td><?php echo $row['nombre']; ?></td>
								<td><?php echo $row['idea']; ?></td>
								<td><?php echo $row['idea']; ?></td>
								<td><?php echo $row['dni']; ?></td>
								<td><?php echo $correo; ?></td>
								<td><?php echo $row['dni']; ?></td>
							</tr>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</tbody>
					</table>
				</div>
				
				<div class="hidden-print">
					<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
				</div>
					
				
			</div><!-- /.col-sm-6 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>

</body>
</html>
