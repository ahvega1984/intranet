<? $nums_ids=0;
foreach ($_POST as $id => $valor) {
  if (is_numeric($id) and is_numeric($valor)){
$columnas = $columnas + 1;
 $num_ids +=1;
$celdas .= " id = '$id' or";
  		}
  		}
 $celdas = substr($celdas,0,strlen($celdas)-3);
	if (empty($num_ids)) {
 echo '<br /><div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIӓN:</h5>
Debes seleccionar al menos una Columna del cuaderno para poder operar.
</div></div>';
 echo "<br /><INPUT TYPE='button' VALUE='Volver al Cuaderno' onClick='history.back(-1)' class='btn btn-primary'>";	
exit;			
		}
// Procesamos los datos
// Distintos códigos de la asignatura cuando hay varios grupos en una hora.
$n_c = mysqli_query($db_con, "SELECT distinct  a_grupo, asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'");
while($varias = mysqli_fetch_array($n_c))
{
	if (substr($varias[0],3,2) == "Dd" ) {
	$varias[0] = substr($varias[0],0,4);
	}
	$curso_alma = mysqli_query($db_con, "select distinct curso from alma where unidad = '$varias[0]'");
	$curso_alma1 = mysqli_fetch_row($curso_alma);
	$nombre_curso = $curso_alma1[0];
	$largo = strlen($varias[1]);
	if (strlen($varias[1]) > 10) {$nombre_asig = substr($varias[1],0,10);} else {$nombre_asig = substr($varias[1],0,6);}	
	$nombre_asig = trim($nombre_asig);
	$asig_sen0 = mysqli_query($db_con, "select codigo from asignaturas where curso = '$nombre_curso' and nombre like '$nombre_asig%' and abrev not like '%º'");
	while($asig_sen1 = mysqli_fetch_row($asig_sen0)){
	if (strstr($asigna_a , $asig_sen1[0]) == false) 
	{
	$asigna_a .= $asig_sen1[0].",";
	}
	}
}
$asigna_c = explode(",",$asigna_a);
$asignatura0 = $asigna_c[0];
$asignatura1 = $asigna_c[1];
$asignatura2 = $asigna_c[2];
if (!(empty($asignatura1))) {
	$otras = " or combasi like '%$asignatura1:%' ";
}
if (!(empty($asignatura2))) {
	$otras .= " or combasi like '%$asignatura2:%' ";
}
// Tabla con las distintas notas_cuaderno y la mediaxx
  		
  // Todos los Grupos juntos
$n_cursos = mysqli_query($db_con, "SELECT distinct  a_grupo, c_asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'");
  while($n_cur = mysqli_fetch_array($n_cursos))
  {
  	$curs .= $n_cur[0].", ";
  } 
// Eliminamos el espacio
  	$curs0 = substr($curs,0,(strlen($curs)-1));
// Eliminamos la última coma para el título.
	$curso_sin = substr($curs0,0,(strlen($curs0)-1));
//Número de columnas
	
	$col = "select distinct id, nombre, orden from notas_cuaderno where profesor = '$profesor' and curso like '%$curso%' and oculto = '0' and ($celdas)  order by orden asc";
	$col0 = mysqli_query($db_con, $col);
	
	$curso_sin = substr($curso,0,strlen($curso) - 1);
// Titulos

echo "<table align='center' class='table table-striped' style='width:auto'>"; 
echo "<thead><th style='vertical-align:bottom;background-color:#fff'>NC</th><th colspan='2' style='vertical-align:bottom;background-color:#fff'>Alumno</th>";
// Número de las columnas de la tabla	
	while($col20 = mysqli_fetch_array($col0)){
	$ident= $col20[2];
	$id = $col20[0];
		echo "<th nowrap style='background-color:#fff'>
<div style='width:40px;height:130px;'>
<div class='Rotate-90'><span class='text-info text-lowercase'>$col20[1]</span> </div>
</div> </th>";
		}
echo "</thead>";
// Tabla para cada Grupo
  $curso0 = "SELECT distinct  a_grupo, c_asig, asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'";
  $curso20 = mysqli_query($db_con, $curso0);
  while ($curso11 = mysqli_fetch_array($curso20))
    {
	$curso = $curso11[0];
	$asignatura = $curso11[1];
	$nombre = $curso11[2];
// Número de Columnas para crear la tabla
	$num_col = 4 + $num_ids;
	$nivel_curso = substr($curso,0,1);			
	
	//	Problemas con Diversificaci�n (4E-Dd)
			$profe_div = mysqli_query($db_con, "select * from profesores where grupo = '$curso'");
			if (mysqli_num_rows($profe_div)<1) {		
				
				$div = $curso;
				$grupo_div = mysqli_query($db_con, "select distinct unidad from alma where unidad like '$nivel_curso%' and (combasi like '%143733%' or combasi LIKE '%143727%')");
				$grupo_diver = mysqli_fetch_row($grupo_div);
				$curso = $grupo_diver[0];
			}

	mysqli_select_db($db_con, $db);
	$hay0 = "select alumnos from grupos where profesor='$profesor' and asignatura = '$asignatura' and curso = '$curso'";
	$hay1 = mysqli_query($db_con, $hay0);
	$hay = mysqli_fetch_row($hay1);	
	
	if(mysqli_num_rows($hay1) == "1"){
	$seleccionados = substr($hay[0],0,strlen($hay[0])-1);
	$t_al = explode(",",$seleccionados);
	$todos = " and (nc = '300'";
	foreach($t_al as $cadauno){
	$todos .=" or nc = '$cadauno'";
	}
	$todos .= ")";
	}	
	mysqli_select_db($db_con, $db);
	
// Alumnos para presentar que tengan esa asignatura en combasi
$resul = "select distinctrow FALUMNOS.CLAVEAL, FALUMNOS.NC, FALUMNOS.APELLIDOS, FALUMNOS.NOMBRE, alma.MATRICULAS, alma.combasi from FALUMNOS, alma WHERE FALUMNOS.CLAVEAL = alma.CLAVEAL and alma.unidad = '$curso' and (combasi like '%$asignatura0:%' $otras) ".$todos ." order by NC";
  $result = mysqli_query($db_con, $resul);	
  $t_alumnos += mysqli_num_rows ($result);
        while($row = mysqli_fetch_array($result))
		{  		
		$claveal = $row[0];            	
echo "<tr><td>$row[1]</td><td colspan='2' nowrap >$row[2], $row[3]</td>";
  	
// Si hay datos escritos rellenamos la casilla correspondiente
	$colu10 = "select distinct id from notas_cuaderno where ";
	 foreach ($_POST as $id => $valor) {
  		if (is_numeric($id) and is_numeric($valor)){
  		$colu10 .= " id = '$id' or"; 
  		$n_1 = "1";
  		}
  		}
  	$colu10 = substr($colu10,0,strlen($colu10)-2);
	$colu10 .= "  and profesor = '$profesor' and curso like '%$curso%' and oculto = '0'order by id";
	$colu20 = mysqli_query($db_con, $colu10);
	$suma = "";
	while($colus10 = mysqli_fetch_array($colu20)){
	$id = $colus10[0];
	$dato0 = mysqli_query($db_con, "select nota from datos where claveal = '$claveal' and id = '$id'");
	$dato1 = mysqli_fetch_array($dato0);
	$suma += $dato1[0];
	if ($dato1[0]==''){$dato1[0]='0';} 
	if ($dato1[0]<'5'){ $color = ' class="text-danger" ';}else{ $color = ' class="text-success" ';} 
echo "<td align='center' $color>$dato1[0]</td>";}

mysqli_select_db($db_con, $db);
echo "</tr>"; 
        }  
	}
	$i=0;
	foreach ($_POST as $id => $valor2) {
if (is_numeric($id)){
$i+=1;
	$aprobados[$i]=0;
	$suspensos[$i]=0;
	$sumanotas[$i]=0;

$est=mysqli_query($db_con, "select nota from datos where id='$id'");	
while ($esta=mysqli_fetch_array($est)){
	
	
		if(($esta[0] < 5) or ($esta[0]=='')){$suspensos[$i]+=1;  $sumanotas[$i]+=$esta[0];}
	else{$aprobados[$i]+=1;  $sumanotas[$i]+=$esta[0]; 
	     	
		}
				}
	
	}}
	
	//media del grupo
	echo "</tr><tr class='info'><td colspan='3' align='left' style='font-weight:bold;'>Media del Grupo</td>";
		for($j = 1;$j<=$i;$j++) {
	$x_real=$sumanotas[$j]/$t_alumnos;
	$x=$sumanotas[$j]/($aprobados[$j]+$suspensos[$j]);
	echo "<td align='center'><b>"; redondeo($x_real); echo"</b></td>";
							}


	echo "</tr><tr class='success'><td colspan='3' align='left' style='font-weight:bold;'>Aprobados</td>";
								for($j = 1;$j<=$i;$j++) {
	echo "<td align='center'>$aprobados[$j]</td>";
							}


	echo "</tr><tr class='danger'><td colspan='3' align='left' style='font-weight:bold;'>Suspensos</td>";
								for($j = 1;$j<=$i;$j++) {
	  $t_s[$j]=$t_alumnos-$aprobados[$j];
	echo "<td align='center'>$t_s[$j]</td>";
						}
  
	echo "</tr>";	
echo '</table>';
echo "<br /><INPUT TYPE='button' VALUE='Volver al Cuaderno' onClick='history.back(-1)' class='btn btn-primary hidden-print'>";	
?>
