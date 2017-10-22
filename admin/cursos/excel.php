<?php 
require('../../bootstrap.php');

require_once("../../includes/php-excel/excel.php"); 
require_once("../../includes/php-excel/excel-ext.php");

$grupo=$_POST['select'];
//echo $tipo." ".$grupo;
$uni = substr($grupo,0,1);
	
$sql="SELECT CONCAT(alma.apellidos,', ',alma.nombre) AS alumno, combasi, domicilio, telefono, fecha, edad, padre, dni FROM alma WHERE unidad = '".$grupo."' ORDER BY apellidos ASC, nombre ASC";

$resEmp = mysqli_query($db_con, $sql) or die(mysqli_error($db_con));

$nc = 0;

if ($_POST['asignaturas']==1){
	
	while($datatmp = mysqli_fetch_array($resEmp)) { 
		$nc++;

		$mat="";

		$alumn = utf8_decode($datatmp['alumno']);
		$domicilio = utf8_decode($datatmp['domicilio']);
		$padre = utf8_decode($datatmp['padre']);
		
		$asig0 = explode(":",$datatmp['combasi']);
		foreach($asig0 as $asignatura){		
		$unidadn = substr($grupo,0,1);			
		$consulta = "select distinct abrev, curso from asignaturas where codigo = '$asignatura' and curso like '%$unidadn%' limit 1";
		$abrev = mysqli_query($db_con, $consulta);		
		$abrev0 = mysqli_fetch_array($abrev);
		$curs=substr($abrev0[1],0,2);
		$mat.=$abrev0[0]."; ";
		}
		
		if ($_POST['datos']=="1") {
			$data[] = array($nc,$alumn,$domicilio,$datatmp['telefono'],$datatmp['fecha'],$datatmp['edad'],$padre,$datatmp['dni']);
		}
		else{
			$data[] = array($nc,$alumn,$mat);
		}
		
}
} 
else{
	while($datatmp = mysqli_fetch_assoc($resEmp)) { 
		$nc++;
		
		if ($_POST['datos']=="1") {
			$alumn = utf8_decode($datatmp['alumno']);
			$domicilio = utf8_decode($datatmp['domicilio']);
			$padre = utf8_decode($datatmp['padre']);
			
			$data[] = array($nc,$dalumn,$domicilio,$datatmp['telefono'],$datatmp['fecha'],$datatmp['edad'],$padre,$datatmp['dni']);
		}
		else{
			$alumn = utf8_decode($datatmp['alumno']);
			$data[] = array($nc,$alumn);

		}
} 
}
createExcel("listado_$grupo.xls", $data);
exit;
?>
