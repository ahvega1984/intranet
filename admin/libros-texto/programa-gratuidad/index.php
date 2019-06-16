<?php
require('../../../bootstrap.php');
acl_acceso($_SESSION['cargo'], array(1, 2));

// OBTENEMOS TODOS LOS NIVELES DEL CENTRO EDUCATIVO
$niveles = array();
if (acl_permiso($_SESSION['cargo'], array(1))) {
    $result = mysqli_query($db_con, "SELECT `idcurso`, `nomcurso` FROM `cursos` WHERE (`nomcurso` LIKE '%E.S.O.' OR `nomcurso` LIKE '%F.P.B.%') ORDER BY `idcurso` ASC");
}
else {
    $result = mysqli_query($db_con, "SELECT `cursos`.`idcurso`, `cursos`.`nomcurso` FROM `cursos` JOIN `unidades` ON `cursos`.`idcurso` = `unidades`.`idcurso` WHERE `unidades`.`nomunidad` = '".$_SESSION['mod_tutoria']['unidad']."' LIMIT 1");
}
while ($row = mysqli_fetch_array($result)) {

    $nivel = array(
        'id'          => $row['idcurso'],
        'nombre'      => $row['nomcurso']
    );

    array_push($niveles, $nivel);
}
mysqli_free_result($result);
unset($nivel);

// Procesamos la variable de selección de curso

if (isset($_GET['curso'])) {
    $curso = urldecode($_GET['curso']);
}
else {
    $curso = $niveles[0]['nombre'];
}

// OBTENEMOS TODOS LOS LIBROS DE TEXTO DEL NIVEL SELECCIONADO
$libros = array();
$result_libros = mysqli_query($db_con, "SELECT `materia`, `programaGratuidad` FROM `libros_texto` WHERE `nivel` = '$curso'");
while ($row_libros_materias = mysqli_fetch_array($result_libros)) {
    if ($row_libros_materias['programaGratuidad']) {
        $libros[] = $row_libros_materias['materia'];
    }
}

// OBTENEMOS TODAS LAS MATERIAS QUE SE IMPARTEN EN EL CENTRO EDUCATIVO
$materias = array();
$result = mysqli_query($db_con, "SELECT DISTINCT `codigo`, `nombre`, `abrev` FROM `asignaturas` WHERE `curso` = '$curso' AND `abrev` NOT LIKE '%\_%' AND `nombre` <> 'Tutoría con Alumnos' ORDER BY `nombre` ASC");
while ($row = mysqli_fetch_array($result)) {

    // Excluimos las materias que no tienen libro de texto asignado
    if (in_array($row['nombre'], $libros)) {
         $materia = array(
            'codigo'        => $row['codigo'],
            'nombre'        => $row['nombre'],
            'abreviatura'   => $row['abrev']
        );

        array_push($materias, $materia);
    }

}
mysqli_free_result($result);
unset($materia);

// OBTENEMOS TODAS LAS UNIDADES DEL CENTRO EDUCATIVO
$unidades = array();
if (acl_permiso($_SESSION['cargo'], array(1))) {
    $result = mysqli_query($db_con, "SELECT `unidades`.`idunidad`, `unidades`.`nomunidad` FROM `unidades` JOIN `cursos` ON `unidades`.`idcurso` = `cursos`.`idcurso` WHERE `cursos`.`nomcurso` = '$curso' ORDER BY `unidades`.`nomunidad` ASC");
}
else {
    $result = mysqli_query($db_con, "SELECT `unidades`.`idunidad`, `unidades`.`nomunidad` FROM `unidades` JOIN `cursos` ON `unidades`.`idcurso` = `cursos`.`idcurso` WHERE `unidades`.`nomunidad` = '".$_SESSION['mod_tutoria']['unidad']."' LIMIT 1");
}
while ($row = mysqli_fetch_array($result)) {

    $unidad = array(
        'id'          => $row['idunidad'],
        'nombre'      => $row['nomunidad']
    );

    array_push($unidades, $unidad);
}
mysqli_free_result($result);
unset($unidad);

// Procesamos la variable de selección de unidad
if (acl_permiso($_SESSION['cargo'], array(2))) {
    $grupo = $_SESSION['mod_tutoria']['unidad'];
}
elseif (isset($_GET['grupo']) && ! empty($_GET['grupo'])) {
    $grupo = urldecode($_GET['grupo']);

    if (! in_array($grupo, array_column($unidades, 'nombre'))) {
        unset($grupo);
    }
}

// OBTENEMOS TODOS LOS ALUMNOS DEL NIVEL Y UNIDAD SELECCIONADOS
$alumnos = array();

if (isset($grupo)) {
    $result = mysqli_query($db_con, "SELECT `claveal`, `apellidos`, `nombre`, `dni`, `curso`, `unidad`, `combasi` FROM `alma` WHERE `unidad` = '$grupo' ORDER BY `unidad` ASC, `apellidos` ASC, `nombre` ASC");
}
else {
    $result = mysqli_query($db_con, "SELECT `claveal`, `apellidos`, `nombre`, `dni`, `curso`, `unidad`, `combasi` FROM `alma` WHERE `curso` = '$curso' ORDER BY `unidad` ASC, `apellidos` ASC, `nombre` ASC");
}

while ($row = mysqli_fetch_array($result)) {

    $alumno = array(
        'nivel'         => $row['curso'],
        'unidad'        => $row['unidad'],
        'claveal'       => $row['claveal'],
        'alumno'        => $row['apellidos'].', '.$row['nombre'],
        'dni'           => $row['dni'],
        'combasi'       => $row['combasi']
    );

    array_push($alumnos, $alumno);
}
mysqli_free_result($result);
unset($alumno);

// OBTENEMOS TODOS LOS REGISTROS DE LA TABLA LIBROS_TEXTO_ALUMNOS DEL GRUPO DE ALUMNOS SELECCIONADO
$estado_libros = array();
foreach ($alumnos as $alumno) {
    $result = mysqli_query($db_con, "SELECT `materia`, `estado`, `devuelto`, `fecha` FROM `libros_texto_alumnos` WHERE `claveal` = '".$alumno['claveal']."'");
    while ($row = mysqli_fetch_array($result)) {

        $estado_libro = array(
            'claveal'   => $alumno['claveal'],
            'materia'   => $row['materia'],
            'estado'    => $row['estado'],
            'devuelto'  => $row['devuelto']
        );

        array_push($estado_libros, $estado_libro);
    }
    mysqli_free_result($result);
    unset($estado_libro);
}

include('../../../menu.php');
include('../menu.php');
?>
    <div class="container">

        <div class="page-header">
            <h2>Libros de texto <small>Programa de Gratuidad en Libros de Texto</small></h2>
        </div>

        <div class="row">

            <div class="col-sm-12">
                <?php if (acl_permiso($_SESSION['cargo'], array(1))): ?>
                <form action="" method="GET">
                    <div class="well">

                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="curso">Curso:</label>
                                    <select class="form-control" name="curso" id="curso" onchange="submit()">
                                        <?php foreach ($niveles as $nivel): ?>
                                        <option value="<?php echo $nivel['nombre']; ?>"<?php echo (isset($curso) && $curso == $nivel['nombre']) ? ' selected' : ''; ?>><?php echo $nivel['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label for="curso">Unidad:</label>
                                    <select class="form-control" name="grupo" id="grupo" onchange="submit()">
                                        <option value="">Todas las unidades</option>
                                        <?php foreach ($unidades as $unidad): ?>
                                        <option value="<?php echo $unidad['nombre']; ?>"<?php echo (isset($grupo) && $grupo == $unidad['nombre']) ? ' selected' : ''; ?>><?php echo $unidad['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>
                <?php endif; ?>

                <br>

                <h3><?php echo (isset($grupo)) ? $grupo.' ('.$curso.')' : $curso; ?></h3>

                <?php
                $numalumnos = 0;
                foreach ($alumnos as $alumno) {
                    if ($alumno['nivel'] == $curso) {
                        $numalumnos++;
                    }
                }
                ?>
                <?php if ($numalumnos): ?>
                <style type="text/css">
                ul.dropdown-menu-form>li {
                    padding: 1px 20px !important;
                    display: block;
                    clear: both;
                    white-space: nowrap;
                }
                ul.dropdown-menu-form>li:hover {
                    background-color: #2c3e50;
                    color: #ffffff;
                }
                </style>

                <div class="hidden-xs">
                    <?php if (acl_permiso($_SESSION['cargo'], array(1, 2))): ?>
                    <a href="certificado.php?curso=<?php echo $curso; ?><?php echo (isset($grupo)) ? '&grupo='.$grupo : ''; ?>" class="btn btn-primary btn-sm btn-print-cert" target="_blank"><span class="fas fa-print fa-lg fa-fw"></span> Imprimir certificados</a>
                    <?php endif; ?>
                </div>

                <br>

                <form action="" method="POST">
                    <table class="table table-bordered table-condensed table-vcentered table-striped" style="font-size: 0.85em;">
                        <tbody>
                            <?php foreach ($alumnos as $alumno): ?>
                            <?php if ($alumno['nivel'] == $curso): ?>
                            <tr class="hidden-xs">
                              <th></th>
                              <?php foreach ($materias as $materia): ?>
                              <th class="text-center"><?php echo $materia['abreviatura']; ?></th>
                              <?php endforeach; ?>
                              <?php if (acl_permiso($_SESSION['cargo'], array(1))): ?>
                              <th></th>
                            </tr>
                            <?php endif; ?>
                            <tr class="visible-xs">
                                <th colspan="<?php echo count($materias); ?>">
                                    <div class="pull-left" style="margin-right: 10px;">
                                        <?php if ($foto = obtener_foto_alumno($alumno['claveal'])): ?>
                                        <img class="img-thumbnail" src="../../../xml/fotos/<?php echo $foto; ?>" style="width: 42px !important;" alt="">
                                        <?php else: ?>
                                        <span class="img-thumbnail far fa-user fa-fw fa-2x" style="width: 42px !important;"></span>
                                        <?php endif; ?>
                                    </div>

                                    <p><strong><?php echo $alumno['alumno']; ?></strong></p>
                                    <p class="text-muted">
                                        <strong>Unidad:</strong> <?php echo $alumno['unidad']; ?> &middot; <strong>NIE:</strong> <?php echo $alumno['claveal']; ?> &middot; <strong>DNI:</strong> <?php echo (! empty($alumno['dni'])) ? $alumno['dni'] : 'Sin registrar'; ?>
                                    </p>
                                </th>
                            </tr>
                            <tr class="visible-xs">
                                <?php foreach ($materias as $materia): ?>
                                <th class="text-center"><?php echo $materia['abreviatura']; ?></th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td class="hidden-xs" data-order="<?php echo $alumno['claveal']; ?>">
                                    <div class="pull-left" style="margin-right: 10px;">
                                        <?php if ($foto = obtener_foto_alumno($alumno['claveal'])): ?>
                                        <img class="img-thumbnail" src="../../../xml/fotos/<?php echo $foto; ?>" style="width: 52px !important;" alt="">
                                        <?php else: ?>
                                        <span class="img-thumbnail far fa-user fa-fw fa-4x" style="width: 52px !important;"></span>
                                        <?php endif; ?>
                                    </div>

                                    <p style="margin-bottom: 2px;"><strong><?php echo $alumno['alumno']; ?></strong></p>
                                    <p class="text-muted" style="margin-bottom: 2px;">
                                        <strong>Unidad:</strong> <?php echo $alumno['unidad']; ?><br>
                                        <strong>NIE:</strong> <?php echo $alumno['claveal']; ?> &middot; <strong>DNI:</strong> <?php echo (! empty($alumno['dni'])) ? $alumno['dni'] : 'Sin registrar'; ?>
                                    </p>
                                </td>
                                <?php foreach ($materias as $materia): ?>
                                <td class="text-center">
                                    <?php $estados = array('B' => 'Buen estado', 'R' => 'Regular / suficiente', 'M' => 'Malo', 'N' => 'No entregado / extraviado', 'S' => 'Prestado para septiembre'); ?>
                                    <?php if (strpos($alumno['combasi'], $materia['codigo']) !== false): ?>
                                    <div class="btn-group" data-toggle="buttons">
                                        <?php
                                        foreach ($estados as $idestado => $nomestado) {
                                            if (isset($estado)) unset($estado);

                                            foreach ($estado_libros as $estado_libro) {
                                                if (($estado_libro['claveal'] == $alumno['claveal']) && ($estado_libro['materia'] == $materia['codigo'])) {
                                                    $estado = $estado_libro['estado'];
                                                }
                                            }
                                        }
                                        switch ($estado) {
                                            case 'B' : $btn_estilo = 'btn-success'; break;
                                            case 'R' : $btn_estilo = 'btn-warning'; break;
                                            case 'M' : $btn_estilo = 'btn-danger'; break;
                                            case 'S' : $btn_estilo = 'btn-info'; break;
                                            default  : $btn_estilo = 'btn-default'; break;
                                        }
                                        ?>
                                        <button type="button" class="btn <?php echo $btn_estilo; ?> btn-sm dropdown-toggle" id="<?php echo $alumno['claveal'].'_'.$materia['codigo']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo (isset($estado)) ? $estado : ' - '; ?> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-form">
                                            <?php $i = 0; ?>
                                            <?php foreach ($estados as $idestado => $nomestado): ?>
                                            <?php foreach ($estado_libros as $estado_libro): ?>
                                            <?php if (($estado_libro['claveal'] == $alumno['claveal']) && ($estado_libro['materia'] == $materia['codigo'])): ?>
                                            <?php $estado = $estado_libro['estado']; ?>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                            <li>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?php echo $alumno['claveal'].'_'.$materia['codigo']; ?>" value="<?php echo $idestado; ?>" autocomplete="off" <?php echo ((isset($estado) && $estado == $idestado)) ? 'checked' : ''; ?>> <?php echo $nomestado; ?>
                                                    </label>
                                                </div>
                                            </li>
                                            <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                                <?php if (acl_permiso($_SESSION['cargo'], array(1))): ?>
                                <td class="text-center hidden-xs">
                                    <?php $librosReposicion = 0; ?>
                                    <?php $numlibros = 0; ?>
                                    <?php foreach ($estado_libros as $estado_libro): ?>
                                    <?php if (($estado_libro['claveal'] == $alumno['claveal'])): ?>
                                    <?php if (! $estado_libro['devuelto']) $librosReposicion++; ?>
                                    <?php $numlibros++; ?>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if ($librosReposicion): ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-print fa-lg"></span> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="certificado.php?claveal=<?php echo $alumno['claveal']; ?>&reposicion=1" target="_blank"><strong class="text-warning">Imprimir certificado de entrega<br>(Libros repuestos por el alumno)</strong></a></li>
                                            <li><a href="certificado.php?claveal=<?php echo $alumno['claveal']; ?>" target="_blank">Imprimir certificado de reposición</a></li>
                                        </ul>
                                    </div>
                                    <?php else: ?>
                                    <a href="certificado.php?claveal=<?php echo $alumno['claveal']; ?>" class="btn btn-primary btn-sm" target="_blank" data-bs="tooltip" title="Imprimir certificado de reposición / entrega"><span class="fas fa-print fa-lg fa-fw"></span></a>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <tr class="visible-xs">
                                <td colspan="<?php echo count($materias); ?>" style="background-color: #333; padding: 1px;"></td>
                            </tr>
                            <tr class="visible-xs">
                                <td colspan="<?php echo count($materias); ?>" style="background-color: #666; padding: 1px;"></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>

                <?php else: ?>
                <br><br><br><br><br>
                <p class="lead text-center text-muted">No hay alumnos en el nivel y unidad seleccionados<p>
                <br><br><br><br><br><br>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php include('../../../pie.php'); ?>

    <script>
    $(document).ready(function() {
        $('.dropdown-menu').on('click', function(e){
            if($(this).hasClass('dropdown-menu-form')){
                e.stopPropagation();
            }
        });

        $('input:radio').on('click', function(){
            var btnname = $(this).attr('name');
            var btnvalue = $(this).val();

            var expbtnname = btnname.split('_');
            var claveal = expbtnname[0];
            var idmateria = expbtnname[1];

            switch (btnvalue) {
                case 'B': var btnstyle = 'btn btn-success btn-sm dropdown-toggle'; break;
                case 'R': var btnstyle = 'btn btn-warning btn-sm dropdown-toggle'; break;
                case 'M': var btnstyle = 'btn btn-danger btn-sm dropdown-toggle'; break;
                case 'S': var btnstyle = 'btn btn-info btn-sm dropdown-toggle'; break;
                default:  var btnstyle = 'btn btn-default btn-sm dropdown-toggle'; break;
            }

            // Guardamos el estado del libro en la base de datos
            $.post( "post_guardarEstado.php", { "nie" : claveal, "materia" : idmateria, "estado" : btnvalue }, null, "json" )
			.done(function( data, textStatus, jqXHR ) {
				if ( data.status ) {
					$('#'+btnname).html(btnvalue+' <span class="caret"></span>');
                    $('#'+btnname).removeClass();
                    $('#'+btnname).addClass(btnstyle);
				}
			});

        });
    });
    </script>

</body>
</html>
