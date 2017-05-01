<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

require("../../pdf/pdf_js.php");
//require("../pdf/mc_table.php");

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
}
define ( 'FPDF_FONTPATH', '../../pdf/font/' );
# creamos el nuevo objeto partiendo de la clase ampliada
$MiPDF = new PDF_AutoPrint();
$MiPDF->SetMargins ( 20, 20, 20 );
# ajustamos al 100% la visualizaciÃ³n
$MiPDF->SetDisplayMode ( 'fullpage' );
// Consulta  en curso. 
if (substr($curso, 0, 1) == '1') {
	$mas = ", colegio";
}
//echo "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" ;
$result0 = mysqli_query($db_con, "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" );
while ($id_ar = mysqli_fetch_array($result0)) {
$id = "";
$id = $id_ar[0];
$result = mysqli_query($db_con, "select * from matriculas where id = '$id'");
if ($row = mysqli_fetch_array ( $result )) {
	$apellidos = utf8_decode("Apellidos del Alumno: ". $row['apellidos']);
	 $nombre= utf8_decode("Nombre: ".$row['nombre']);
	 $nacido= utf8_decode("Nacido en: ".$row['nacido']);
	 $nacimiento = cambia_fecha($row['nacimiento']);
	 $provincia= utf8_decode("Provincia: ".$row['provincia']);
	 $fecha_nacimiento= "Fecha de Nacimiento: $nacimiento";
	 $domicilio= utf8_decode("Domicilio: ".$row['domicilio']);
	 $localidad= utf8_decode("Localidad: ".$row['localidad']);
	 $dni= "DNI del alumno: ".$row['dni'];
	 $padre= utf8_decode("Apellidos y nombre del Tutor legal 1: ".$row['padre']);
	 $pa = explode(", ", $row['padre']);
	 $papa = utf8_decode("$pa[1] $pa[0]");
	 $dnitutor= "DNI: ".$row['dnitutor'];
	 $madre= utf8_decode("Apellidos y nombre del Tutor legal 2: ".$row['madre']);
	 $dnitutor2= "DNI: ".$row['dnitutor2'];
	 $telefono1= utf8_decode("Teléfono Casa: ".$row['telefono1']);
	 $telefono2= utf8_decode("Teléfono Móvil: ".$row['telefono2']);
	 $telefonos="$telefono1\n   $telefono2";
	 $idioma = utf8_decode($row['idioma']);
	 $religion = utf8_decode($row['religion']);
	 $itinerario = $row['itinerario'];
	 $optativas4 = utf8_decode($row['optativas4']);
	 $matematicas3 = utf8_decode($row['matematicas3']);
	 $ciencias4 = utf8_decode($row['ciencias4']);

	 if ($row['colegio'] == "Otro Centro") { $colegio= utf8_decode("Centro de procedencia:  ".$row['otrocolegio']); }else{	 utf8_decode($colegio= "Centro de procedencia:  ".$row['colegio']); }
	 $correo= utf8_decode("Correo electrónico de padre o madre: ".$row['correo']);

	 // Optativas y refuerzos
	 $n_curso = substr($curso, 0, 1);
	 $n_curso2 = $n_curso-1;

	include 'asignaturas.php';


	for ($i=1;$i<8;$i++){
		$ni = $i-1;
		$n_o = 0;
		if ($n_curso==4) {
			foreach ($opt4 as $abrev => $val) {
				if ($n_o==$ni) {
					${optativa.$i} = utf8_decode($row['optativa'.$i]." - ".$val);
				}
				$n_o++;
			}
			
		}
		else{
			${optativa.$i} = utf8_decode($row['optativa'.$i]." - ".${opt.$n_curso}[$ni]);
		}
	}


	for ($i=1;$i<8;$i++)
	 {
	 	if ($row['act1'] == $i) {
	 		${act.$i} = utf8_decode(" X  " . ${a.$n_curso}[$i-1]);
	 	}
	 	else{
	 		${act.$i} = utf8_decode("      ".${a.$n_curso}[$i-1]);
	 	}
	 }
	 

	for ($i=1;$i<8;$i++){
		$ni = $i-1;
		$ncr = $n_curso-1;
			${optativa2.$i} = utf8_decode($row['optativa2'.$i]." - ".${opt.$ncr}[$ni]);
	}
	 
	 for ($i=1;$i<8;$i++)
	 {
	 	$nca = $n_curso-1;
	 	if ($row['act21'] == $i) {
	 		${act2.$i} = utf8_decode(" X  " . ${a.$nca}[$i-1]);

	 	}
	 	else{
	 		${act2.$i} = utf8_decode("      ".${a.$nca}[$i-1]);
	 	}
	 }

	 if ($n_curso == '4'){
	 	for ($i=1;$i<7;$i++)
	 {
	 	if ($row['act1'] == $i) {
			${act2.$i} = utf8_decode(" X  " . $a21[$i-1]);	
		 }
		 else{
	 		${act2.$i} = utf8_decode("      ".$a21[$i-1]);
	 	}
	 }
	}

	 $observaciones= utf8_decode("OBSERVACIONES: ".$row['observaciones']);
	 $texto_exencion= utf8_decode("El alumno solicita la exención de la Asignatura Optativa");
	 $texto_bilinguismo= "El alumno solicita participar en el Programa de Bilinguismo";
	 $curso = $row['curso'];
	 $fecha_total = $row['fecha'];
	 $transporte = $row['transporte'];
	 $ruta_este = $row['ruta_este'];
	 $ruta_oeste = $row['ruta_oeste'];
	 $texto_transporte = utf8_decode("Transporte escolar: $ruta_este$ruta_oeste.");
	 $sexo = $row['sexo'];
	 if ($row['hermanos'] == '' or $row['hermanos'] == '0') { $hermanos = ""; } else{ $hermanos = $row['hermanos']; }
	 
	 $nacionalidad = utf8_decode($row['nacionalidad']);
	 $itinerario = $row['itinerario'];
	 $optativas4 = utf8_decode($row['optativas4']);
}

	# insertamos la primera pagina del documento
	$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	
	
	// Formulario de matrícula

	#Cuerpo.
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 10 );
	$MiPDF->Multicell ( 0, 4, $titulo1, 0, 'C', 0 );
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(168,6,"DATOS PERSONALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$apellidos,1);

	$MiPDF->Cell(56,8,$nombre,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(56,8,$nacido,1);
	$MiPDF->Cell(56,8,$provincia,1);
	$MiPDF->Cell(56,8,$fecha_nacimiento,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(72,8,$domicilio,1);
	$MiPDF->Cell(40,8,$localidad,1);
	$MiPDF->Cell(56,8,$dni,1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$padre,1);
	$MiPDF->Cell(56,8,$dnitutor,1);
	if (strlen($madre)>38) {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(112,8,$madre,1);
	$MiPDF->Cell(56,8,$dnitutor2,1);
	}
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(90,8,$telefonos,1);
	$MiPDF->Cell(78,8,$colegio,1);
	if ($transporte=='1') {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(168,8,$texto_transporte,1);	
	}
	if (strlen($correo)>38) {
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(168,8,$correo,1);	
	}
	$MiPDF->Ln ( 10 );
	$MiPDF->Cell(84,6,"IDIOMA EXTRANJERO",1,0,'L',1);
	$MiPDF->Cell(84,6,utf8_decode("ENSEÑANZA DE RELIGIÓN O ALTERNATIVA"),1,0,'L',1);
	$MiPDF->Ln ( 6);
	$MiPDF->Cell(84,8,$idioma,0);
	$MiPDF->Cell(84,8,$religion,0);
	$MiPDF->Ln ( 8 );
	if($n_curso<'3'){
	$MiPDF->Cell(84,6,"ASIGNATURAS OPTATIVAS",1,0,'L',1);
	$MiPDF->Cell(84,6,"PROGRAMA DE REFUERZO O ALTERNATIVO",1,0,'L',1);
	$MiPDF->Ln ( 6 );
	}
	else{
		if($n_curso=='4'){

	$extra_it="";
	if(stristr($itinerario,"1")==TRUE){$extra_it="1 (".$ciencias4.")";}
	else{$extra_it=$itinerario." ";}
	//echo $ciencias4;
	if(strlen($optativas4)>1){$extra_it.=utf8_decode(" - $optativas4");}	
	//if ($n_curso == '4') { $extra="4ESO (It. $itinerario".$extra_it.")";}
	
	$MiPDF->Cell(168,6,"ITINERARIO $extra_it.",1,0,'C',1);
	$MiPDF->Ln ( 6 );
		}
		else{
	$MiPDF->Cell(168,6,"ASIGNATURAS OPTATIVAS",1,0,'C',1);
	$MiPDF->Ln ( 6 );
		}
	}
	if($n_curso=='3'){
	if ($matematicas3=="A") {
		$mat_3=utf8_decode("Matemáticas Académicas (Bachillerato)");}elseif($matematicas3=="B"){$mat_3=utf8_decode("Matemáticas Aplicadas (Formación Profesional)");
		}
	$MiPDF->Cell(168,6,$mat_3,1,0,'C',0);
	$MiPDF->Ln ( 5 );
	}

	// $MyPDF->FillColor();
	if($n_curso<4){
	$MiPDF->Cell(84,8,$optativa1,0);
	$MiPDF->Cell(84,8,$act1,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa2,0);
	$MiPDF->Cell(84,8,$act2,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa3,0);
	$MiPDF->Cell(84,8,$act3,0);
	$MiPDF->Ln ( 5 );
	if ($n_curso=='2') {
	$MiPDF->Cell(84,8,"",0);
	}
	else{
	$MiPDF->Cell(84,8,$optativa4,0);
	}
	$MiPDF->Cell(84,8,$act4,0);
	$MiPDF->Ln ( 5 );

	if ($n_curso=='1') {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act5,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act6,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act7,0);
	$MiPDF->Ln ( 5 );
	}
	elseif ($n_curso=='2') {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act5,0);
	}
	elseif ($n_curso=='3') {
	$MiPDF->Cell(84,8,$optativa5,0);
	$MiPDF->Cell(84,8,$act5,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa6,0);
	$MiPDF->Cell(84,8,$act6,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa7,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Ln ( 5 );
	}
	}
	elseif($n_curso=='4'){
	$MiPDF->Cell(168,6,"ASIGNATURAS OPTATIVAS",1,0,'C',1);
	$MiPDF->Ln ( 6 );
	$MiPDF->Cell(84,8,$optativa1,0);
	$MiPDF->Cell(84,8,$optativa4,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa2,0);
	$MiPDF->Cell(84,8,$optativa5,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa3,0);
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Ln ( 5 );
	}

	if (substr($curso, 0, 1) == 2 or substr($curso, 0, 1) == 3 or substr($curso, 0, 1) == 4){
	$MiPDF->Ln ( 7 );
	$MiPDF->Cell(168,6,utf8_decode("ASIGNATURAS DE ".$n_curso2."º DE ESO"),1,0,'C',1);
	$MiPDF->Ln ( 6 );
	$MiPDF->Cell(84,6,"ASIGNATURA OPTATIVA",1,0,'L',1);
	$MiPDF->Cell(84,6,"PROGRAMA DE REFUERZO O ALTERNATIVO",1,0,'L',1);
	$MiPDF->Ln ( 6 );
	// $MyPDF->FillColor();
	$MiPDF->Cell(84,8,$optativa21,0);
	$MiPDF->Cell(84,8,$act21,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa22,0);
	$MiPDF->Cell(84,8,$act22,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa23,0);
	$MiPDF->Cell(84,8,$act23,0);
	$MiPDF->Ln ( 5 );
	if (substr($curso, 0, 1) == 3){
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act24,0);
	$MiPDF->Ln ( 5 );
	}
	else{
	$MiPDF->Cell(84,8,$optativa24,0);
	$MiPDF->Cell(84,8,$act24,0);
	$MiPDF->Ln ( 5 );
	}

	if (substr($curso, 0, 1) == 4) {
	$MiPDF->Cell(84,8,$optativa25,0);
	$MiPDF->Cell(84,8,$act25,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa26,0);
	$MiPDF->Cell(84,8,$act26,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,$optativa27,0);
	$MiPDF->Cell(84,8,"",0);
		}
	if (substr($curso, 0, 1) == 2 or substr($curso, 0, 1) == 3) {
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act25,0);
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,8,"",0);
	$MiPDF->Cell(84,8,$act26,0);
		}
	}

	else{
	$MiPDF->Ln ( 7 );		
	}
		
	if ($row[39]=='1') {
	$MiPDF->Ln ( 7 );	
	$MiPDF->Cell(168,5,$texto_exencion,1,0,'L',1);
	}
	if ($row[40]=='Si') {
		$MiPDF->Ln ( 7 );
		$MiPDF->Cell(168,5,$texto_bilinguismo,1,0,'L',1);
	}
	$MiPDF->Ln ( 8 );
	if (strlen($observaciones)>15) {
	$MiPDF->MultiCell(168,4,$observaciones,0,'L');
	$MiPDF->Ln ( 3);		
	}
	else{
	$MiPDF->Ln ( 8 );		
	}

	}
   $MiPDF->AutoPrint(true);     
   $MiPDF->Output ();

?>