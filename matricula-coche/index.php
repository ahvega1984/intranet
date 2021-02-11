<?php

require('../bootstrap.php');
	
if ( isset($_POST['registrarMatricula']) ) {
	$cmp_matricula = strtoupper (limpiarInput(trim($_POST['registrarMatricula']), 'alphanumeric'));
	if ( 1 ) {
		mysqli_query($db_con, "UPDATE `c_profes` SET `matricula` = '".$cmp_matricula."' WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1");
	}
	else {
		$msg_matricula_error = "Debe introducir una matricula válida.";
	}
} else {
	$msg_matricula_error = "Debe introducir una matricula válida.";
}

// Obtenemos la matricula del usuario
$usuario = array();
$result = mysqli_query($db_con, "SELECT `matricula` FROM `c_profes` WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1") or die (mysqli_error($db_con));
if (mysqli_num_rows($result)) {
	$row = mysqli_fetch_array($result);
	$matricula = $row['matricula'];	
}
$matricula_buscar = '';
if (isset($_POST['matricula_buscar']) && $_POST['matricula_buscar'] != "") {
	$matricula_buscar = strtoupper(limpiarInput($_POST['matricula_buscar']));
	$results = mysqli_query($db_con, "SELECT `PROFESOR` FROM `c_profes` WHERE `matricula` LIKE '%".$matricula_buscar."%'") or die (mysqli_error($db_con));
	$profesores = array();
	while ($profesor = mysqli_fetch_array($results)) {	
		$profesores [] = $profesor[0];
	}	
	if (count ($profesores) == 0) {
		$profesores_error = 'No coincide con ninguna matrícula';
	}	
} else {
	$profesor_error = '';
}

include("../menu.php");
include("menu.php");
?>

<div class="container">
	
	<div class="col-md-4">
		<div class="well">
			
			<div class="page-header">
				<h2>Tu matrícula: </h2>
			</div>		
			
			<form action="index.php" method="post" class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label for="matricula" class="col-sm-3 control-label">Matrícula</label>
						<div class="col-sm-4">
							<input style="width: 150px" type="text" id="matricula" name="registrarMatricula" class="form-control" value="<?php echo (isset($matricula) && ! empty($matricula)) ? $matricula : ''; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button type="submit" class="btn btn-primary" name="buscarMatricula">Guardar matrícula</button>
						</div>
					</div>
				</fieldset>
				<p class="text-danger"><?php echo $profesores_error; ?></p>
			</form>
		</div>
	</div>
	<div class="col-md-4">
		<div class="well">			
			<div class="page-header">
				<h2>Buscar matrículas</h2>
			</div>
			<form action="index.php" method="post" class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label for="matricula" class="col-sm-3 control-label">Matrícula</label>
						<div class="col-sm-4">
							<input style="width: 150px" type="text" id="matricula" name="matricula_buscar" class="form-control" value="<?php echo (isset($matricula_buscar) && ! empty($matricula_buscar)) ? $matricula_buscar : ''; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-10">
							<button type="submit" class="btn btn-primary" name="buscarMatricula">Buscar matrícula</button>
						</div>
					</div>
				</fieldset>
				<p class="text-danger"><?php echo $profesores_error; ?></p>
			</form>
		</div>
	</div>
</div>

<?php if ( count($profesores) )  { ?>
<div class="container">
	<div class="well">
		<div class="page-header">
			<h2>Resultados</h2>
		</div>

		<div class="well">
			<ul class="list-group list-group-flush">
			<?php 
				foreach ($profesores as $row) {
					echo '<li class="list-group-item">'.$row.'</li>';
				}			
			?>
			</ul>
		</div>
	</div>
</div>
<?php } ?>


<?php include("../pie.php");?>

</body>
</html>
