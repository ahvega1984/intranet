<? 
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}

// Conexión 
$fp = fopen ( $_FILES['archivo']['tmp_name'] , "r" );
// Backup
mysqli_query($db_con,"truncate table horw_seg"); 
mysqli_query($db_con,"insert into horw_seg select * from horw"); 

mysqli_query($db_con,"drop table horw_faltas");  

// Claveal primaria e índice
  $SQL6 = "ALTER TABLE  `horw_faltas` ADD INDEX (  `prof` )";
  $result6 =mysqli_query($db_con,$SQL6);
    $SQL7 = "ALTER TABLE  `horw_faltas` ADD INDEX (  `c_asig` )";
  $result7 =mysqli_query($db_con,$SQL7);
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { 
// Mientras hay líneas que leer... si necesitamos añdir sólo las clases hay que hacer aquí un if ($data[9]!='')
$sql="INSERT INTO horw_faltas (dia,hora,a_asig,asig,c_asig,prof,no_prof,c_prof,a_aula,n_aula,a_grupo,nivel,n_grupo) ";
$sql.=" VALUES ( ";
foreach ($data as $indice=>$clave){
if($indice=="5"){
	$clave=mb_strtoupper($clave,'iso-8859-1');
	$clave=mb_strtolower($clave,'iso-8859-1');
	$clave=ucwords($clave);
}
$sql.="'".trim($clave)."', ";
}
$sql=substr($sql,0,strlen($sql)-2);
$sql.=" )";
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

// Separamos Nivel y Grupo, que viene juntos en el campo Unidad, que finalmente nos cargamos
  $SQL0 = "SELECT a_grupo, id FROM  horw_faltas";
  $result0 =mysqli_query($db_con,$SQL0);
 while  ($row0 =mysqli_fetch_array($result0))
 {
 	if (is_numeric(substr($row0[0],0,1))) 
 	{
$nivel0 = substr($row0[0], 0, 2);

$grupo0 = substr($row0[0], 3, 1);

$actualiza= "UPDATE horw_faltas SET nivel = '$nivel0', n_grupo = '$grupo0' where id = '$row0[1]'";
 	}
 	else {
$actualiza= "UPDATE horw_faltas SET nivel = '', n_grupo = '' where a_grupo = '$row0[0]'";
 	}
mysqli_query($db_con,$actualiza); 
 }
 // Eliminamos residuos y cambiamos alguna cosa.
 
 $sin =mysqli_query($db_con,"SELECT nombre FROM departamentos WHERE nombre not in (select profesor from profesores)");
 if(mysql_num_rows($sin) > "0"){
 while($sin_hor=mysql_fetch_array($sin))
 {
 $prof_sin.=" prof like '%$sin_hor[0]%' or";
 }
 }
 $prof_sin = " and ".substr($prof_sin,0,strlen($prof_sin)-3);
mysqli_query($db_con,"delete from horw_faltas where (1=1 $prof_sin or a_asig  like '%TTA%' or a_asig  like '%TPESO%')");

 $recreo = "DELETE FROM horw_faltas WHERE hora ='4'";
mysqli_query($db_con,$recreo);
 $hora4 = "UPDATE  horw_faltas SET  hora =  '4' WHERE  hora = '5'";
mysqli_query($db_con,$hora4);
 $hora5 = "UPDATE  horw_faltas SET  hora =  '5' WHERE  hora = '6'";
mysqli_query($db_con,$hora5);
 $hora6 = "UPDATE  horw_faltas SET  hora =  '6' WHERE  hora = '7'";
mysqli_query($db_con,$hora6);

 //Elimina las horas no lectivas
  $nolectiva = "UPDATE  horw_faltas SET  nivel =  '', a_grupo = '', n_grupo = '' WHERE  a_grupo NOT LIKE '1%' and a_grupo NOT LIKE '2%' and a_grupo NOT LIKE '3%' and a_grupo NOT LIKE '4%'";
 mysqli_query($db_con,$nolectiva);
 mysqli_query($db_con,"delete from horw_faltas where a_grupo = ''");
 mysqli_query($db_con,"OPTIMIZE TABLE  `horw_faltas`");   
  
  // Cambiamos los numeros de Horw para dejarlos en orden alfabético.
 $hor =mysqli_query($db_con,"select distinct prof from horw order by prof");
while($hor_profe =mysqli_fetch_array($hor)){
	$np+=1;
	$sql = "update horw set no_prof='$np' where prof = '$hor_profe[0]'";
	$sql0 = "update horw_faltas set no_prof='$np' where prof = '$hor_profe[0]'";
	//echo "$sql<br>";
	$sql1 =mysqli_query($db_con,$sql);
	$sql2 =mysqli_query($db_con,$sq0);
}
  // Copia del horario en Reservas
 mysqli_query($db_con,"DROP TABLE IF EXISTS  `reservas`.`horw`");

mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS  `reservas`.`horw` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `dia` CHAR( 1 ) NOT NULL DEFAULT  '',
 `hora` CHAR( 2 ) NOT NULL DEFAULT  '',
 `a_asig` VARCHAR( 8 ) NOT NULL DEFAULT  '',
 `asig` VARCHAR( 64 ) NOT NULL DEFAULT  '',
 `c_asig` VARCHAR( 30 ) NOT NULL DEFAULT  '',
 `prof` VARCHAR( 50 ) NOT NULL DEFAULT  '',
 `no_prof` TINYINT( 4 ) DEFAULT NULL ,
 `c_prof` VARCHAR( 30 ) NOT NULL DEFAULT  '',
 `a_aula` VARCHAR( 5 ) NOT NULL DEFAULT  '',
 `n_aula` VARCHAR( 64 ) NOT NULL DEFAULT  '',
 `a_grupo` VARCHAR( 10 ) NOT NULL DEFAULT  '',
 `nivel` VARCHAR( 10 ) NOT NULL DEFAULT  '',
 `n_grupo` VARCHAR( 10 ) NOT NULL DEFAULT  '',
 `clase` VARCHAR( 16 ) NOT NULL DEFAULT  '',
PRIMARY KEY (  `id` ) ,
KEY  `prof` (  `prof` ) ,
KEY  `c_asig` (  `c_asig` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1");

mysqli_query($db_con,"INSERT INTO  `reservas`.`horw`
SELECT * 
FROM  `faltas`.`horw`");
  
echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Tabla <strong>Horw</strong>: los datos se han introducido correctamente en la Base de datos. Es necesario que actualizes las tablas de Profesores, una vez actualizados los horarios.<br>Vuelve a la página principal y actualiza los Profesores inmediatamente.
</div></div><br />';

?> 
<br />
<div align="center">
  <input type="button" value="Volver atrás" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div>