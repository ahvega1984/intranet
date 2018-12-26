<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

require_once(INTRANET_DIRECTORY.'/lib/phpodt/phpodt.php');

$odt = ODT::getInstance();

// ESTILOS
$estiloEncabezado = new TextStyle('Encabezado 1');
$estiloEncabezado->setFontName('Arial');
$estiloEncabezado->setColor('#000000');
$estiloEncabezado->setBold();
$estiloEncabezado->setFontSize(14);

$estiloPredeterminado = new TextStyle('Cuerpo de texto');
$estiloPredeterminado->setFontName('Arial');
$estiloPredeterminado->setColor('#000000');
$estiloPredeterminado->setFontSize(12);

$estiloNegrita = new TextStyle('Negrita');
$estiloNegrita->setFontName('Arial');
$estiloNegrita->setColor('#000000');
$estiloNegrita->setBold();
$estiloNegrita->setFontSize(12);

// ESTILOS DE PÁRRAFO
$parrafoEstiloEncabezado = new ParagraphStyle('Encabezado 1');
$parrafoEstiloEncabezado->setTextAlign(StyleConstants::CENTER);

$parrafoEstiloPredeterminado= new ParagraphStyle('Cuerpo de texto');
$parrafoEstiloPredeterminado->setTextAlign(StyleConstants::JUSTIFY);

$parrafoEstiloPredeterminadoCentrado= new ParagraphStyle('Cuerpo de texto (centrado)');
$parrafoEstiloPredeterminadoCentrado->setTextAlign(StyleConstants::CENTER);


// TITULO DEL DOCUMENTO
$parrafoEncabezado = new Paragraph($parrafoEstiloEncabezado);
$parrafoEncabezado->addText("Autorización para la publicación de imágenes de los alumnos", $estiloEncabezado);

// CUERPO DEL DOCUMENTO
$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("En cumplimiento de lo dispuesto en el Reglamento General de Protección de Datos, se le informa que las fotografías, videos y demás contenido audiovisual en las cuales aparezca su imagen individualmente o en grupo realizadas durante las actividades culturales, recreativas, deportivas y sociales en las que participa el centro educativo en sus instalaciones y/o fuera de las mismas serán incorporados para su tratamiento al fichero 'Contenido audiovisual de las actividades de los centros y servicios educativos' con la finalidad de difundir y promocionar las citadas actividades.", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("El interesado autoriza a la Dirección del " . $config['centro_denominacion'] . " a ceder a partir de este momento sus datos personales en las publicaciones del propio centro, para su utilización en las finalidades arriba expuestas. El responsable del tratamiento es la Dirección del centro " . $config['centro_denominacion'] . ".", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("Si lo desea, podrá ejercitar los derechos de acceso, rectificación, cancelación y oposición de sus datos en el centro " . $config['centro_denominacion'] . ", " . $config['centro_direccion'] . ", " . $config['centro_codpostal'] . ", " . $config['centro_localidad'] . ", " . $config['centro_provincia'] . ".", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("En consecuencia, la Dirección del centro " . $config['centro_denominacion'] . " solicita su consentimiento:", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminadoCentrado);
$parrafo->addText("[&nbsp;&nbsp;&nbsp;] ", $estiloPredeterminado);
$parrafo->addText("Doy mi CONSENTIMIENTO", $estiloPredeterminado);
$parrafo->addText("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $estiloPredeterminado);
$parrafo->addText("[&nbsp;&nbsp;&nbsp;] ", $estiloPredeterminado);
$parrafo->addText("NO", $estiloNegrita);
$parrafo->addText(" doy mi CONSENTIMIENTO", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminadoCentrado);
$parrafo->addText("(marque con una cruz lo que proceda)", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("En caso de ser un alumno menor de catorce años, el padre, madre o tutor/a legal debe acreditar el consentimiento informando los datos que a continuación se indican:", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("Don/Doña ................................................................................... con DNI .............................
como padre, madre o tutor/a legal de .................................................................................... con domicilio en .....................................................................................................................", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("Firma del padre, madre o tutor/a legal", $estiloPredeterminado);

$parrafo = new Paragraph($parrafoEstiloPredeterminado);
$parrafo->addText("", $estiloPredeterminado);

$odt->output('../varios/internos/Proteccion de Datos/Contenido audiovisual de las actividades de los centros y servicios publicos/Autorizacion para la publicacion de imagenes de los alumnos.odt');
