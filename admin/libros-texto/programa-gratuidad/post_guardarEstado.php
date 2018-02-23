<?php
require('../../../bootstrap.php');

$jsondata = array();

if (isset($_POST['nie']) && isset($_POST['materia']) && isset($_POST['estado'])) {
    $estados = array('N','B','R','M','S');
    
    $claveal = $_POST['nie'];
    $idmateria = $_POST['materia'];
    $estado = $_POST['estado'];

    // Comprobamos el NIE del alumno
    $result_nie = mysqli_query($db_con, "SELECT `claveal` FROM `alma` WHERE `claveal` = $claveal LIMIT 1");
    $esNIEAlumno = (mysqli_num_rows($result_nie) > 0) ? 1 : 0;

    // Comprobamos el ID de materia
    $result_materia = mysqli_query($db_con, "SELECT `codigo` FROM `asignaturas` WHERE `codigo` = $idmateria LIMIT 1");
    $esIdMateria = (mysqli_num_rows($result_materia) > 0) ? 1 : 0;

    // Comprobamos el estado
    $esEstado = (in_array($estado, $estados)) ? 1 : 0;
    
    if ($esNIEAlumno && $esIdMateria && $esEstado) {
        $fecha_hora = date('Y-m-d H:i:s');
        $curso_actual = $config['curso_actual'];

        // Comprobamos si se trata de una actualización o registro de datos
        $result_registro = mysqli_query($db_con, "SELECT `claveal`, `materia` FROM `libros_texto_alumnos` WHERE `claveal` = $claveal AND `materia` = $idmateria LIMIT 1");
        if (mysqli_num_rows($result_registro)) {
            $result = mysqli_query($db_con, "UPDATE `libros_texto_alumnos` SET `estado` = '$estado', fecha = '$fecha_hora' WHERE `claveal` = $claveal AND `materia` = $idmateria LIMIT 1");
        }
        else {
            $result = mysqli_query($db_con, "INSERT `libros_texto_alumnos` (`claveal`, `materia`, `estado`, `devuelto`, `fecha`, `curso`) VALUES ($claveal, $idmateria, '$estado', 0, '$fecha_hora', '$curso_actual')");
        }

        if ($result) {
            $jsondata['status'] = true;
        } else {
            $jsondata['status'] = false;
        }
    }
    else {
        $jsondata['status'] = false;
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();

}