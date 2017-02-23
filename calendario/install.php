<?php
require('../bootstrap.php');

$GLOBALS['db_con'] = $db_con;


echo "Creando base de datos <strong>calendario_categorias</strong><br>";
mysqli_query($db_con, "DROP TABLE `calendario_categorias`");
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `calendario_categorias` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(30) collate latin1_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `profesor` varchar(80) collate latin1_spanish_ci NOT NULL,
  `color` char(7) collate latin1_spanish_ci NOT NULL,
  `espublico` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;") or die(mysqli_error($db_con));


echo "Creando base de datos <strong>calendario</strong><br>";
mysqli_query($db_con, "DROP TABLE `calendario`");
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `calendario` (
  `id` int(11) NOT NULL auto_increment,
  `categoria` int(11) NOT NULL,
  `nombre` varchar(120) collate latin1_spanish_ci NOT NULL,
  `descripcion` longtext collate latin1_spanish_ci NOT NULL,
  `fechaini` date default NULL,
  `horaini` time default NULL,
  `fechafin` date default NULL,
  `horafin` time default NULL,
  `lugar` varchar(180) collate latin1_spanish_ci NOT NULL,
  `departamento` text collate latin1_spanish_ci default NULL,
  `profesores` text collate latin1_spanish_ci default NULL,
  `unidades` text collate latin1_spanish_ci default NULL,
  `asignaturas` text collate latin1_spanish_ci default NULL,
  `fechareg` datetime NOT NULL,
  `profesorreg` varchar(60) collate latin1_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;") or die(mysqli_error($db_con));


echo "Creando calendarios públicos<br>";
mysqli_query($db_con, "INSERT INTO `calendario_categorias` (`id`, `nombre`, `fecha`, `profesor`, `color`, `espublico`) VALUES
(1, 'Calendario del centro', '".date('Y-m-d')."', 'admin', '#f29b12', 1),
(2, 'Actividades extraescolares', '".date('Y-m-d')."', 'admin', '#18bc9c', 1);") or die(mysqli_error($db_con));


echo "Creando calendarios personales<br>";
$result = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos");
while ($row = mysqli_fetch_assoc($result)) {
	$exp_nombre = explode(',', $row['nombre']);
	$nombre = trim($exp_nombre[1]);
	if ($nombre == '') {
		$exp_nombre = explode(' ', $row['nombre']);
		$nombre = trim($exp_nombre[0]);
	}
	$idea = $row['idea'];
	
	$query = "INSERT INTO `calendario_categorias` (`nombre`, `fecha`, `profesor`, `color`, `espublico`) VALUES ('$nombre', '".date('Y-m-d')."', '$idea', '#3498db', 0)";
	mysqli_query($db_con, $query) or die(mysqli_error($db_con));
}
mysqli_free_result($result);



echo "Migrando datos del calendario existente<br>";
$result = mysqli_query($db_con, "SELECT eventdate, title, event FROM cal WHERE idact IS NULL ORDER BY eventdate ASC");
while ($row = mysqli_fetch_assoc($result)) {
	$fechaini = $row['eventdate'];
	$nombre =  mysqli_real_escape_string($db_con, $row['title']);
	$descripcion = mysqli_real_escape_string($db_con, $row['event']);
	
	$query = "INSERT INTO `calendario` (`categoria`, `nombre`, `descripcion`, `fechaini`, `horaini`, `fechafin`, `horafin`, `departamento`, `profesores`, `unidades`, `fechareg`, `profesorreg`) VALUES (1, '$nombre', '$descripcion', '".$fechaini."', '00:00', '".$fechaini."', '00:00', NULL, NULL, NULL, '".$fechaini."', 'admin')";
	mysqli_query($db_con, $query) or die(mysqli_error($db_con));
}
mysqli_free_result($result);



echo "Migrando datos de Actividades extraescolares<br>";
$result = mysqli_query($db_con, "SELECT actividad, CONCAT(descripcion,' ',justificacion) AS descripcion, departamento, profesor, grupos, fecha, hoy FROM actividades WHERE confirmado=1 ORDER BY hoy ASC");
while ($row = mysqli_fetch_assoc($result)) {
	$fechaini = $row['fecha'];
	$nombre = mysqli_real_escape_string($db_con, $row['actividad']);
	$descripcion = mysqli_real_escape_string($db_con, $row['descripcion']);
	$departamento = mysqli_real_escape_string($db_con, $row['departamento']);
	$profesores = mysqli_real_escape_string($db_con, $row['profesor']);
	$unidades = mysqli_real_escape_string($db_con, $row['grupos']);
	$fechareg = mysqli_real_escape_string($db_con, $row['hoy']);
	
	$query = "INSERT INTO `calendario` (`categoria`, `nombre`, `descripcion`, `fechaini`, `horaini`, `fechafin`, `horafin`, `departamento`, `profesores`, `unidades`, `fechareg`, `profesorreg`) VALUES (2, '$nombre', '$descripcion', '".$fechaini."', '08:15', '".$fechaini."', '09:15', '$departamento', '$profesores', '$unidades', '".$fechareg."', 'admin')";
	mysqli_query($db_con, $query) or die(mysqli_error($db_con));
}
mysqli_free_result($result);



echo "Migrando datos de Diario<br>";

$result_profesor = mysqli_query($db_con, "SELECT profesor, idea FROM c_profes ORDER BY profesor ASC");
while ($row_profesor = mysqli_fetch_assoc($result_profesor)) {
	$result = mysqli_query($db_con, "SELECT fecha, grupo, materia, titulo, observaciones FROM diario WHERE profesor='".$row_profesor['profesor']."'");
	while ($row = mysqli_fetch_assoc($result)) {
		$fechaini = $row['fecha'];
		$nombre = mysqli_real_escape_string($db_con, $row['titulo']);
		$descripcion = mysqli_real_escape_string($db_con, $row['observaciones']);
		$unidades = mysqli_real_escape_string($db_con, $row['grupo']);
		$asignaturas = mysqli_real_escape_string($db_con, $row['materia']);
		$profesorreg = mysqli_real_escape_string($db_con, $row_profesor['idea']);
		
		$result_calendario = mysqli_query($db_con, "SELECT id FROM calendario_categorias WHERE profesor='$profesorreg' AND id NOT LIKE '1' AND id NOT LIKE '2'");
		$row_calendario = mysqli_fetch_assoc($result_calendario);
		$idcalendario = $row_calendario['id'];
		
		$query = "INSERT INTO `calendario` (`categoria`, `nombre`, `descripcion`, `fechaini`, `horaini`, `fechafin`, `horafin`, `departamento`, `profesores`, `unidades`, `asignaturas`, `fechareg`, `profesorreg`) VALUES ($idcalendario, '$nombre', '$descripcion', '".$fechaini."', '08:15', '".$fechaini."', '09:15', NULL, NULL, '$unidades', '$asignaturas', '".$fechaini."', '$profesorreg')";
		if ($idcalendario) {
			mysqli_query($db_con, $query) or die(mysqli_error($db_con));
		}
	}
	mysqli_free_result($result);
}
mysqli_free_result($result_profesor);

echo '<meta http-equiv="Refresh" content="0;url=../index.php">';
?>