<?php
require('../../bootstrap.php');

// Redireccionamos al profesorado si no es PT o refuerzo a otra página
$result_pt_o_ref = mysqli_query($db_con, "SELECT id FROM horw WHERE (c_asig = '21' OR c_asig = '136') AND prof = '$pr' LIMIT 1");
if (! mysqli_num_rows($result_pt_o_ref)) {
  header('Location:'.'seleccion_alumnos.php');
  exit;
}

if (isset($_POST['guardar_cambios'])) {

    $asignatura_ant = "";
    $unidad_ant = "";
    $array_nc = array();

    $flag_primera_vez = 1;

    foreach($_POST as $key => $val) {

        $exp_key = explode(';', $key);
        $asignatura = $exp_key[1];
        $unidad = $exp_key[2];
        $unidad = str_replace('_', ' ', $unidad);
        $nc = $exp_key[3];

        if ($asignatura != $asignatura_ant || $unidad != $unidad_ant) {
            // Saltamos este bloque la primera vez que se ejecuta foreach o si key corresponde al botón de envío de formulario
            if ($flag_primera_vez > 1 || $key != 'guardar_cambios') {
                $result_alumnos_grupo = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal FROM alma WHERE alma.unidad = '".$unidad_ant."' ORDER BY alma.apellidos ASC, alma.nombre ASC");
                $total_alumnos_grupo = mysqli_num_rows($result_alumnos_grupo);

                $result_alumnos = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal FROM alma WHERE alma.unidad = '".$unidad_ant."' AND alma.combasi LIKE '%".$asignatura_ant."%' ORDER BY alma.apellidos ASC, alma.nombre ASC");
                $total_alumnos = mysqli_num_rows($result_alumnos);
                $alumnos_seleccionados = count($array_nc);

                if (($total_alumnos != $alumnos_seleccionados) || ($total_alumnos != $total_alumnos_grupo)) {

                    $nc_separado_por_comas = implode(",", $array_nc);

                    // Comprobamos si el profesor ya seleccionó alumnos. En ese caso actualizamos los datos, si no, insertamos en la tabla
                    $result_seleccion = mysqli_query($db_con, "SELECT id FROM grupos WHERE profesor = '$pr' AND asignatura = '$asignatura_ant' AND curso = '$unidad_ant'");
                    if (mysqli_num_rows($result_seleccion)) {
                        $row_seleccion = mysqli_fetch_array($result_seleccion);
                        $id_seleccion = $row_seleccion['id'];
                        mysqli_query($db_con, "UPDATE grupos SET alumnos = '$nc_separado_por_comas' WHERE id = '$id_seleccion'");
                    }
                    else {
                        if (!empty($asignatura_ant) && !empty($nc_separado_por_comas)) {
                        mysqli_query($db_con, "INSERT INTO grupos (profesor, asignatura, curso, alumnos) VALUES ('$pr', '$asignatura_ant', '$unidad_ant', '$nc_separado_por_comas')") or die (mysqli_error($db_con));
                        }
                    }

                }
                else {
                    $result_seleccion = mysqli_query($db_con, "SELECT id FROM grupos WHERE profesor = '$pr' AND asignatura = '$asignatura_ant' AND curso = '$unidad_ant'");
                    if (mysqli_num_rows($result_seleccion)) {
                        $row_seleccion = mysqli_fetch_array($result_seleccion);
                        $id_seleccion = $row_seleccion['id'];

                        mysqli_query($db_con, "DELETE FROM grupos WHERE id = '$id_seleccion'");
                    }
                }

            }

            $array_nc = array();
            array_push($array_nc, $nc);
        }
        else {
            array_push($array_nc, $nc);
        }

        $asignatura_ant = $asignatura;
        $unidad_ant = $unidad;

        $flag_primera_vez = 2;
    }

    // Añadimos al profesor en la tabla profesores para que pueda rellenar informes. Previamente eliminamos para evitar duplicidad o cambios que haya realizado
    mysqli_query($db_con, "DELETE FROM profesores WHERE profesor = '$pr'");

    $result_grupos_profesor = mysqli_query($db_con, "SELECT DISTINCT curso, asignatura FROM grupos WHERE profesor = '$pr'");
    while ($row_grupos_profesor = mysqli_fetch_array($result_grupos_profesor)) {
      $result_asignatura = mysqli_query($db_con, "SELECT nombre FROM asignaturas WHERE codigo = '".$row_grupos_profesor['asignatura']."' LIMIT 1");
      $row_asignatura = mysqli_fetch_array($result_asignatura);
      $nomasignatura = $row_asignatura['nombre'];

      $result_curso = mysqli_query($db_con, "SELECT cursos.nomcurso FROM unidades JOIN cursos ON unidades.idcurso = cursos.idcurso WHERE unidades.nomunidad = '".$row_grupos_profesor['curso']."' LIMIT 1");
      $row_curso = mysqli_fetch_array($result_curso);
      $nomcurso = trim($row_curso['nomcurso']);

      mysqli_query($db_con, "INSERT INTO profesores (nivel, materia, grupo, profesor) VALUES ('$nomcurso', '$nomasignatura', '".$row_grupos_profesor['curso']."', '$pr')");
    }

}

if (isset($_POST['restablecer_seleccion'])) {
    $result_grupos = mysqli_query($db_con, "SELECT id FROM grupos WHERE profesor = '$pr'");
    if (mysqli_num_rows($result_grupos)) {
        mysqli_query($db_con, "DELETE FROM grupos WHERE profesor = '$pr'");
        mysqli_query($db_con, "DELETE FROM profesores WHERE profesor = '$pr'");
    }
}

include("../../menu.php");
?>

    <div class="container">

        <!-- TITULO DE LA PAGINA -->
        <div class="page-header">
            <h2>Mi alumnado <small>Selección de alumnos por materias</small></h2>
        </div>

        <!-- SCAFFOLDING -->
        <div class="row">

            <div class="col-sm-12">
                <div class="alert alert-info">
                    <p>Marca aquellos alumnos a los que impartes una actividad de Refuerzo Pedagógico o Pedagogía Terapéutica. Esto permitirá que puedas ver las notificaciones de Informes de tareas o tutoría, o que aparezca en tus listados de grupos y faltas de asistencia.</p>
                </div>

                <?php
                $esREF = 0;
                $esPT = 0;
                $result_pt_o_ref = mysqli_query($db_con, "SELECT DISTINCT c_asig FROM horw WHERE (c_asig = '21' OR c_asig = '136') AND prof = '$pr'");
                while ($row_pt_o_ref = mysqli_fetch_array($result_pt_o_ref)) {
                  if ($row_pt_o_ref['c_asig'] == '136') $esPT = 1;
                  if ($row_pt_o_ref['c_asig'] == '21') $esREF = 1;
                }

                if ($esPT && ! $esREF) {
                  $result = mysqli_query($db_con, "SELECT DISTINCT curso, codigo, nombre, abrev FROM asignaturas WHERE codigo = '136'");
                }
                elseif ($esREF && ! $esPT) {
                  $result = mysqli_query($db_con, "SELECT DISTINCT curso, codigo, nombre, abrev FROM asignaturas WHERE codigo = '21'");
                }
                else {
                  $result = mysqli_query($db_con, "SELECT DISTINCT curso, codigo, nombre, abrev FROM asignaturas WHERE codigo = '21' OR codigo = '136'");
                }
                ?>
                <?php if (mysqli_num_rows($result)): ?>
                <form action="" method="post">
                    <div class="panel-group" id="materias" role="tablist" aria-multiselectable="true">

                        <?php $i = 0; ?>
                        <?php while ($row = mysqli_fetch_array($result)): ?>

                        <?php $result_alumnos = mysqli_query($db_con, "SELECT alma.unidad, alma.apellidos, alma.nombre, alma.claveal FROM alma WHERE alma.curso = '".$row['curso']."' ORDER BY alma.unidad, alma.apellidos ASC, alma.nombre ASC"); ?>
                        <?php $total_alumnos_unidad = mysqli_num_rows($result_alumnos); ?>
                        <?php
                        $nc_alumnos_seleccionados = array();
                        $result_alumnos_seleccionados = mysqli_query($db_con, "SELECT alumnos FROM grupos WHERE profesor = '".$pr."' AND asignatura = '".$row['codigo']."'");
                        if (mysqli_num_rows($result_alumnos_seleccionados)) {
                          while ($row_alumnos_seleccionados = mysqli_fetch_array($result_alumnos_seleccionados)) {
                            $alumnos_seleccionados = $row_alumnos_seleccionados['alumnos'];
                            $alumnos_seleccionados = rtrim($alumnos_seleccionados, ',');
                            $nc_alumnos_seleccionados_unidad = explode(',', $alumnos_seleccionados);
                            $total_alumnos_seleccionados = count($nc_alumnos_seleccionados);

                            array_push($nc_alumnos_seleccionados, $nc_alumnos_seleccionados_unidad);
                          }
                        }
                        else {
                          $total_alumnos_seleccionados = 0;
                        }
                        ?>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading<?php echo $i; ?>">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#materias" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>" style="display: block;">
                                    <?php echo $row['curso'].' - '.$row['nombre']; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
                                <div class="panel-body">
                                    <?php mysqli_data_seek($result_alumnos, 0); ?>
                                    <?php while ($row_alumno = mysqli_fetch_array($result_alumnos)): ?>
                                    <?php
                                    $nombre_checkbox = 'checkbox;'.$row['codigo'].';'.$row_alumno['unidad'].';'.$row_alumno['claveal'];
                                    if (mysqli_num_rows($result_alumnos_seleccionados) > 0) {
                                      $checkbox_checked = "";
                                      foreach ($nc_alumnos_seleccionados as $nc_alumno_seleccionado) {
                                        for ($j=0; $j < count($nc_alumno_seleccionado); $j++) {
                                          if ($nc_alumno_seleccionado[$j] == $row_alumno['claveal']) {
                                            $checkbox_checked = "checked";
                                          }
                                        }
                                      }
                                    } else {
                                      $checkbox_checked = "";
                                    }
                                    ?>
                                    <div class="checkbox">
                                        <label for="<?php echo $nombre_checkbox; ?>">
                                            <input type="checkbox" name="<?php echo $nombre_checkbox; ?>" id="<?php echo $nombre_checkbox; ?>" value="1" <?php echo $checkbox_checked; ?>> <span class="label label-info"><?php echo $row_alumno['unidad']; ?></span> <?php echo $row_alumno['apellidos'].', '.$row_alumno['nombre']; ?>
                                        </label>
                                    </div>
                                    <?php endwhile; ?>

                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                        <?php endwhile; ?>

                    </div>

                    <button type="submit" class="btn btn-primary" name="guardar_cambios">Guardar cambios</button>
                    <button type="submit" class="btn btn-default" name="restablecer_seleccion">Restablecer selección</button>
                </form>

                <?php else: ?>

                <div class="well text-center">
                    <p class="lead">No tienes materias asignadas.</p>
                    <p>O bien no has registrado el horario en Séneca o aún no se ha actualizado la información en la Intranet.</p>
                    <p>Contacta con algún miembro del equipo directivo para actualizar la información.</p>
                </div>

                <?php endif; ?>
            </div>

        </div><!-- /.row -->

    </div><!-- /.container -->

	<?php include("../../pie.php"); ?>

</body>
</html>
