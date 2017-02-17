<?
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
				'descripcion' => 'El CGA (Centro de Gestión Avanzado, creo) ha elaborado una guía muy completa sobre todos los aspectos técnicos que rodean a un Centro TIC: ordenadores y periféricos, Sistema Operativo (Guadalinex), Plataforma Educativa, etc. Incluye una sección estupenda sobre el uso de un escaner, impresoras, cámaras de fotos. Hay otra sección sobre el Cañón Virtual, Jclic, y otras aplicaciones.',
				'enlace' => 'docs/guia_centros_tic.pdf',
			),
		),
	),
	
	array(
		'seccion' => 'Hardware',
		'recursos' => array(
			array(
				'titulo' => 'Manual del Portatil TOSHIBA',
				'descripcion' => 'Para los que quieran conocer en detalle las características y uso del portatil que utilizamos en el Centro, aquí van el PDF que contiene el Manual de Uso.',
				'enlace' => 'docs/MAN-toshiba-es.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Guadalinex',
		'recursos' => array(
			array(
				'titulo' => 'Guia de Guadalinex V.3.1',
				'descripcion' => 'Guia completita del Sistema Operativo que utilizamos. Hay una versión en formato libro por si alguien quiere echarle un vistazo. Imprescindible tanto para los recien llegados como para usuarios mas avanzados. Además del Sistema Operativo, trata aplicaciones de uso corriente.',
				'enlace' => 'docs/Guia_Guadalinex_V3.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Aplicaciones importantes en Guadalinex',
		'recursos' => array(
			array(
				'titulo' => 'Procesador de textos de OpenOffice',
				'descripcion' => 'Introducion a la aplicación de textos de OpenOffice.',
				'enlace' => 'docs/openoffice_writer.pdf',
			),
			array(
				'titulo' => 'Gimp',
				'descripcion' => 'Gimp es una aplicación para el tratamiento de graficos y fotografias. Algo así como el PhotoShop de Linux. La introducción es básica pero util para empezar.',
				'enlace' => '#',
			),
			array(
				'titulo' => 'Composer (Creación de Páginas Web)',
				'descripcion' => 'Introdución rápida al uso de esta aplicación para crear páginas web sencillas en modo gráfico. Hay otra utilidad mas potente con la misma funcion en Guadalinex, NVU, en caso de pedir mas potencia.',
				'enlace' => 'docs/paginas_web_con_composer.pdf',
			),
			array(
				'titulo' => 'XSane',
				'descripcion' => 'Introducción a la aplicación que utiliza Guadalinex para el uso de un escaner. Imprescindible para los que quieran utilizar regularmente la máquina.',
				'enlace' => 'docs/xsane_manual_escanear.pdf',
			),
			array(
				'titulo' => 'Cañon Virtual',
				'descripcion' => 'Instrucciones para el uso de la aplicación Cañon Virtual que esta disponible en Guadalinex. La aplicación permite al profesor que el alumno vea en su ordenador lo que el profesor tiene en la suya, por ejemplo peliculas o el propio escritorio. Muy util para el uso en las aulas.',
				'enlace' => 'docs/CanonVirtual.pdf',
			),
		),
	),
	
	
	array(
		'seccion' => 'Plataforma educativa',
		'recursos' => array(
			array(
				'titulo' => 'Manual de la Plataforma Educativa',
				'descripcion' => 'La Plataforma Educativa es la otra cosa que viene con los Centros TIC: una aplicación que permite trabajar con los alumnos en el aula (colocar documentos, poner controles, crear foros de discusion, etc). El uso necesita de cierto aprendizaje, asi que aquí van un par de manuales de uso para quien quiera entrar en ese mundo. Aunque su uso no es obligatorio, algunos profesores pueden encontrar muchas posibilidades para utilizar regularmente el ordenador en el aula con los alumnos.',
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
				'titulo' => 'Centro de Gestión Avanzado de centros TIC',
				'descripcion' => 'Pagina principal del organismo que lleva el peso de los Centros TIC. Hay documentación importante.',
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
