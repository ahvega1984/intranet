<?php
require("../../bootstrap.php");

if (file_exists('config.php')) {
	include('config.php');
}

if (! isset($config['convivencia']['compromiso_convivencia']) && ! $config['convivencia']['compromiso_convivencia']) {
  acl_acceso();
}

acl_acceso($_SESSION['cargo'], array(1));


// OBTENEMOS TODOS LOS ALUMNOS DEL CENTRO EDUCATIVO
$alumnos = array();
$result = mysqli_query($db_con, "SELECT `unidad`, `apellidos`, `nombre`, `claveal` FROM `alma` ORDER BY `unidad` ASC, `apellidos` ASC, `nombre` ASC");
while ($row = mysqli_fetch_array($result)) {

    $alumno = array(
      'unidad'      => $row['unidad'],
      'apellidos'   => $row['apellidos'],
      'nombre'      => $row['nombre'],
      'nie'         => $row['claveal']
    );

    array_push($alumnos, $alumno);
}
mysqli_free_result($result);
unset($alumno);

if (acl_permiso($_SESSION['cargo'], array(1))) {

  // NUEVO COMPROMISO
  if (isset($_POST['nuevoCompromiso'])) {
    $nie = htmlspecialchars($_POST['nie']);
    $nie = mysqli_real_escape_string($db_con, $nie);

    $result = mysqli_query($db_con, "SELECT `fecha` FROM `compromiso_convivencia` WHERE `nie` = '$nie' LIMIT 1");
    if (! mysqli_num_rows($result)) {
      mysqli_query($db_con, "INSERT INTO `compromiso_convivencia` (`nie`, `fecha`) VALUES ('$nie', NOW())");
    }
  }

  // ELIMINAR COMPROMISO
  if (isset($_GET['accion']) && $_GET['accion'] == "eliminar" && isset($_GET['nie'])) {
  	$nie = htmlspecialchars($_GET['nie']);
    $nie = mysqli_real_escape_string($db_con, $nie);

  	$result = mysqli_query($db_con, "SELECT `fecha` FROM `compromiso_convivencia` WHERE `nie` = '$nie' LIMIT 1");
  	if (mysqli_num_rows($result)) {
  		mysqli_query($db_con, "DELETE FROM `compromiso_convivencia` WHERE `nie` = '$nie' LIMIT 1");
  	}
    header("Location:"."compromiso.php");
  }
}

// OBTENEMOS TODOS LOS COMPROMISOS DE CONVIVENCIA
$compromisos = array();
$result = mysqli_query($db_con, "SELECT `alma`.`claveal`, `alma`.`apellidos`, `alma`.`nombre`, `alma`.`unidad`, `compromiso_convivencia`.`fecha` FROM `compromiso_convivencia` JOIN `alma` ON `compromiso_convivencia`.`nie` = `alma`.`claveal` ORDER BY `compromiso_convivencia`.`fecha` DESC");
if (mysqli_num_rows($result)) {
  while ($row = mysqli_fetch_array($result)) {
    $compromiso = array(
      "apellidos" => $row['apellidos'],
      "nombre" => $row['nombre'],
      "nie" => $row['claveal'],
      "unidad" => $row['unidad'],
      "fecha" => $row['fecha'],
    );

    array_push($compromisos, $compromiso);
  }
  unset($compromiso);
}


include("../../menu.php");
include("menu.php");
?>

  <div class="container">

    <div class="page-header">
      <h2>Problemas de convivencia <small>Compromisos de convivencia</small></h2>
    </div>

    <div class="row">

      <div class="col-sm-12">

        <?php if (acl_permiso($_SESSION['cargo'], array(1))): ?>
        <br>
        <div class="hidden-print">
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCompromisoConvivencia">Nuevo compromiso</a>
        </div>
        <br>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Alumno/a</th>
              <th>Unidad</th>
              <th>Fecha inicio de compromiso</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $numrow = 1; ?>
            <?php foreach ($compromisos as $compromiso): ?>
            <tr>
              <td><?php echo $compromiso['apellidos'].", ".$compromiso['nombre']; ?></td>
              <td><?php echo $compromiso['unidad']; ?></td>
              <td><?php echo $compromiso['fecha']; ?></td>
              <td>
      					<a href="compromisos.php?accion=eliminar&nie=<?php echo $compromiso['nie']; ?>" class="btn btn-danger btn-sm" data-bb="confirm-delete" data-bs="tooltip" title="Eliminar"><i class="far fa-trash-alt fa-fw fa-lg"></i></a>
              </td>
            </tr>
            <?php $numrow++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div><!-- /.col-sm-12 -->

    </div><!-- /.row -->

  </div><!-- /.container -->

  <?php if (acl_permiso($_SESSION['cargo'], array(1))): ?>
  <!-- MODAL NUEVO COMPROMISO DE CONVIVENCIA -->
  <div class="modal fade" id="modalCompromisoConvivencia" tabindex="-1" role="dialog" aria-labelledby="compromisoConvivencia">
      <div class="modal-dialog" role="document">
          <form action="" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="compromisoConvivencia">Nuevo compromiso de convivencia</h4>
                  </div>
                  <div class="modal-body">

                      <div class="form-group-edit"></div>

                      <div class="form-group">
                          <label for="nie">Alumno/a</label>
                          <select class="form-control" id="nie" name="nie">
                              <option value=""></option>
                              <?php foreach ($alumnos as $alumno): ?>
                              <option value="<?php echo $alumno['nie']; ?>"><?php echo '['.$alumno['unidad'].'] '.$alumno['apellidos'].', '.$alumno['nombre']; ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                      <button type="submit" name="nuevoCompromiso" class="btn btn-primary">Activar compromiso</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
  <?php endif; ?>

  <?php include("../../pie.php"); ?>

</body>
</html>
