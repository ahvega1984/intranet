<?php 
require('../../bootstrap.php');

require_once("../../includes/php-excel/excel.php"); 
require_once("../../includes/php-excel/excel-ext.php");

$grupo=$_POST['select'];
//echo $tipo." ".$grupo;
$uni = substr($grupo,0,1);
	
$sql="SELECT concat(alma.apellidos,', ',alma.nombre) as alumno, nc as num, combasi, domicilio, telefono, fecha, edad, padre, dni FROM alma, FALUMNOS WHERE alma.claveal=FALUMNOS.claveal and alma.Unidad='".$grupo."' ORDER BY nc";

$resEmp = mysqli_query($db_con, $sql) or die(mysqli_error($db_con));
 
if ($_POST['asignaturas']==1){
	while($datatmp = mysqli_fetch_array($resEmp)) { 
		$mat="";
		$asig0 = explode(":",$datatmp['combasi']);
		foreach($asig0 as $asignatura){		
		$unidadn = substr($grupo,0,1);			
		$consulta = "select distinct abrev, curso from asignaturas where codigo = '$asignatura' and curso like '%$unidadn%' limit 1";
		$abrev = mysqli_query($db_con, $consulta);		
		$abrev0 = mysqli_fetch_array($abrev);
		$curs=substr($abrev0[1],0,2);
		$mat.=$abrev0[0]."; ";
		}
		is_numeric($datatmp['num']);
		if ($_POST['datos']=="1") {
			$data[] = array($datatmp['num'],$datatmp['alumno'],$mat,$datatmp['domicilio'],$datatmp['telefono'],$datatmp['fecha'],$datatmp['edad'],$datatmp['padre'],$datatmp['dni']);
		}
		else{
			$data[] = array($datatmp['num'],$datatmp['alumno'],$mat);
		}
		
}
} 
else{
	while($datatmp = mysqli_fetch_assoc($resEmp)) { 
		if ($_POST['datos']=="1") {
			$data[] = array($datatmp['num'],$datatmp['alumno'],$datatmp['domicilio'],$datatmp['telefono'],$datatmp['fecha'],$datatmp['edad'],$datatmp['padre'],$datatmp['dni']);
		}
		else{
			$data[] = array($datatmp['num'],$datatmp['alumno']);

		}
} 
}
createExcel("listado_$grupo.xls", $data);
exit;
?>