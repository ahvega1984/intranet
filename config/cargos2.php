<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$cargos_directivos = array('Director/a', 'Vicedirector/a', 'Secretario/a', 'Jefe/a de estudios', 'Jefe/a de estudios adjunto', 'Jefe de estudios (EPA)', 'Jefe de estudios adjunto (EPA)');

// OBTENEMOS AL PERSONAL DEL CENTRO
$personal = array();
$result = mysqli_query($db_con, "SELECT `departamentos`.`nombre`, `departamentos`.`dni`, `departamentos`.`departamento`, `departamentos`.`cargo`, `departamentos`.`idea`, `c_profes`.`telefono`, `c_profes`.`correo` FROM `departamentos` JOIN `c_profes` ON `departamentos`.`idea` = `c_profes`.`idea` ORDER BY `departamentos`.`departamento` ASC, `departamentos`.`nombre` ASC");
while ($row = mysqli_fetch_array($result)) {

    $empleado = array(
        'nombre'   			=> $row['nombre'],
        'dni'      			=> $row['dni'],
				'departamento'  => $row['departamento'],
				'cargo'     		=> $row['cargo'],
				'idea'     			=> $row['idea'],
				'telefono'     	=> $row['telefono'],
				'correoe'     	=> $row['correo']
    );

    array_push($personal, $empleado);
}
mysqli_free_result($result);
unset($empleado);

// OBTENEMOS UNIDADES DEL CENTRO
$cursos = array();
$result = mysqli_query($db_con, "SELECT `idcurso`, `nomcurso` FROM `cursos` ORDER BY `nomcurso` ASC");
while ($row = mysqli_fetch_array($result)) {

		$unidades = array();
		$result_unidades = mysqli_query($db_con, "SELECT `nomunidad` FROM `unidades` WHERE `idcurso` = '".$row['idcurso']."' ORDER BY `nomunidad` ASC");
		while ($row_unidades = mysqli_fetch_array($result_unidades)) {
			$unidad = array(
					'nombre'			=> $row_unidades['nomunidad']
	    );

			array_push($unidades, $unidad);
		}
		unset($unidad);

    $curso = array(
        'nombre'   			=> $row['nomcurso'],
				'unidades'			=> $unidades
    );

    array_push($cursos, $curso);
}
mysqli_free_result($result);
unset($unidades);
unset($curso);

include("../menu.php");
?>
	<div class="container">

		<div class="page-header">
		  <h2>Administración <small>Perfiles de Profesores</small></h2>
		</div>

		<div class="row">

			<div class="col-sm-12">

				<form action="" method="post">

					<table class="table table-bordered table-condensed table-striped text-center" style="font-size: 0.85em;">
						<tbody>
							<?php foreach ($personal as $empleado): ?>
							<?php if ($empleado['departamento'] != 'Admin' && $empleado['departamento'] != 'Administracion' && $empleado['departamento'] != 'Conserjeria' && $empleado['departamento'] != 'Educador'): ?>
							<tr>
								<td rowspan="2" class="text-left">
									<div class="pull-left" style="margin-right: 10px; margin-bottom: 10px;">
											<?php if ($foto = obtener_foto_profesor($empleado['idea'])): ?>
											<img class="img-thumbnail" src="../xml/fotos_profes/<?php echo $foto; ?>" style="width: 42px !important;" alt="">
											<?php else: ?>
											<span class="img-thumbnail far fa-user fa-fw fa-2x" style="width: 42px !important;"></span>
											<?php endif; ?>
									</div>

									<p>
										<strong><?php echo $empleado['nombre']; ?></strong><br>
										<small class="text-muted"><?php echo $empleado['departamento']; ?></small>
									</p>

									<div class="clearfix"></div>
									<div class="text-muted">
										<small><i class="fas fa-phone fa-fw"></i> <?php echo (! empty($empleado['telefono'])) ? $empleado['telefono'] : '<i>Sin registrar</i>'; ?><br>
										<i class="fas fa-envelope fa-fw"></i> <?php echo (! empty($empleado['correoe'])) ? $empleado['correoe'] : '<i>Sin registrar</i>'; ?></small>
									</div>
								</td>
								<?php if (acl_permiso($_SESSION['cargo'], array('0')) || $_SESSION['ide'] == 'admin'): ?>
								<th>Admin</th>
								<?php endif; ?>
								<th>Equipo directivo</th>
								<th>Tutoría</th>
								<th>J. Dpto.</th>
								<th>ETCP</th>
								<th>DACE</th>
								<th>Orien.</th>
								<th>Conv.</th>
								<th>Biblio.</th>
								<th>Genero</th>
								<th>DFEIE</th>
							</tr>
							<tr>
								<?php if (acl_permiso($_SESSION['cargo'], array('0')) || $_SESSION['ide'] == 'admin'): ?>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_admin_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_admin_'.$empleado['idea']; ?>" name="<?php echo 'perm_admin_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], '0') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<?php endif; ?>
								<td>
									<select class="form-control input-sm" id="<?php echo 'perm_directivo_'.$empleado['idea']; ?>" name="<?php echo 'perm_directivo_'.$empleado['idea']; ?>">
										<option value=""></option>
										<?php foreach ($cargos_directivos as $cargo_directivo): ?>
										<option value="<?php echo $cargo_directivo; ?>" <?php echo ($pos = strpos($empleado['cargo'], '1') !== false) ? 'selected' : ''; ?>><?php echo $cargo_directivo; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<select class="form-control input-sm" id="<?php echo 'perm_tutor_'.$empleado['idea']; ?>" name="<?php echo 'perm_tutor_'.$empleado['idea']; ?>">
										<option value=""></option>
										<?php foreach ($cursos as $curso): ?>
										<optgroup label="<?php echo $curso['nombre']; ?>">
											<?php foreach ($curso['unidades'] as $unidad): ?>
											<option value="<?php echo $unidad['nombre']; ?>" <?php echo ($pos = strpos($empleado['cargo'], '2') !== false) ? 'checked' : ''; ?>><?php echo $unidad['nombre']; ?></option>
											<?php endforeach; ?>
										</optgroup>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_jefedepto_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_jefedepto_'.$empleado['idea']; ?>" name="<?php echo 'perm_jefedepto_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], '4') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_etcp_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_etcp_'.$empleado['idea']; ?>" name="<?php echo 'perm_etcp_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], '9') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_dace_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_dace_'.$empleado['idea']; ?>" name="<?php echo 'perm_dace_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], '5') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_orientacion_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_orientacion_'.$empleado['idea']; ?>" name="<?php echo 'perm_orientacion_'.$empleado['idea']; ?>" value="1" <?php echo (($pos = strpos($empleado['departamento'], 'Orienta') !== false) || ($pos = strpos($empleado['departamento'], 'Orienta') !== false)) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_convivencia_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_convivencia_'.$empleado['idea']; ?>" name="<?php echo 'perm_convivencia_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], 'b') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_biblioteca_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_biblioteca_'.$empleado['idea']; ?>" name="<?php echo 'perm_biblioteca_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], 'c') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_genero_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_genero_'.$empleado['idea']; ?>" name="<?php echo 'perm_genero_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], 'd') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
								<td>
									<div class="checkbox">
										<label for="<?php echo 'perm_dfeie_'.$empleado['idea']; ?>">
											<input type="checkbox" id="<?php echo 'perm_dfeie_'.$empleado['idea']; ?>" name="<?php echo 'perm_dfeie_'.$empleado['idea']; ?>" value="1" <?php echo ($pos = strpos($empleado['cargo'], 'f') !== false) ? 'checked' : ''; ?>>
										</label>
									</div>
								</td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>


					<div class="hidden-print">
						<button type="submit" class="btn btn-primary" name="submit">Guardar cambios</button>
						<a class="btn btn-default" href="../xml/index.php">Volver</a>
					</div>
				</form>


			</div>

		</div>



	</div>

	<?php include("../pie.php");?>
</body>
</html>
