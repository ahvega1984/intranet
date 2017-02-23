<?php
session_start();
include("../../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');
	exit();
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/salir.php");
	exit;
}
?>
<?php
include("../../../menu.php");
?>
<div class="page-header" align="center">
<h2>Administración <small> Creación de Horarios y Profesores</small></h2>
</div>
<br />
<div align="center">
<?php

$fp = fopen ( $_FILES['archivo']['tmp_name'] , "r" );
if (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
	$num_col=count($data);
	if ($num_col<>13) {
		echo '<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>Atención:</legend>
El archivo de Horwin que estás intentando exportar contiene <strong>'.$num_col.' columnas</strong> de datos y debe contener <strong>13 columnas</strong>. Asegúrate de que el archivo de Horwin sigue las instrucciones de la imagen, y vuelve a intentarlo.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atrás" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div><br />';
		exit();
	}
}

// Backup
mysqli_query($db_con,"truncate table horw_seg");
mysqli_query($db_con,"insert into horw_seg select * from horw");
mysqli_query($db_con,"truncate table horw_seg_faltas");
mysqli_query($db_con,"insert into horw_seg_faltas select * from horw_faltas");

mysqli_query($db_con,"truncate table horw");

// Claveal primaria e índice

while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
	// Mientras hay líneas que leer... si necesitamos añdir sólo las clases hay que hacer aquí un if ($data[9]!='')
	$sql="INSERT INTO horw (dia,hora,a_asig,asig,c_asig,prof,no_prof,c_prof,a_aula,n_aula,a_grupo,nivel,n_grupo) ";
	$sql.=" VALUES ( ";
	foreach ($data as $indice=>$clave){
		$sql.="'".trim($clave)."', ";
	}
	$sql=substr($sql,0,strlen($sql)-2);
	$sql.=" )";
	//echo $sql."<br>";
	mysqli_query($db_con,$sql) or die ('<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIÓN:</h5>
No se han podido insertar los datos en la tabla <strong>Horw</strong>. Ponte en contacto con quien pueda resolver el problema.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atrás" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div>');	
}
fclose ( $fp );

// Eliminamos el Recreo como 4ª Hora.
$recreo = "DELETE FROM horw WHERE hora ='4'";
mysqli_query($db_con,$recreo);
$hora4 = "UPDATE  horw SET  hora =  '4' WHERE  hora = '5'";
mysqli_query($db_con,$hora4);
$hora5 = "UPDATE  horw SET  hora =  '5' WHERE  hora = '6'";
mysqli_query($db_con,$hora5);
$hora6 = "UPDATE  horw SET  hora =  '6' WHERE  hora = '7'";
mysqli_query($db_con,$hora6);
mysqli_query($db_con,"OPTIMIZE TABLE  `horw`");



// Cambiamos los numeros de Horw para dejarlos en orden alfabético.
$hor = mysqli_query($db_con, "select distinct prof from horw order by prof");
while($hor_profe = mysqli_fetch_array($hor)){
	$np+=1;
	$sql = "update horw set no_prof='$np' where prof = '$hor_profe[0]'";
	//echo "$sql<br>";
	$sql1 = mysqli_query($db_con, $sql);
}

// Limpiez de codigos
$h1 = mysqli_query($db_con, "select id, c_asig, a_grupo, asig, unidades.idcurso, nomcurso from horw, unidades, cursos where a_grupo=nomunidad and unidades.idcurso=cursos.idcurso and a_grupo not like ''");
while ($h2 = mysqli_fetch_array($h1)) {
	$id_horw = $h2[0];
	$curso = $h2[5];
	$cod = $h2[1];
	$nombre_asignatura = $h2[3];
	
	$asig = mysqli_query($db_con, "select codigo, nombre from asignaturas where curso = '$curso' and curso not like '' and nombre = '$nombre_asignatura' and codigo not like '2' and abrev not like '%\_%'");
	//echo "select codigo, nombre from asignaturas where curso = '$curso' and curso not like '' and nombre = '$nombre_asignatura' and codigo not like '2' and abrev not like '%\_%'<br>";
if (mysqli_num_rows($asig)>0) {
	$asignatur = mysqli_fetch_array($asig);
	$asignatura=$asignatur[0];
	if (!($asignatura==$cod)) {
		$codasi = $asignatura;
		mysqli_query($db_con, "update horw set c_asig = '$codasi' where id = '$id_horw'");
		//echo "update horw set c_asig = '$codasi' where id = '$id_horw'<br>";
	}
	else{
		$codasi="";
	}
	
}

}

// Horw para Faltas
mysqli_query($db_con, "truncate table horw_faltas");
mysqli_query($db_con, "insert into horw_faltas select * from horw");
mysqli_query($db_con, "delete from horw_faltas where a_grupo = ''");

	// Metemos a los profes en la tabla profesores hasta que el horario se haya exportado a Séneca y consigamos los datos reales de los mismos
	$tabla_profes =mysqli_query($db_con,"select * from profesores");
	if (mysql_num_rows($tabla_profes) > 0) {}
	else{
		// Recorremos la tabla Profesores bajada de Séneca
		$pro =mysqli_query($db_con,"select distinct asig, a_grupo, prof from horw where a_grupo like '1%' or a_grupo like '2%' or a_grupo like '3%' or a_grupo like '4%' order by prof");
		while ($prf =mysqli_fetch_array($pro)) {
			$materia = $prf[0];
			$grupo = $prf[1];
			$profesor = $prf[2];
			$niv =mysqli_query($db_con,"select distinct curso from alma where unidad = '$grupo'");
			$nive =mysqli_fetch_array($niv);
			$nivel = $nive[0];

			mysql_query("INSERT INTO  profesores (
`nivel` ,
`materia` ,
`grupo` ,
`profesor`
) VALUES ('$nivel', '$materia', '$grupo', '$profesor')");
		}
	}

	// Tutores
	$tabla_tut =mysqli_query($db_con,"select * from FTUTORES");
	if(mysql_num_rows($tabla_tut) > 0){}
	else{
		mysql_query("insert into FTUTORES (nivel, grupo, tutor) select distinct nivel, n_grupo, prof from horw where a_asig like '%TUT%'");
	}
	?>
	<div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Horario ha sido importado correctamente.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atrás" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div><br />
	</div>
	</div>
	<? include("../../../pie.php");?>