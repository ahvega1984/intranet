<?php
require("../../bootstrap.php");

acl_acceso($_SESSION['cargo'], array('1', '2'));

// Obtenemos todas las unidades del centro que pertenezcan a ciclos formativos
$unidades = array();
if (acl_permiso($_SESSION['cargo'], array('2'))) {
  $result = mysqli_query($db_con, "SELECT `cursos`.`nomcurso`, `unidades`.`nomunidad` FROM `cursos` JOIN `unidades` ON `cursos`.`idcurso` = `unidades`.`idcurso` WHERE `unidades`.`nomunidad` = '".$_SESSION['mod_tutoria']['unidad']."' AND `cursos`.`nomcurso` LIKE '%F.P.%' ORDER BY `cursos`.`nomcurso` ASC, `unidades`.`nomunidad` ASC");
  if (! mysqli_num_rows($result)) {
    acl_acceso();
  }
}
else {
  $result = mysqli_query($db_con, "SELECT `cursos`.`nomcurso`, `unidades`.`nomunidad` FROM `cursos` JOIN `unidades` ON `cursos`.`idcurso` = `unidades`.`idcurso` WHERE `cursos`.`nomcurso` LIKE '%F.P.%' ORDER BY `cursos`.`nomcurso` ASC, `unidades`.`nomunidad` ASC");
}
while ($row = mysqli_fetch_array($result)) {
  $unidad = array (
    'nombre'  => $row['nomunidad'],
    'curso'   => $row['nomcurso']
  );

  array_push($unidades, $unidad);
}
unset($unidad);

// Obtenemos los módulos de los ciclos formativos
$asignaturas = array();
$result = mysqli_query($db_con, "SELECT DISTINCT `codigo`, `nombre`, `abrev`, `curso` FROM `asignaturas` WHERE `abrev` NOT LIKE '%\_%' AND `curso` LIKE '%F.P.%' ORDER BY `curso` ASC, `nombre` ASC");
while ($row = mysqli_fetch_array($result)) {
  $asignatura = array (
    'codigo'  => $row['codigo'],
    'nombre'  => $row['nombre'],
    'abrev'   => $row['abrev'],
    'curso'   => $row['curso']
  );

  array_push($asignaturas, $asignatura);
}
unset($asignatura);

// Obtenemos los trimestres
$trimestres = array();

$result_inicio_navidad = mysqli_query($db_con, "SELECT `fecha` FROM `festivos` WHERE `nombre` = 'Vacaciones de Navidad' ORDER BY `fecha` ASC LIMIT 1");
$row_inicio_navidad = mysqli_fetch_array($result_inicio_navidad);
$inicio_navidad = $row_inicio_navidad['fecha'];

$result_final_navidad = mysqli_query($db_con, "SELECT `fecha` FROM `festivos` WHERE `nombre` = 'Vacaciones de Navidad' ORDER BY `fecha` DESC LIMIT 1");
$row_final_navidad = mysqli_fetch_array($result_final_navidad);
$final_navidad = $row_final_navidad['fecha'];

$result_inicio_semana_santa = mysqli_query($db_con, "SELECT `fecha` FROM `festivos` WHERE `nombre` = 'Vacaciones Semana Santa' ORDER BY `fecha` ASC LIMIT 1");
$row_inicio_semana_santa = mysqli_fetch_array($result_inicio_semana_santa);
$inicio_semana_santa = $row_inicio_semana_santa['fecha'];

$result_final_semana_santa = mysqli_query($db_con, "SELECT `fecha` FROM `festivos` WHERE `nombre` = 'Vacaciones Semana Santa' ORDER BY `fecha` DESC LIMIT 1");
$row_final_semana_santa = mysqli_fetch_array($result_final_semana_santa);
$final_semana_santa = $row_final_semana_santa['fecha'];

$trimestre_1 = array(
  'inicio' => $config['curso_inicio'],
  'final' => $inicio_navidad
);

array_push($trimestres, $trimestre_1);
unset($trimestre_1);

$trimestre_2 = array(
  'inicio' => $final_navidad,
  'final' => $inicio_semana_santa
);

array_push($trimestres, $trimestre_2);
unset($trimestre_2);

$trimestre_3 = array(
  'inicio' => $final_semana_santa,
  'final' => $config['curso_fin']
);

array_push($trimestres, $trimestre_3);
unset($trimestre_3);


// Obtenemos los alumnos de las unidades seleccionadas
$alumnos = array();
foreach ($unidades as $unidad) {
  $result = mysqli_query($db_con, "SELECT `curso`, `unidad`, `apellidos`, `nombre`, `claveal`, `combasi` FROM `alma` WHERE `curso` = '".$unidad['curso']."' AND `unidad` = '".$unidad['nombre']."' ORDER BY `curso` ASC, `unidad` ASC, `apellidos` ASC, `nombre` ASC");
  while ($row = mysqli_fetch_array($result)) {

    $resultados_trimestre_1 = array();
    $resultados_trimestre_2 = array();
    $resultados_trimestre_3 = array();

    // Obtenemos los módulos en los que se encuentra matriculado
    $combasi = rtrim($row['combasi'], ':');
    $exp_combasi = explode(':', $combasi);

    foreach ($exp_combasi as $asignatura) {
      $key = array_search($asignatura, array_column($asignaturas, 'codigo'));
      if ($key !== FALSE) {

        // Buscamos las faltas de asistencia por módulo y por trimestre
        $faltas_trimestre_1 = array();
        $result_faltas_trimestre_1 = mysqli_query($db_con, "SELECT `FECHA`, `HORA` FROM `FALTAS` WHERE `CLAVEAL` = '".$row['claveal']."' AND `FECHA` >= '".$trimestres[0]['inicio']."' AND `FECHA` <= '".$trimestres[0]['final']."' AND `CODASI` = '".$asignaturas[$key]['codigo']."' AND `FALTA` = 'F' ORDER BY `FECHA` ASC, `HORA` ASC");
        while ($row_faltas_trimestre_1 = mysqli_fetch_array($result_faltas_trimestre_1)) {
          $falta_trimestre_1 = array(
            'fecha' => $row_faltas_trimestre_1['FECHA'],
            'hora'  => $row_faltas_trimestre_1['HORA'],
          );
          array_push($faltas_trimestre_1, $falta_trimestre_1);
        }
        $resultado_trimestre_1 = array(
          'asignatura' => $asignaturas[$key]['codigo'],
          'fechas' => $faltas_trimestre_1
        );
        array_push($resultados_trimestre_1, $resultado_trimestre_1);

        $faltas_trimestre_2 = array();
        $result_faltas_trimestre_2 = mysqli_query($db_con, "SELECT `FECHA`, `HORA` FROM `FALTAS` WHERE `CLAVEAL` = '".$row['claveal']."' AND `FECHA` >= '".$trimestres[1]['inicio']."' AND `FECHA` <= '".$trimestres[1]['final']."' AND `CODASI` = '".$asignaturas[$key]['codigo']."' AND `FALTA` = 'F' ORDER BY `FECHA` ASC, `HORA` ASC");
        while ($row_faltas_trimestre_2 = mysqli_fetch_array($result_faltas_trimestre_2)) {
          $falta_trimestre_2 = array(
            'fecha' => $row_faltas_trimestre_2['FECHA'],
            'hora'  => $row_faltas_trimestre_2['HORA'],
          );
          array_push($faltas_trimestre_2, $falta_trimestre_2);
        }
        $resultado_trimestre_2 = array(
          'asignatura' => $asignaturas[$key]['codigo'],
          'fechas' => $faltas_trimestre_2
        );
        array_push($resultados_trimestre_2, $resultado_trimestre_2);

        $faltas_trimestre_3 = array();
        $result_faltas_trimestre_3 = mysqli_query($db_con, "SELECT `FECHA`, `HORA` FROM `FALTAS` WHERE `CLAVEAL` = '".$row['claveal']."' AND `FECHA` >= '".$trimestres[2]['inicio']."' AND `FECHA` <= '".$trimestres[2]['final']."' AND `CODASI` = '".$asignaturas[$key]['codigo']."' AND `FALTA` = 'F' ORDER BY `FECHA` ASC, `HORA` ASC");
        while ($row_faltas_trimestre_3 = mysqli_fetch_array($result_faltas_trimestre_3)) {
          $falta_trimestre_3 = array(
            'fecha' => $row_faltas_trimestre_3['FECHA'],
            'hora'  => $row_faltas_trimestre_3['HORA'],
          );
          array_push($faltas_trimestre_3, $falta_trimestre_3);
        }
        $resultado_trimestre_3 = array(
          'asignatura' => $asignaturas[$key]['codigo'],
          'fechas' => $faltas_trimestre_3
        );
        array_push($resultados_trimestre_3, $resultado_trimestre_3);
      }
    }


    $alumno = array (
      'curso'       => $row['curso'],
      'unidad'      => $row['unidad'],
      'apellidos'   => $row['apellidos'],
      'nie'         => $row['claveal'],
      'nombre'      => $row['nombre'],
      'trimestre_1' => $resultados_trimestre_1,
      'trimestre_2' => $resultados_trimestre_2,
      'trimestre_3' => $resultados_trimestre_3,
    );

    array_push($alumnos, $alumno);
  }
  unset($alumno);
}

include("../../menu.php");
include("../../faltas/menu.php");
?>

  <div class="container">

    <div class="page-header">
      <h2>Informe sobre faltas de asistencia <small>Módulos de ciclos formativos</small></h2>
    </div>

    <?php include("menu_informes.php"); ?>

    <div class="row">

      <div class="col-sm-12">
        <?php foreach ($unidades as $unidad): ?>
          <h3 class="text-info"><?php echo $unidad['nombre']; ?> (<?php echo $unidad['curso']; ?>)</h3>

          <?php $numasig = 0; ?>
          <?php foreach ($asignaturas as $asignatura): ?>
          <?php if ($asignatura['curso'] == $unidad['curso']): ?>
          <?php $numasig++ ?>
          <?php endif; ?>
          <?php endforeach; ?>

          <table class="table table-condensed table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th rowspan="2">Alumno/a</th>
                <th rowspan="2">Trimestre</th>
                <th colspan="<?php echo $numasig; ?>">Módulos</th>
              </tr>
              <tr>
                <?php foreach ($asignaturas as $asignatura): ?>
                <?php if ($asignatura['curso'] == $unidad['curso']): ?>
                <th><?php echo $asignatura['abrev']; ?></th>
                <?php endif; ?>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php $nc = 0; ?>
              <?php foreach ($alumnos as $alumno): ?>
              <?php if ($alumno['curso'] == $unidad['curso'] && $alumno['unidad'] == $unidad['nombre']): ?>
              <?php $nc++; ?>
              <!-- 1er TRIMESTRE -->
              <tr>
                <td rowspan="3"><span class="label label-default"><?php echo $nc; ?></span> <?php echo $alumno['apellidos'].', '.$alumno['nombre']; ?></td>
                <td>
                  <p>1<sup>er</sup> trimestre</p>
                  <small class="text-muted"><?php echo strftime('%d %b, %Y', strtotime($trimestres[0]['inicio'])); ?> - <?php echo strftime('%d %b, %Y', strtotime($trimestres[0]['final'])); ?></small>
                </td>
                <?php foreach ($asignaturas as $asignatura): ?>
                <?php if ($asignatura['curso'] == $unidad['curso']): ?>
                <td>
                  <?php $key = array_search($asignatura['codigo'], array_column($alumno['trimestre_1'], 'asignatura')); ?>
                  <?php if ($key !== FALSE): ?>
                  <?php echo count($alumno['trimestre_1'][$key]['fechas']); ?>
                  <?php if (count($alumno['trimestre_1'][$key]['fechas'])): ?>
                  <a href="#" data-container="body" data-toggle="tooltip" data-placement="left" data-html="true" title="<?php foreach ($alumno['trimestre_1'][$key]['fechas'] as $fechas) { echo $fechas['fecha'].' a '.$fechas['hora'].'ª hora<br>'; }; ?>">
                    <i class="far fa-eye fa-fw"></i>
                  </a>
                  <?php endif; ?>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
                <?php endforeach; ?>
              </tr>
              <!-- 2º TRIMESTRE -->
              <tr>
                <td>
                  <p>2<sup>o</sup> trimestre</p>
                  <small class="text-muted"><?php echo strftime('%d %b, %Y', strtotime($trimestres[1]['inicio'])); ?> - <?php echo strftime('%d %b, %Y', strtotime($trimestres[1]['final'])); ?></small>
                </td>
                <?php foreach ($asignaturas as $asignatura): ?>
                <?php if ($asignatura['curso'] == $unidad['curso']): ?>
                  <td>
                    <?php $key = array_search($asignatura['codigo'], array_column($alumno['trimestre_2'], 'asignatura')); ?>
                    <?php if ($key !== FALSE): ?>
                    <?php echo count($alumno['trimestre_2'][$key]['fechas']); ?>
                    <?php if (count($alumno['trimestre_2'][$key]['fechas'])): ?>
                    <a href="#" data-container="body" data-toggle="tooltip" data-placement="left" data-html="true" title="<?php foreach ($alumno['trimestre_2'][$key]['fechas'] as $fechas) { echo $fechas['fecha'].' a '.$fechas['hora'].'ª hora<br>'; }; ?>">
                      <i class="far fa-eye fa-fw"></i>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
                <?php endforeach; ?>
              </tr>
              <!-- 3er TRIMESTRE -->
              <tr>
                <td>
                  <p>3<sup>er</sup> trimestre</p>
                  <small class="text-muted"><?php echo strftime('%d %b, %Y', strtotime($trimestres[2]['inicio'])); ?> - <?php echo strftime('%d %b, %Y', strtotime($trimestres[2]['final'])); ?></small>
                </td>
                <?php foreach ($asignaturas as $asignatura): ?>
                <?php if ($asignatura['curso'] == $unidad['curso']): ?>
                  <td>
                    <?php $key = array_search($asignatura['codigo'], array_column($alumno['trimestre_3'], 'asignatura')); ?>
                    <?php if ($key !== FALSE): ?>
                    <?php echo count($alumno['trimestre_3'][$key]['fechas']); ?>
                    <?php if (count($alumno['trimestre_3'][$key]['fechas'])): ?>
                    <a href="#" data-container="body" data-toggle="tooltip" data-placement="left" data-html="true" title="<?php foreach ($alumno['trimestre_3'][$key]['fechas'] as $fechas) { echo $fechas['fecha'].' a '.$fechas['hora'].'ª hora<br>'; }; ?>">
                      <i class="far fa-eye fa-fw"></i>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
                <?php endforeach; ?>
              </tr>
              <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>

        <?php endforeach; ?>
      </div><!-- /.col-sm-12 -->

    </div><!-- /.row -->

  </div><!-- /.container -->

<?php include("../../pie.php"); ?>

  <script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
  </script>

</body>
</html>
