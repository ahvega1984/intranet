<?php
require('../bootstrap.php');
error_reporting(E_ALL);
ini_set('display_errors', true);

acl_acceso($_SESSION['cargo'], array('z', '1'));

include ("../menu.php");
?>
<div class='container-fluid'>

	<div class="page-header">
	  <h2>Administración <small> Perfiles de Profesores</small></h2>
	</div>

	<?php
	if (isset($_GET['borrar']) && $_GET['borrar'] == 1) {
		$dni_profe = preg_replace('([^A-Za-z0-9])', '', $_GET['dni_profe']);
		mysqli_query($db_con, "delete from departamentos where dni = '".$dni_profe."'");
		echo '<div class="alert alert-success">
	            <button type="button" class="close" data-dismiss="alert">&times;</button>
	            El profesor ha sido borrado de la base de datos..
	          </div>';
	}

	if (isset($_POST['enviar'])) {

		// Backup de FTUTORES
		mysqli_query($db_con, "drop table FTUTORES_seg" );
		mysqli_query($db_con, "create table FTUTORES_seg select * from FTUTORES" );
		mysqli_query($db_con, "truncate table FTUTORES" );
		// Backup de departamentos
		mysqli_query($db_con, "drop table departamentos_seg" );
		mysqli_query($db_con, "create table departamentos_seg select * from departamentos" );
		// Backup de c_profes
		mysqli_query($db_con, "drop table c_profes_seg" );
		mysqli_query($db_con, "create table c_profes_seg select * from c_profes" );
		mysqli_query($db_con, "update c_profes set telefono = ''" );

		mysqli_query($db_con, "truncate table cargos" );


		foreach($_POST as $input_key => $input_value) {
			
			if ($input_key != "enviar" && ! empty($input_value)) {

				//echo "$input_key => $input_value<br>";

				$input_value = trim($input_value);
				$exp_dni = explode('_', $input_key);
				$dni = trim($exp_dni[0]);

				if ((! is_numeric($input_value)) && strlen($input_value) > 1) {
					$resultNombreProfesor = mysqli_query($db_con, "SELECT nombre FROM departamentos WHERE dni = '$dni' LIMIT 1");
					$rowNombreProfesor = mysqli_fetch_array($resultNombreProfesor);
					$unidad = $input_value;
					$n_tutor = $rowNombreProfesor[0];

					mysqli_query($db_con, "INSERT INTO `FTUTORES` (`unidad` , `tutor`, `observaciones1`, `observaciones2`) VALUES ('$unidad', '$n_tutor', '', '')");

				} 
				elseif (strlen($input_value) < 2) {
					$cargo = $input_value;
					mysqli_query($db_con, "UPDATE departamentos SET cargo = '' WHERE dni = '$dni' LIMIT 1");
					mysqli_query($db_con, "INSERT INTO `cargos` (`dni` , `cargo`) VALUES ('$dni', '$cargo')" );
				}
			}
		}

		$dniEmpleados = mysqli_query($db_con, "SELECT dni FROM departamentos");
		while ($rowDniEmpleados = mysqli_fetch_array($dniEmpleados)) {
			$serializeCargosEmpleado = "";
			$cargosEmpleado = mysqli_query($db_con, "SELECT DISTINCT cargo FROM cargos WHERE dni = '".$rowDniEmpleados['dni']."'");
			while ($rowCargosEmpleado = mysqli_fetch_array($cargosEmpleado)) {
				$serializeCargosEmpleado .= $rowCargosEmpleado['cargo'];
			}
			mysqli_query($db_con, "UPDATE departamentos SET cargo = '$serializeCargosEmpleado' WHERE dni = '".$rowDniEmpleados['dni']."' LIMIT 1");
		}

		echo '<div class="alert alert-success">Los perfiles han sido asignados correctamente a los profesores.</div>';
	}
	?>

  <div class="row">

   <div class="col-sm-12">

   <style type="text/css">
   thead th {
   	font-size: 0.8em;
   }
   </style>

		<?php
		$head = '<thead>
			<tr>
			<th>Profesor</th>
			<th><span data-bs="tooltip" title="Administradores de la Aplicación">Admin</span></th>
			<th><span data-bs="tooltip" title="Miembros del Equipo Directivo del Centro">Dirección</span></th>
			<th><span data-bs="tooltip" title="Tutores de Grupo de todos los niveles">Tutor</span></th>
			<th><span data-bs="tooltip" title="Jefes de los distintos Departamentos que el IES ha seleccionado.">JD</span></th>
			<th><span data-bs="tooltip" title="Miembros del Equipo Técnico de Coordinación Pedadgógica">ETCP</span></th>
			<th><span data-bs="tooltip" title="Miembro del departamento de Actividades Complementarias y Extraescolares.">DACE</span></th>
			<th><span data-bs="tooltip" title="Miembros del personal de Administracción y Servicios: Conserjes.">Conserje</span></th>
			<th><span data-bs="tooltip" title="Miembros del personal de Administracción y Servicios: Administrativos">Administ.</span></th>
			<th><span data-bs="tooltip" title="Todos los profesores que pertenecen al Equipo de Orientación, incluídos ATAL, Apoyo, PCPI, etc.">Orienta.</span></th>';
		if (isset($config['mod_bilingue']) && $config['mod_bilingue'] == 1) {
			$head .= '<th><span data-bs="tooltip" title="Profesores que participan en el Plan de Bilinguismo">Bilingüe</span></th>';
		}
		if (isset($config['mod_convivencia']) && $config['mod_convivencia'] == 1) {
			$head .= '<th><span data-bs="tooltip" title="Profesores encargados de atender a los alumnos en el Aula de Convivencia del Centro, si este cuenta con ella.">Conv.</span></th>';
		}
		if (isset($config['mod_biblioteca']) && $config['mod_biblioteca'] == 1) {
			$head .= '<th><span data-bs="tooltip" title="Profesores que participan en el Plan de Bibliotecas o se encargan de llevar la Biblioteca del Centro">Biblio.</span></th>';
		}
		$head .= '<th><span data-bs="tooltip" title="Profesor encargado de las Relaciones de Género">Género</span></th>
				  <th><span data-bs="tooltip" title="Departamento de Formacción, Innovación y Evaluación">DFEIE</span></th>
				  <th>&nbsp;</th>
			</tr>
			</thead>';
		?>

		<form name="cargos" action="cargos.php" method="post">

		<p class="help-block">
			Si necesitas información sobre los distintos perfiles de los profesores, puedes conseguirla colocando el cursor del ratón sobre los distintos tipos de perfiles.
		</p>

		<table class="table table-bordered table-striped table-condensed">
		<?php echo $head;?>
			<tbody>
		<?php
		$resultDatosEmpleados = mysqli_query($db_con, "SELECT DISTINCT departamentos.nombre, departamentos.cargo, departamentos.dni, departamentos.idea, c_profes.telefono, c_profes.correo FROM departamentos JOIN c_profes ON departamentos.idea=c_profes.idea ORDER BY departamentos.nombre ASC");
		$num_profes = mysqli_num_rows($resultDatosEmpleados);
		$n_i = 0;
		while($rowDatosEmpleados = mysqli_fetch_array($resultDatosEmpleados)) {
			$pro = $rowDatosEmpleados['nombre'];
			$car = $rowDatosEmpleados['cargo'];
			$dni = $rowDatosEmpleados['dni'];
			$idea = $rowDatosEmpleados['idea'];
			$correo = $rowDatosEmpleados['correo'];
			$telefono = $rowDatosEmpleados['telefono'];
			if ($telefono=='0') {
				$telefono="";
			}
			$n_i = $n_i + 10;
			if ($n_i % 100 == 0) {
				echo $head;
			}
			?>
		<tr>
			<td nowrap>
				<strong><small><?php echo $pro;?></small></strong>
				<?php if (! empty($telefono)): ?>
				<p style="margin-bottom: 0; font-size: 0.75em;"><strong>Teléfono móvil:</strong> <?php echo $telefono; ?></p>
				<?php endif; ?>
				<?php if (! empty($correo)): ?>
				<p style="margin-bottom: 0; font-size: 0.75em;"><?php echo $correo; ?></p>
				<?php endif; ?>
			</td>

			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_z" value="z" <?php echo (! empty($car) && strpos('z', $car) !== false) ? "checked" : ""; ?> <?php echo ($idea == "admin") ? "checked disabled" : ""; ?>  />
			</td>

			<td class="text-center">
				<?php if ($idea == "admin"): ?>
				<input type="checkbox" name="" value="1" <?php echo ($idea == "admin") ? "checked disabled" : ""; ?> />
				<input type="hidden" name="<?php echo $dni; ?>_1" value="1">
				<?php else: ?>
				<input type="checkbox" name="<?php echo $dni; ?>_1" value="1" <?php echo (! empty($car) && strpos('1', $car) !== false) ? "checked" : ""; ?> <?php echo ($idea == "admin") ? "checked disabled" : ""; ?> />
				<?php endif; ?>
			</td>
			<td class="form-inline" nowrap>
				<input type="checkbox" name="<?php echo $dni; ?>_2" value="2" <?php echo (! empty($car) && strpos('2', $car) !== false) ? "checked" : ""; ?> /> 
				<select class="form-control input-sm" style="width: auto;" name="<?php echo $dni; ?>_2t">
					<option value=""></option>
					<?php
					$tutorUnidadCentro = mysqli_query($db_con, "SELECT unidad FROM FTUTORES WHERE tutor = '".$pro."' LIMIT 1");
					$rowTutorUnidadCentro = mysqli_fetch_array($tutorUnidadCentro);
					$tutorUnidad = $rowTutorUnidadCentro['unidad'];

					$unidadesCentro = mysqli_query($db_con, "SELECT nomunidad FROM unidades ORDER BY idcurso ASC, nomunidad ASC");
					while($rowUnidadesCentro = mysqli_fetch_array($unidadesCentro)) {
					?>
			  		<option value="<?php echo $rowUnidadesCentro['nomunidad']; ?>" <?php echo ($tutorUnidad == $rowUnidadesCentro['nomunidad']) ? "selected" : ""; ?>><?php echo $rowUnidadesCentro['nomunidad']; ?></option>
					<?php } ?>
		  		</select>
		  	</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_4" value="4" <?php echo (! empty($car) && strpos('4', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_15" value="9" <?php echo (! empty($car) && strpos('9', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_5" value="5" <?php echo (! empty($car) && strpos('5', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_6" value="6" <?php echo (! empty($car) && strpos('6', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_7" value="7" <?php echo (! empty($car) && strpos('7', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_8" value="8" <?php echo (! empty($car) && strpos('8', $car) !== false) ? "checked" : ""; ?> />
			</td>

			<?php if (isset($config['mod_bilingue']) && $config['mod_bilingue'] == 1): ?>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_9" value="a" <?php echo (! empty($car) && strpos('a', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<?php endif; ?>

			<?php if (isset($config['mod_convivencia']) && $config['mod_convivencia'] == 1): ?>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_10" value="b" <?php echo (! empty($car) && strpos('b', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<?php endif; ?>

			<?php if (isset($config['mod_biblioteca']) && $config['mod_biblioteca'] == 1): ?>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_11" value="c" <?php echo (! empty($car) && strpos('c', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<?php endif; ?>

			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_12" value="d" <?php echo (! empty($car) && strpos('d', $car) !== false) ? "checked" : ""; ?> />
			</td>
			<td class="text-center">
				<input type="checkbox" name="<?php echo $dni; ?>_13" value="f" <?php echo (! empty($car) && strpos('f', $car) !== false) ? "checked" : ""; ?> />
			</td>

			<td class="text-center">
				<?php if ($idea != "admin"): ?>
				<a href="cargos.php?borrar=1&dni_profe=<?php echo $dni; ?>" data-bb='confirm-delete'><span class="far fa-trash-alt fa-lg fa-fw"></span></a>
				<?php endif; ?>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>

	<button type="submit" class="btn btn-primary" name="enviar">Guardar cambios</button>
	<a class="btn btn-default" href="../xml/index.php">Volver</a>
</form>
            </div></div></div>
<?php include("../pie.php");?>
</body>
</html>
