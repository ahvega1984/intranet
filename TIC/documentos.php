<?php
session_start();
include("../config.php");
include_once('../config/version.php');
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


$recursos = array(
	array(
		'seccion' => 'Punto de partida sobre las TIC',
		'recursos' => array(
			array(
				'titulo' => 'Las TIC como Agentes de innovación',
				'descripcion' => 'Documento esencial para entender lo que la Consejería de Educación pretende con la idea de los Centros TIC, y como afecta el asunto a Padres, Alumnos y Profesores.',
				'enlace' => 'docs/TIC_como_agentes_innovacion.pdf',
			),
			array(
				'titulo' => 'Guia de los Centros TIC',
				'descripcion' => 'El CGA (Centro de Gesti�n Avanzado, creo) ha elaborado una gu�a muy completa sobre todos los aspectos t�cnicos que rodean a un Centro TIC: ordenadores y perif�ricos, Sistema Operativo (Guadalinex), Plataforma Educativa, etc. Incluye una secci�n estupenda sobre el uso de un escaner, impresoras, c�maras de fotos. Hay otra secci�n sobre el Ca��n Virtual, Jclic, y otras aplicaciones.',
				'enlace' => 'docs/guia_centros_tic.pdf',
			),
		),
	),
	
	array(
		'seccion' => 'Hardware',
		'recursos' => array(
			array(
				'titulo' => 'Manual del Portatil TOSHIBA',
				'descripcion' => 'Para los que quieran conocer en detalle las caracter�sticas y uso del portatil que utilizamos en el Centro, aqu� van el PDF que contiene el Manual de Uso.',
				'enlace' => 'docs/MAN-toshiba-es.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Guadalinex',
		'recursos' => array(
			array(
				'titulo' => 'Guia de Guadalinex V.3.1',
				'descripcion' => 'Guia completita del Sistema Operativo que utilizamos. Hay una versi�n en formato libro por si alguien quiere echarle un vistazo. Imprescindible tanto para los recien llegados como para usuarios mas avanzados. Adem�s del Sistema Operativo, trata aplicaciones de uso corriente.',
				'enlace' => 'docs/Guia_Guadalinex_V3.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Aplicaciones importantes en Guadalinex',
		'recursos' => array(
			array(
				'titulo' => 'Procesador de textos de OpenOffice',
				'descripcion' => 'Introducion a la aplicaci�n de textos de OpenOffice.',
				'enlace' => 'docs/openoffice_writer.pdf',
			),
			array(
				'titulo' => 'Gimp',
				'descripcion' => 'Gimp es una aplicaci�n para el tratamiento de graficos y fotografias. Algo as� como el PhotoShop de Linux. La introducci�n es b�sica pero util para empezar.',
				'enlace' => '#',
			),
			array(
				'titulo' => 'Composer (Creaci�n de P�ginas Web)',
				'descripcion' => 'Introduci�n r�pida al uso de esta aplicaci�n para crear p�ginas web sencillas en modo gr�fico. Hay otra utilidad mas potente con la misma funcion en Guadalinex, NVU, en caso de pedir mas potencia.',
				'enlace' => 'docs/paginas_web_con_composer.pdf',
			),
			array(
				'titulo' => 'XSane',
				'descripcion' => 'Introducci�n a la aplicaci�n que utiliza Guadalinex para el uso de un escaner. Imprescindible para los que quieran utilizar regularmente la m�quina.',
				'enlace' => 'docs/xsane_manual_escanear.pdf',
			),
			array(
				'titulo' => 'Ca�on Virtual',
				'descripcion' => 'Instrucciones para el uso de la aplicaci�n Ca�on Virtual que esta disponible en Guadalinex. La aplicaci�n permite al profesor que el alumno vea en su ordenador lo que el profesor tiene en la suya, por ejemplo peliculas o el propio escritorio. Muy util para el uso en las aulas.',
				'enlace' => 'docs/CanonVirtual.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Plataforma educativa',
		'recursos' => array(
			array(
				'titulo' => 'Manual de la Plataforma Educativa',
				'descripcion' => 'La Plataforma Educativa es la otra cosa que viene con los Centros TIC: una aplicaci�n que permite trabajar con los alumnos en el aula (colocar documentos, poner controles, crear foros de discusion, etc). El uso necesita de cierto aprendizaje, asi que aqu� van un par de manuales de uso para quien quiera entrar en ese mundo. Aunque su uso no es obligatorio, algunos profesores pueden encontrar muchas posibilidades para utilizar regularmente el ordenador en el aula con los alumnos.',
				'enlace' => 'docs/Manual_Plataforma_Educativa.pdf',
			),
			array(
				'titulo' => 'Plataforma E-Ducativa',
				'descripcion' => 'Otro manual de uso de la Plataforma.',
				'enlace' => 'docs/plataforma_e-ducativa.pdf',
			),
			array(
				'titulo' => 'Contenidos en la Plataforma',
				'descripcion' => 'Instrucciones para crear contenidos para los alumnos, quizas la parte fundamental de la Plataforma.',
				'enlace' => 'docs/plataformaIVcontenidos.doc',
			),
		),
	),
	
	
	array(
		'seccion' => 'Enlaces interesantes',
		'recursos' => array(
			array(
				'titulo' => 'Centro de Gesti�n Avanzado de centros TIC',
				'descripcion' => 'Pagina principal del organismo que lleva el peso de los Centros TIC. Hay documentaci�n importante.',
				'enlace' => 'http://www.juntadeandalucia.es/educacion/cga/portal/',
			),
		),
	),
);

include("../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Documentos y manuales de uso</small></h2>
		</div>
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">
				
				<?php foreach ($recursos as $recurso): ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th colspan="2"><?php echo $recurso['seccion']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($recurso['recursos'] as $manuales): ?>
						<tr>
							<td>
								<h5 class="text-info"><strong><?php echo $manuales['titulo']; ?></strong></h5>
								<?php echo $manuales['descripcion']; ?>
							</td>
							<td class="col-xs-1 text-center"><a href="<?php echo $manuales['enlace']; ?>" target="_blank"><span class="fa fa-cloud-download fa-fw fa-3x" data-bs="tooltip" title="Descargar"></span></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php endforeach; ?>
				
			</div><!-- /.col-sm-12 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../pie.php"); ?>

</body>
</html>
