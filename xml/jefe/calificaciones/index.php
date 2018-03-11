<?php
include("../../../bootstrap.php");

acl_acceso($_SESSION['cargo'], array(1));

// OBTENEMOS TODOS LOS NIVELES DEL CENTRO EDUCATIVO
$niveles = array();
if (acl_permiso($_SESSION['cargo'], array(1))) {
    $result = mysqli_query($db_con, "SELECT `idcurso`, `nomcurso` FROM `cursos` WHERE `nomcurso` ORDER BY `idcurso` ASC");
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

if (isset($_GET['curso']) && ! empty($_GET['curso'])) {
    $curso = urldecode($_GET['curso']);

    if (! in_array($curso, array_column($niveles, 'nombre'))) {
        $curso = "Todos los cursos";
    }
} 
else {
    $curso = "Todos los cursos";
}

// OBTENEMOS TODAS LAS UNIDADES DEL CENTRO EDUCATIVO
$unidades = array();
if (acl_permiso($_SESSION['cargo'], array(1)) && $curso != "Todos los cursos") {
    $result = mysqli_query($db_con, "SELECT `unidades`.`idunidad`, `unidades`.`nomunidad` FROM `unidades` JOIN `cursos` ON `unidades`.`idcurso` = `cursos`.`idcurso` WHERE `cursos`.`nomcurso` = '$curso' ORDER BY `unidades`.`nomunidad` ASC");
}
elseif (acl_permiso($_SESSION['cargo'], array(1)) && $curso == "Todos los cursos") {
    $result = mysqli_query($db_con, "SELECT `unidades`.`idunidad`, `unidades`.`nomunidad` FROM `unidades` JOIN `cursos` ON `unidades`.`idcurso` = `cursos`.`idcurso` ORDER BY `cursos`.`idcurso` ASC, `unidades`.`nomunidad` ASC");
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

// OBTENEMOS TODAS LAS EVALUACIONES IMPORTADAS
$array_convocatorias = array('EVI' => 'Evaluación Inicial', '1EV' => '1ª Evaluación', '2EV' => '2ª Evaluación', 'ORD' => 'Evaluación Ordinaria', 'EXT' => 'Evaluación Extraordinaria');
$convocatorias = array();
$result = mysqli_query($db_con, "SELECT COUNT(`notas0`) AS notas0, COUNT(`notas1`) AS notas1, COUNT(`notas2`) AS notas2, COUNT(`notas3`) AS notas3, COUNT(`notas4`) AS notas4 FROM notas");
$row = mysqli_fetch_array($result);
for ($i = 0; $i < 5; $i++) {
    
    if ($row['notas'.$i] > 0) {
        $convocatoria = array(
            'abrev'       => key($array_convocatorias),
            'nombre'      => current($array_convocatorias)
        );

        array_push($convocatorias, $convocatoria);
    }
    next($array_convocatorias);
    
}
mysqli_free_result($result);
unset($convocatoria);

// Procesamos la variable de selección de evaluación
if (isset($_GET['evaluacion']) && ! empty($_GET['evaluacion'])) {
    $evaluacion = urldecode($_GET['evaluacion']);

    if (! in_array($evaluacion, array_column($convocatorias, 'nombre'))) {
        unset($evaluacion);
    }
}

include("../../../menu.php");
?>

    <div class="container">

        <div class="page-header">
            <h2>Impresión de boletines</h2>
        </div>

        <div class="row">
        
            <div class="col-sm-12">
            
                <form action="" method="GET">
                    <div class="well">

                        <div class="row">

                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label for="curso">Curso:</label>
                                    <select class="form-control" name="curso" id="curso" onchange="submit()">
                                        <option value="">Todas los cursos</option>
                                        <?php foreach ($niveles as $nivel): ?>
                                        <option value="<?php echo $nivel['nombre']; ?>"<?php echo (isset($curso) && $curso == $nivel['nombre']) ? ' selected' : ''; ?>><?php echo $nivel['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                            <div class="col-sm-4">

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

                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label for="curso">Convocatoria:</label>
                                    <select class="form-control" name="evaluacion" id="evaluacion" onchange="submit()">
                                        <?php foreach ($convocatorias as $convocatoria): ?>
                                        <option value="<?php echo $convocatoria['nombre']; ?>"<?php echo (isset($evaluacion) && $evaluacion == $convocatoria['nombre']) ? ' selected' : ''; ?>><?php echo $convocatoria['nombre']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                        </div>
                        
                    </div>
                </form>

                <form action="imprimir.php" method="POST">
                    <input type="hidden" name="curso" value="<?php echo $curso; ?>">
                    <input type="hidden" name="grupo" value="<?php echo $grupo; ?>">
                    <input type="hidden" name="evaluacion" value="<?php echo $evaluacion; ?>">
                    <button type="submit" name="generarBoletines" class="btn btn-primary" formtarget="_blank">Generar boletines</button>
                </form>

            </div>

        </div>
        
    </div>

    <?php include("../../../pie.php"); ?>

</body>
</html>
