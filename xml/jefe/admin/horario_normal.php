<?

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
	else {
		echo "Tarari";
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
	 echo $sql."<br>";
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


// Horw para Faltas
mysqli_query($db_con, "trunca table horw_faltas");
mysqli_query($db_con, "insert into horw_faltas select * from horw");
mysqli_query($db_con, "delete from horw_faltas where a_grupo = ''");

// Cambiamos los numeros de Horw para dejarlos en orden alfabético.
$hor = mysqli_query($db_con, "select distinct prof from horw order by prof");
while($hor_profe = mysqli_fetch_array($hor)){
	$np+=1;
	$sql = "update horw set no_prof='$np' where prof = '$hor_profe[0]'";
	$sql0 = "update horw_faltas set no_prof='$np' where prof = '$hor_profe[0]'";
	//echo "$sql<br>";
	$sql1 = mysqli_query($db_con, $sql);
	$sql2 = mysqli_query($db_con, $sq0);
}

	
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

	?>