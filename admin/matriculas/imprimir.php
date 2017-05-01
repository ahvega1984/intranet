<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

require("../../pdf/pdf_js.php");

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
$n_curso = substr($curso, 0, 1);
$result0 = mysqli_query($db_con, "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" );
//echo "select distinct id_matriculas from matriculas_temp, matriculas where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre";
while ($id_ar = mysqli_fetch_array($result0)) {
$id = $id_ar[0];
$result = mysqli_query($db_con, "select * from matriculas where id = '$id'");
if ($row = mysqli_fetch_array ( $result )) {
	$apellidos = "Apellidos del Alumno: ". $row['apellidos'];
	 $nombre= "Nombre: ".$row['nombre'];
	 $nacido= "Nacido en: ".$row['nacido'];
	 $nacimiento = cambia_fecha($row['nacimiento']);
	 $provincia= "Provincia de: ".$row['provincia'];
	 $fecha_nacimiento= "Fecha de Nacimiento: $nacimiento";
	 $domicilio= "Domicilio: ".$row['domicilio'];
	 $localidad= "Localidad: ".$row['localidad'];
	 $dni= "DNI del alumno: ".$row['dni'];
	 $padre= "Apellidos y nombre del Tutor legal 1: ".$row['padre'];
	 $pa = explode(", ", $row['padre']);
	 $papa = "$pa[1] $pa[0]";
	 $dnitutor= "DNI: ".$row['dnitutor'];
	 $madre= "Apellidos y nombre del Tutor legal 2: ".$row['madre'];
	 $dnitutor2= "DNI: ".$row['dnitutor2'];
	 $telefono1= "Teléfono Casa: ".$row['telefono1'];
	 $telefono2= "Teléfono Móvil: ".$row['telefono2'];
	 $telefonos="$telefono1\n   $telefono2";
	 $idioma = $row['idioma'];
	 $religion = $row['religion'];
	 $itinerario = $row['itinerario'];
	 $optativas4 = $row['optativas4'];
	  if(stristr($optativas4,'Iniciaci')==TRUE){$optativas4='Iniciación Actividad Emprendedora';}
	 $matematicas3 = $row['matematicas3'];
	 if ($row['colegio'] == "Otro Centro") { $colegio= "Centro de procedencia:  ".$row['otrocolegio']; }else{	 $colegio= "Centro de procedencia:  ".$row['colegio']; }
	 $correo= "Correo electrónico de padre o madre: ".$row['correo'];
	 // Optativas y refuerzos
	 $n_curso = substr($curso, 0, 1);
	 $n_curso2 = $n_curso-1;


	include 'asignaturas.php';

	mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `temp` (
  		`clave` varchar(64) collate utf8_general_ci NOT NULL default '',
  		`valor` int(1) NOT NULL default '0'
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

	for ($i=1;$i<8;$i++){
		$ni = $i-1;
		$n_o = 0;
		if ($n_curso==4) {
			foreach ($opt4 as $abrev => $val) {
				if ($n_o==$ni and $row['optativa'.$i]>0) {
					mysqli_query($db_con,"insert into temp (clave, valor) VALUES ( '$val','".$row['optativa'.$i]."')");
				}
				$n_o++;
			}		
		}
		else{
			if ($row['optativa'.$i]>0) {
				mysqli_query($db_con,"insert into temp (clave, valor) VALUES ('".${opt.$n_curso}[$ni]."', '".$row['optativa'.$i]."')");
			}
		}
	}

	$opt="";
	$o_p = mysqli_query($db_con,"select * from temp order by valor");
				while($o_p0 = mysqli_fetch_array($o_p)){
					$opt .= $o_p0[1].": ".$o_p0[0]."; ";	

				}
	mysqli_query($db_con,"drop table temp");
	

	for ($i=1;$i<8;$i++)
	 {
	 	if ($row['act1'] == $i) {
	 		${act.$i} = utf8_decode(" X  " . ${a.$n_curso}[$i-1]);
	 	}
	 	else{
	 		${act.$i} = utf8_decode("      ".${a.$n_curso}[$i-1]);
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
$fech = explode(" ",$fecha_total);
$fecha = $fech[0];
//$hoy = formatea_fecha($fech[0]);
$an = substr($config['curso_actual'],0,4);
$an1 = $an+1;
$hoy = formatea_fecha(date('Y-m-d'));
$titulo_documentacion = utf8_decode("DOCUMENTACIÓN NECESARIA PARA LA MATRICULACIÓN");
$documentacion = utf8_decode("1. Fotocopia del D.N.I. Obligatorio por ley para todo alumnado mayor de 14 años. Si el alumnado es menor de 14 años y no se dispone de D.N.I., se admitirá una fotocopia del Libro de Familia o Certificado de Nacimiento.
2. El alumnado procedente de otros Institutos o de Colegios no adscritos a nuestro Centro deben aportar el Certificado de expediente académico..
3. Los alumnos que se matriculen a partir de 3º de ESO tienen que abonar 2 euros para la cuota obligatoria del Seguro Escolar.
4. Cuota voluntaria de 12 euros para la Asociación de Padres y Madres del Centro.
");
$datos_junta = utf8_decode("PROTECCIÓN DE DATOS.\n En cumplimiento de lo dispuesto en la Ley Orgánica 15/1999, de 13 de Diciembre, de Protección de Datos de Carácter Personal, la Consejería de Educación le informa que los datos personales obtenidos mediante la cumplimentación de este formulario y demás documentación que se adjunta van a ser incorporados, para su tratamiento, al fichero 'Séneca. Datos personales y académicos del alumnado', con la finalidad de recoger los datos personales y académicos del alumnado que cursa estudios en centros dependientes de la Conserjería de Educación, así como de las respectivas unidades familiares.\n De acuerdo con lo previsto en la Ley, puede ejercer los derechos de acceso, rectificación, cancelación y oposición dirigiendo un escrito a la Secretaría General Técnica de la Conserjería de Educación de la Junta de Andalucía en Avda. Juan Antonio de Vizarrón, s/n, Edificio Torretriana 41071 SEVILLA");

// Normas de telefonía móvil
$titulo_moviles = utf8_decode("SOBRE EL USO DE TELÉFONOS MÓVILES Y OTROS DISPOSITIVOS EN EL CENTRO");

$texto_moviles=utf8_decode("
           Estimadas familias:

     Les informamos de que está prohibido el uso de teléfonos móviles y otros dispositivos de grabación/reproducción multimedia por parte del alumnado durante el horario escolar. Dicha medida es consecuencia de salvaguardar la intimidad tanto del alumnado como del profesorado, quienes pudieran ver vulnerados sus derechos de protección por grabaciones y/o difusiones de imágenes capturadas de forma ajena a su voluntad. Por este motivo, recordamos que la utilización de estos aparatos está prohibida en el Centro. En caso de que algún alumno sea sorprendido con cualquier dispositivo electrónico, este le será requisado aplicándose las medidas que en materia de convivencia hay estipuladas en nuestro Reglamento al efecto.
     El teléfono móvil en el Centro es absolutamente innecesario y constituye un elemento perturbador del clima de estudio y trabajo en el mismo. En aquellos casos en los que el alumnado tenga que comunicarse con la familia (que se entienden como situaciones graves o de urgencia), los teléfonos del Centro están siempre a disposición del alumnado. 
     Por último anunciar que dado que se ha dejado claro que están prohibidos estos dispositivos en el instituto, informamos que el Centro no se hace responsable ni va a mediar en situaciones donde se produzcan <<desapariciones>> de dichos dispositivos dentro de nuestras instalaciones.");

$final_moviles=utf8_decode("
D./Dª. $papa, con DNI número ".$row['dnitutor'].", padre/madre/tutor legal del alumno/ a ".$row['nombre']." ".$row['apellidos']." del curso $curso, teniendo en cuenta la información aportada, es conocedor de la prohibición de la tenencia y uso de los teléfonos móviles, así como de cualquier otro dispositivo electrónico que difunda o grabe imágenes de vídeo/audio.
");
$firma_moviles="
Firmado,


Padre/madre/tutor legal.";

// Formulario de la junta	
for($i=1;$i<3;$i++){
	$MiPDF->Addpage ();
	#### Cabecera con dirección
	$MiPDF->SetFont ( 'Times', 'B', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 8 );
	$titulo2 = utf8_decode("EDUCACIÓN SECUNDARIA OBLIGATORIA                                                           MATRICULA");
	$MiPDF->Multicell ( 0, 4, $titulo2, 0, 'L', 0 );

	$MiPDF->Ln ( 8 );
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Cell(21,6,utf8_decode("Nº MATRÍCULA: "),0);
	$MiPDF->Cell(24,6,"",1);
	$adv = utf8_decode("         ANTES DE FIRMAR ESTE IMPRESO, COMPRUEBE QUE CORRESPONDE A LA
	        ETAPA EDUCATIVA EN LA QUE DESEA REALIZAR LA MATRÍCULA.
	        ESTA MATRÍCULA ESTÁ CONDICIONADA A LA COMPROBACIÓN DE LOS DATOS,  DE CUYA
	        VERACIDAD SE RESPONSABILIZA LA PERSONA FIRMANTE. ");
	$MiPDF->MultiCell(120, 3, $adv,0,'L',0);
	$MiPDF->Ln ( 4 );
	$MiPDF->SetFont ( 'Times', '', 10 );
	$MiPDF->Cell(5,6,"1",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS PERSONALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(84,5,"APELLIDOS",0,0,"C");
	$MiPDF->Cell(84,5,"NOMBRE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,5,utf8_decode($row['apellidos']),1,0,'C');
	$MiPDF->Cell(84,5,utf8_decode($row['nombre']),1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(40,5,"FECHA NACIMIENTO",0,0,"C");
	$MiPDF->Cell(26,5,"DNI/NIE",0,0,"C");
	$MiPDF->Cell(26,5,utf8_decode("TELÉFONO"),0,0,"C");
	$MiPDF->Cell(35,5,"NACIONALIDAD",0,0,"C");
	$MiPDF->Cell(21,5,"HERMANOS",0,0,"C");
	$MiPDF->Cell(20,5,"SEXO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(40,5,utf8_decode($nacimiento),1,0,'C');
	$MiPDF->Cell(26,5,$row['dni'],1,0,'C');
	$MiPDF->Cell(26,5,$row['telefono1'],1,0,'C');
	$MiPDF->Cell(35,5,$nacionalidad,1,0,'C');
	$MiPDF->Cell(21,5,$hermanos,1,0,'C');
	$MiPDF->Cell(20,5,$sexo,1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(100,5,"DOMICILIO",0,0,"C");
	$MiPDF->Cell(25,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(15,5,"C.P.",0,0,"C");
	$MiPDF->Cell(28,5,"PROVINCIA",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(100,5,utf8_decode($row['domicilio']),1,0,'C');
	$MiPDF->Cell(25,5,utf8_decode($row['localidad']),1,0,'C');
	$MiPDF->Cell(15,5,utf8_decode($config['centro_codpostal']),1,0,'C');
	$MiPDF->Cell(28,5,$config['centro_provincia'],1,0,'C');
	$MiPDF->Ln ( 8 );
		
	$MiPDF->Cell(84,5,utf8_decode("CORREO ELECTRÓNICO DE CONTACTO"),0,0,"C");
	$MiPDF->Cell(84,5,"Transporte Escolar",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,5,$row['correo'],1,0,'C');
	$MiPDF->Cell(84,5,utf8_decode($ruta_est.$ruta_oeste),1,0,'C');
	
	$MiPDF->Ln ( 10 );
	$MiPDF->Cell(5,6,"2",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS DE LOS REPRESENTANTES LEGALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 1(con quien este convive)",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,utf8_decode($row['padre']),1,0,'C');
	$MiPDF->Cell(28,5,$row['dnitutor'],1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 2",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,utf8_decode($row['madre']),1,0,'C');
	$MiPDF->Cell(28,5,$row['dnitutor2'],1,0,'C');

	
	$MiPDF->Ln ( 10 );
	$MiPDF->Cell(5,6,"3",1,0,'C',1);
	$MiPDF->Cell(163,6,utf8_decode("DATOS DE MATRÍCULA"),1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(76,5,"CENTRO DOCENTE EN EL QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(46,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(46,5,"CODIGO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(76,5,$config['centro_denominacion'],1,0,'C');
	$MiPDF->Cell(46,5,$config['centro_localidad'],1,0,'C');
	$MiPDF->Cell(46,5,$config['centro_codigo'],1,0,'C');
	$MiPDF->Ln ( 6 );
	//echo $itinerario;
	$extra_it="";
	if($itinerario==1){$extra_it="(".$row['ciencias4'].")";}
	if(strlen($optativas4)>1){$extra_it.=" - $optativas4";}	
	if ($n_curso == '4') { $extra="4ESO (It. $itinerario".$extra_it.")";}elseif ($n_curso == '3') { $extra=utf8_decode("3ESO (Matemáticas $matematicas3)");}else{$extra=$curso;}
	
       $MiPDF->Cell(84,6,"IDIOMA EXTRANJERO",0,0,'C');
	$MiPDF->Cell(84,6,utf8_decode("RELIGIÓN O ALTERNATIVA"),0,0,'C');
	$MiPDF->Ln ( 6);
	$MiPDF->Cell(84,5,utf8_decode($idioma),1,0,'C');
	$MiPDF->Cell(84,5,utf8_decode($religion),1,0,'C');	
	$MiPDF->Ln ( 7);
	
	$MiPDF->Cell(60,5,"CURSO EN QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(108,5,"MATERIAS OPTATIVAS DEL CURSO EN QUE SE MATRICULA",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(60,5,$extra,1,0,'C');
	$MiPDF->MultiCell(108,5,utf8_decode($opt),1);
	$MiPDF->Ln ( 4 );
	$f_hoy = "        En ".$config['centro_localidad'].", a ".$hoy;
	$sello = "                                  Sello del Centro";
	$firma_centro = "                                El/La Funcionario/a";
	$firma_padre= "  Firma del representante o Guardador legal 1";
	$MiPDF->Cell(84,8,$firma_padre,0);	
	$MiPDF->Cell(84, 8, $firma_centro,0);
	$MiPDF->Ln ( 18);
	$MiPDF->Cell(84, 8, $f_hoy,0);
	$MiPDF->Cell(84, 8, $sello,0);
	$MiPDF->Ln ( 9 );
	$nota = utf8_decode("NOTA: Para la primera matriculación del alumnado en el centro docente se aportará documento acreditativo de la fecha de nacimimiento del alumno/a y documento de estar en posesión de los requisitos académicos establecidos en la legislación vigente.");
	$MiPDF->SetFont ( 'Times', 'B', 8 );
	$MiPDF->MultiCell(168,5,$nota,0);
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Ln ( 3 );		
	$MiPDF->MultiCell(168, 3, $datos_junta,1,'L',1);
}

	# insertamos la primera pagina del documento
	$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Image ( '../../img/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 12 );
	$MiPDF->Multicell ( 0, 4, $titulo_documentacion, 0, 'C', 0 );
	$MiPDF->Ln ( 4 );
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Multicell ( 0, 6, $documentacion, 0, 'L', 0 );
	$MiPDF->Ln ( 5 );
	$MiPDF->Multicell ( 0, 6, "------------------------------------------------------------------------------------------------------------------------------------------", 0, 'L', 0 );
	$MiPDF->Multicell ( 0, 6, "------------------------------------------------------------------------------------------------------------------------------------------", 0, 'L', 0 );
	$MiPDF->Ln ( 8 );
	$MiPDF->SetFont ( 'Times', 'B', 11  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Multicell ( 0, 4, $titulo_moviles, 0, 'C', 0 );
	$MiPDF->Ln ( 2 );
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->Multicell ( 0, 6, $texto_moviles, 0, 'L', 0 );
	$MiPDF->Ln ( 1 );
	$MiPDF->Multicell ( 0, 6, $final_moviles, 0, 'L', 0 );
	$MiPDF->Ln ( 3 );
	$MiPDF->Multicell ( 0, 6, $firma_moviles, 0, 'C', 0 );
	
	include("autorizaciones.php");
	}
   $MiPDF->AutoPrint(true);     
   $MiPDF->Output ();

?>