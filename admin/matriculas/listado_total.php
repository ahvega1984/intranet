<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1, 8));

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($_POST['grupo_actual'])) {
	$grupo_actual = $_POST['grupo_actual'];
	$sql = "select distinct curso, grupo_actual from matriculas where grupo_actual = '$grupo_actual[0]' and curso='$curso' order by curso, grupo_actual";
}
else{
	$sql = "select distinct curso, grupo_actual from matriculas where grupo_actual != '' order by curso, grupo_actual";
}

require_once('../../pdf/class.ezpdf.php');

$pdf = new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$tot = mysqli_query($db_con, $sql);
while($total = mysqli_fetch_array($tot)){
# hasta aquÃ­ lo del pdf
$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
					);
	$curso = $total[0];
	$grupo_actual = $total[1];
	
$sqldatos="SELECT concat(apellidos,', ',nombre), exencion, optativa1, optativa2, optativa3, optativa4, optativa5, optativa6, optativa7, act1, religion, diversificacion, matematicas3, bilinguismo, itinerario, optativas4, ciencias4 FROM matriculas WHERE curso = '$curso' and grupo_actual='".$grupo_actual."' ORDER BY apellidos, nombre";
//echo $sqldatos;

$div3=mysqli_query($db_con,"SELECT diversificacion FROM matriculas WHERE curso = '$curso' and grupo_actual='".$grupo_actual."' and diversificacion='1'");
if (mysqli_num_rows($div3)>0) {
	$div_3 = $grupo_actual;
}		
	
$lista= mysqli_query($db_con, $sqldatos );
$nc=0;
unset($data);
while($datatmp = mysqli_fetch_array($lista)) { 
        $bil = "";
        if($datatmp['bilinguismo']=="Si"){$bil = " (Bil.)";}
        
$religion = "";
	
if ($curso=="3ESO") {
for ($i = 2; $i < ($count_3+2); $i++) {
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="X";
		}
		else{
			$datatmp[$i]="";
		}
	}
	// Diversificación
if ($datatmp['diversificacion']=="1") {
			$datatmp['diversificacion']="X";
		}
		else{
			$datatmp['diversificacion']="";
		}
}
else {
if ($curso=="2ESO") {
	// Diversificación
	if ($datatmp['diversificacion']=="1") {
			$datatmp['diversificacion']="X";
		}
		else{
			$datatmp['diversificacion']="";
		}
}	
for ($i = 2; $i < 7; $i++) {
	if ($curso=="4ESO") {
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="1";
		}
		elseif ($datatmp[$i]=="2") {
			$datatmp[$i]="2";
		}
		else{
			$datatmp[$i]="";
		}	
	}
	else{
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="X";
		}
		else{
			$datatmp[$i]="";
		}	
	}

	
	}
	
}

for ($i = 0; $i < 10; $i++) {
		if ($datatmp[$i]=="0") {
			$datatmp[$i]="";
		}
	}
	
$nc+=1;

if (strstr($datatmp['religion'],"Cat")==TRUE) {
	$religion ="X";
}

if ($curso=="3ESO") {
	$num="";
	$act = "\nActividades de Refuerzo y Ampliación:
	";
	foreach ($a3 as $val) {
		$num++;
		$act.="$num => $val, ";
	}
	$act = utf8_decode(substr($act, 0, -2));

	$opt = "\nOptativas:
	";
	$num="";
	foreach ($opt3 as $val) {
		$num++;
		$opt.="$num => $val, ";
	}
	$opt = substr($opt, 0, -2);

	if ($div_3 == $grupo_actual) {
			$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0]).$bil,
				'c9'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp[6],
				'c7'=>$datatmp[7],
				'c8'=>$datatmp[8],
				'c11'=>$datatmp['diversificacion'],
				'c12'=>$datatmp['matematicas3'],
				'c13'=>$datatmp['act1'],
				
				);
	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c9'=>'Rel. Cat.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c4'=>'O3',
				'c5'=>'O4',
				'c6'=>'O5',
				'c7'=>'O6',
				'c8'=>'O7',
				'c11'=>'PMAR',
				'c12'=>'Mat',
				'c13'=>'Act',
			);
	}
	else{
			$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0]).$bil,
				'c10'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp[6],
				'c7'=>$datatmp[7],
				'c8'=>$datatmp[8],
				'c9'=>$datatmp['matematicas3'],
				'c11'=>$datatmp['act1'],
				);
	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c10'=>'Rel. Cat.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c4'=>'O3',
				'c5'=>'O4',
				'c6'=>'O5',
				'c7'=>'O6',
				'c8'=>'O7',
				'c9'=>'Mat',
				'c11'=>'Act',
			);
	}
}

if ($curso=="2ESO") {
	
	$act = "\nActividades de Refuerzo y Ampliación:
	";
	$num="";
	foreach ($a2 as $val) {
		$num++;
		$act.="$num => $val, ";
	}
	$act = utf8_decode(substr($act, 0, -2));

	$opt = "\nOptativas:
	";
	$num="";
	foreach ($opt2 as $val) {
		$num++;
		$opt.="$num => $val, ";
	}
	$opt = substr($opt, 0, -2);

	if ($div_3 == $grupo_actual) {
				$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0]),
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp['act1'],
				'c6'=>$datatmp['diversificacion'],
				);

	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Act.',
				'c6'=>'PMAR',
			);
	}
	else{
			$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0]),
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp['act1'],
				);

	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Act.',
			);
	}

}


if ($curso=="1ESO") {

	$act = "\nActividades de Refuerzo y Ampliación:
	";
	$num="";
	foreach ($a1 as $val) {
		$num++;
		$act.="$num => $val, ";
	}
	$act = utf8_decode(substr($act, 0, -2));

	$opt = "\nOptativas:
	";
	$num="";
	foreach ($opt1 as $val) {
		$num++;
		$opt.="$num => $val, ";
	}
	$opt = substr($opt, 0, -2);
	
	$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0]),
				'c7'=>$religion,
				'c2'=>$datatmp[2],
				'c3'=>$datatmp[3],
				'c4'=>$datatmp[4],
				'c5'=>$datatmp[5],
				'c6'=>$datatmp['act1'],
				);

	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c7'=>'Rel. Cat.',
				'c2'=>'Opt1',
				'c3'=>'Opt2',
				'c4'=>'Opt3',
				'c5'=>'Opt4',
				'c6'=>'Act.',
			);
}


if ($curso=="4ESO") {
	$opt="";
	for ($i=1;$i<4;$i++) { 
		${n_opt.$i}="";
		${n_opt.$i}.= "\nOptativas Modalidad Itinerario $i (".${it4.$i}[0]." -> ".${it4.$i}[1]."): ";
		$num="";
		foreach (${it4.$i} as $val) {
			$num++;
			if ($num>2) {
				$num_opt = $num-2;
				${n_opt.$i}.="$num_opt => $val, ";
			}			
		}
		${n_opt.$i} = substr(${n_opt.$i}, 0, -2);
		${n_opt.$i}.="; ";
		$opt.=${n_opt.$i};
	}
	$opt.=" ";
	$opt.= "\nOptativas generales de 4 ESO: ";
$num="";
	foreach ($opt4 as $val) {
		$num++;
		$opt_gen.="$num => $val, ";
	}
	$opt.=$opt_gen;
	$opt = substr($opt, 0, -2);
	$opt.=" ";

if ($datatmp['itinerario']==1) {
	$extra_itin="(".$datatmp['ciencias4'].")";
}
else{$extra_itin="";}

$opt4="";
$opt4 = iniciales($datatmp['optativas4']);

if ($datatmp['exencion']==1) {
	$exencion = 'X';
}
else{
	$exencion = '';
}
$data[] = array(
				'num'=>$nc,
				'nombre'=>utf8_decode($datatmp[0].$bil),
				'c8'=>$religion,
				'It.'=>$datatmp['itinerario'].$extra_itin,
				'c2'=>$datatmp['optativa1'],
				'c3'=>$datatmp['optativa2'],
				'c7'=>$opt4,
				'c9'=>$exencion,
				);

	$titles = array(
				'num'=>'<b>NC</b>',
				'nombre'=>'<b>Alumno</b>',
				'c8'=>'Rel. Cat.',
				'It.'=>'Itiner.',
				'c2'=>'O1',
				'c3'=>'O2',
				'c7'=>'OptGen',
				'c9'=>'Ex.',
			);
		}
	}


$options = array(
				'textCol' => array(0.2,0.2,0.2),
				'innerLineThickness'=>0.5,
				'outerLineThickness'=>0.7,
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>500
			);

$txttit = "Lista del Grupo ".$curso."-".$grupo_actual."\n";
$txttit.= utf8_decode($config['centro_denominacion']).". Curso ".$config['curso_actual'].".\n";
	
$pdf->ezText($txttit, 13,$options_center);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezText(utf8_decode($opt), '10', $options);
if ($curso !== "4ESO") {
	$pdf->ezText($act, '10', $options);
}
$pdf->ezText("\n", 10);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_right);
	
$pdf->ezNewPage();
}
$pdf->ezStream();
?>
