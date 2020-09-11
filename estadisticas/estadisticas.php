<?php 
require('../bootstrap.php'); 

if (! isset($_POST['id'])) exit('No direct script access allowed');

if (acl_permiso($carg, array('1'))) {

	if ($_POST['id'] == 'accesos') { 
		mysqli_query($db_con, "CREATE TEMPORARY TABLE tmp_accesos SELECT DISTINCT profesor FROM reg_intranet WHERE fecha LIKE '".date('Y-m-d')."%' AND profesor IN (SELECT idea FROM departamentos WHERE departamento NOT LIKE 'Administracion' AND departamento NOT LIKE 'Admin' AND departamento NOT LIKE 'Conserjeria') ORDER BY profesor ASC");
								
		$result = mysqli_query($db_con, "SELECT nombre, departamento FROM departamentos WHERE departamento NOT LIKE 'Administracion' AND departamento NOT LIKE 'Admin' AND departamento NOT LIKE 'Conserjeria' AND idea NOT IN (SELECT profesor FROM tmp_accesos) ORDER BY nombre ASC");
								
		$result1 = mysqli_query($db_con, "SELECT * FROM departamentos WHERE departamento NOT LIKE 'Administracion' AND departamento NOT LIKE 'Admin' AND departamento NOT LIKE 'Conserjeria'");
		
		$row = mysqli_num_rows($result);
		$row1 = mysqli_num_rows($result1);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'profesor' => $table_row['nombre'],
				'departamento' => $table_row['departamento']
			);
			
			array_push($table, $table2);
		}
		
		mysqli_query($db_con,"drop table tmp_accesos");
		
		$output = array('num_profesores' => $row, 'total_profesores' => $row1, 'accesos_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'convivencia') { 
		$result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, Fechoria.id, Fechoria.asunto, Fechoria.informa FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE Fechoria.fecha = '".date('Y-m-d')."' ORDER BY Fechoria.fecha DESC");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'profesor' => $table_row['informa']
			);
			
			array_push($table, $table2);
		}
		
		$output = array('total' => $row, 'convivencia_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'expulsiones') { 
		$result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, alma.unidad, Fechoria.id, Fechoria.asunto, Fechoria.informa, Fechoria.inicio, Fechoria.fin FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE expulsion > 0 AND inicio <= '".date('Y-m-d')."' AND fin >= '".date('Y-m-d')."'");
		
		$ayer = date('Y') . "-" . date('m') . "-" . (date('d') - 1);
		$result1 = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, alma.unidad, Fechoria.id, Fechoria.asunto, Fechoria.informa, Fechoria.inicio, Fechoria.fin FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE expulsion > 0 AND fin = '$ayer'");
		
		$row = mysqli_num_rows($result);
		$row1 = mysqli_num_rows($result1);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'inicio' => $table_row['inicio'],
				'fin' => $table_row['fin']
			);
			
			array_push($table, $table2);
		}
		
		unset($table_row);
		unset($table2);
		
		
		$table1 = array();
		while ($table_row = mysqli_fetch_array($result1)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'inicio' => $table_row['inicio'],
				'fin' => $table_row['fin']
			);
			
			array_push($table1, $table2);
		}
		
		unset($table_row);
		unset($table2);
		
		
		$output = array('total_expulsados' => $row, 'total_reingresan' => $row1, 'expulsados_tabla' => $table, 'reincorporaciones_tabla' => $table1);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'visitas') { 
		$result = mysqli_query($db_con, "SELECT id, apellidos, nombre, unidad, tutor FROM infotut_alumno WHERE F_ENTREV = '".date('Y-m-d')."'");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idvisita' => $table_row['id'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'tutor' => $table_row['tutor']
			);
			
			array_push($table, $table2);
		}
		
		$output = array('total' => $row, 'visitas_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'mensajes') { 		
		mysqli_query($db_con, "CREATE TEMPORARY TABLE mens_tmp SELECT * FROM mens_profes WHERE recibidoprofe='0' ORDER BY id_texto DESC LIMIT 5000");
		mysqli_query($db_con, "DELETE FROM mens_tmp WHERE profesor NOT IN (SELECT idea FROM departamentos)");
		mysqli_query($db_con, "CREATE TEMPORARY TABLE mens_tmp2 SELECT profesor, COUNT(*) AS num FROM mens_tmp GROUP BY profesor");
		$result = mysqli_query($db_con, "SELECT profesor, nombre, num FROM mens_tmp2, departamentos WHERE departamentos.idea = mens_tmp2.profesor AND num > '25' ORDER BY nombre ASC");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idea' => $table_row['profesor'],
				'profesor' => $table_row['nombre'],
				'numero' => $table_row['num']
			);
			
			array_push($table, $table2);
		}
		
			
		mysqli_free_result($result);
		mysqli_query($db_con,"drop table tmp_accesos");
		mysqli_query($db_con,"drop table mens_tmp");
		mysqli_query($db_con,"drop table mens_tmp2");
		
		$output = array('total' => $row, 'mensajes_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
}

if (acl_permiso($carg, array('2'))) {
	
	if ($_POST['id'] == 'asistencia') { 		
		$result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, FALTAS.claveal, FALTAS.falta, FALTAS.hora FROM FALTAS JOIN alma ON FALTAS.claveal = alma.claveal WHERE FALTAS.unidad = '".$_SESSION['mod_tutoria']['unidad']."' AND FALTAS.fecha = '".date('Y-m-d')."'");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'claveal' => $table_row['claveal'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'hora' => $table_row['hora'],
				'falta' => $table_row['falta']
			);
			
			array_push($table, $table2);
		}
		
			
		mysqli_free_result($result);
		
		$output = array('num_faltas' => $row, 'asistencia_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'convivencia') { 
		$result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, Fechoria.id, Fechoria.asunto, Fechoria.informa FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE alma.unidad = '".$_SESSION['mod_tutoria']['unidad']."' AND Fechoria.fecha = '".date('Y-m-d')."' ORDER BY Fechoria.fecha DESC");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'profesor' => $table_row['informa']
			);
			
			array_push($table, $table2);
		}
		
		$output = array('total' => $row, 'convivencia_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'expulsiones') { 
		$result = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, alma.unidad, Fechoria.id, Fechoria.asunto, Fechoria.informa, Fechoria.inicio, Fechoria.fin FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE alma.unidad = '".$_SESSION['mod_tutoria']['unidad']."' AND expulsion > 0 AND inicio <= '".date('Y-m-d')."' AND fin >= '".date('Y-m-d')."'");
		
		$ayer = date('Y') . "-" . date('m') . "-" . (date('d') - 1);
		$result1 = mysqli_query($db_con, "SELECT alma.apellidos, alma.nombre, alma.claveal, alma.unidad, Fechoria.id, Fechoria.asunto, Fechoria.informa, Fechoria.inicio, Fechoria.fin FROM Fechoria JOIN alma ON Fechoria.claveal = alma.claveal WHERE alma.unidad = '".$_SESSION['mod_tutoria']['unidad']."' AND expulsion > 0 AND fin = '$ayer'");
		
		$row = mysqli_num_rows($result);
		$row1 = mysqli_num_rows($result1);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'inicio' => $table_row['inicio'],
				'fin' => $table_row['fin']
			);
			
			array_push($table, $table2);
		}
		
		unset($table_row);
		unset($table2);
		
		
		$table1 = array();
		while ($table_row = mysqli_fetch_array($result1)) {
			$table2 = array(
				'idfechoria' => $table_row['id'],
				'claveal' => $table_row['claveal'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'problema' => $table_row['asunto'],
				'inicio' => $table_row['inicio'],
				'fin' => $table_row['fin']
			);
			
			array_push($table1, $table2);
		}
		
		unset($table_row);
		unset($table2);
		
		
		$output = array('total_expulsados' => $row, 'total_reingresan' => $row1, 'expulsados_tabla' => $table, 'reincorporaciones_tabla' => $table1);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}
	
	if ($_POST['id'] == 'visitas') { 
		$result = mysqli_query($db_con, "SELECT id, apellidos, nombre, unidad, tutor FROM infotut_alumno WHERE F_ENTREV = '".date('Y-m-d')."' AND unidad = '".$_SESSION['mod_tutoria']['unidad']."'");
		
		$row = mysqli_num_rows($result);
		
		$table = array();
		while ($table_row = mysqli_fetch_array($result)) {
			$table2 = array(
				'idvisita' => $table_row['id'],
				'unidad' => $table_row['unidad'],
				'alumno' => $table_row['nombre'].' '.$table_row['apellidos'],
				'tutor' => $table_row['tutor']
			);
			
			array_push($table, $table2);
		}
		
		$output = array('total' => $row, 'visitas_tabla' => $table);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($output);
		exit();
	}


}

?>