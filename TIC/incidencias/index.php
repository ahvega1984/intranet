<?php
require('../../bootstrap.php');
require('../inc_variables.php');

mysqli_query("CREATE TABLE IF NOT EXISTS `incidencias_tic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `profesor` varchar(12) NOT NULL,
  `dependencia` varchar(30) DEFAULT NULL,
  `problema` smallint(3) unsigned NOT NULL,
  `descripcion` text,
  `estado` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `numincidencia` char(10) DEFAULT NULL,
  `observaciones` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");

// Migración de datos
/*
$result = mysqli_query($db_con, "SELECT `fecha`, `profesor`, `descripcion`, `estado`, `nincidencia` FROM `partestic` ORDER BY `parte` ASC") or die (mysqli_error($db_con));
while($row = mysqli_fetch_array($result)) {
    $result_profesor = mysqli_query($db_con, "SELECT `idea` FROM `departamentos` WHERE `nombre` = '".$row['profesor']."'");
    $row_profesor = mysqli_fetch_array($result_profesor);

    if ($row['estado'] != 'solucionado') $migracion_estado = 1;
    else $migracion_estado = 3;

    mysqli_query($db_con, "INSERT INTO `incidencias_tic` (`fecha`, `profesor`, `problema`, `descripcion`, `estado`, `numincidencia`) VALUES ('".$row['fecha']."', '".$row_profesor['idea']."', 901, '".$row['descripcion']."', $migracion_estado, '".$row['nincidencia']."')") or die (mysqli_error($db_con));
}
*/

if (file_exists('../config.php')) {
	include('../config.php');
}

// Procesando formulario
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$tipoproblema = isset($_POST['tipoproblema']) ? $_POST['tipoproblema'] : '';
$dependencia = isset($_POST['dependencia']) ? $_POST['dependencia'] : '';
$profesor = isset($_POST['profesor']) ? $_POST['profesor'] : $ide;
$asunto = isset($_POST['asunto']) ? $_POST['asunto'] : '';
$estado = isset($_POST['estado']) ? $_POST['estado'] : '';
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
$cga_nincidencia = isset($_POST['cga_nincidencia']) ? $_POST['cga_nincidencia'] : '';

include("../../menu.php");
include("../menu.php");
?>

    <div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Nueva incidencia</small></h2>
		</div>

        <div class="row">

            <div class="col-sm-12">

                <div class="well">

                    <form action="" method="POST">

                        <fieldset>
                            <legend>Nueva incidencia</legend>

                            <div class="row">
                            
                                <div class="col-sm-3">
                                    <div class="form-group" id="datetimepicker1">
                                        <label for="fecha">Fecha <span class="text-danger">(*)</span></label>
                                        <div class="input-group">
						  			 	    <input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo (isset($fecha) && !empty($fecha)) ? $fecha : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY">
						  			        <span class="input-group-addon">
						  			  	        <span class="fa fa-calendar fa-fw"></span>
						  			        </span>
						  			    </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="solicitante">Solicitante <span class="text-danger">(*)</span></label>
                                        <?php if ($pr == 'Administrador' || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
                                        <select class="form-control" id="solicitante" name="solicitante">
                                            <?php $result = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos ORDER BY nombre ASC"); ?>
                                            <?php while ($row = mysqli_fetch_array($result)): ?>
                                            <option value="<?php echo $row['idea']; ?>"<?php echo ($solicitante == $row['idea']) ? ' selected' : ''; ?>><?php echo $row['nombre']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                        <?php else: ?>
                                        <input type="hidden" class="form-control" id="solicitante" name="solicitante" value="<?php echo $pr; ?>">
                                        <input type="text" class="form-control" id="solicitante_nombre" name="solicitante_nombre" value="<?php echo $pr; ?>" readonly>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="dependencia">Aula o dependencia <span class="text-danger">(*)</span></label>
                                        <?php $result = mysqli_query($db_con, "SELECT nomdependencia FROM dependencias ORDER BY nomdependencia ASC"); ?>
                                        <?php if (mysqli_num_rows($result)): ?>
                                        <select class="form-control" id="dependencia" name="dependencia"<?php echo ($pr == 'Administrador' || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])) ? ' onchange="submit()"' : ''; ?>>
                                            <option value=""></option>
                                            <?php while ($row = mysqli_fetch_array($result)): ?>
                                            <option value="<?php echo $row['nomdependencia']; ?>"<?php echo (isset($dependencia) && $dependencia == $row['nomdependencia']) ? ' selected' : ''; ?>><?php echo $row['nomdependencia']; ?></value>
                                            <?php endwhile; ?>
                                        </select>
                                        <?php else: ?>
                                        <input type="text" class="form-control" id="dependencia" name="dependencia" placeholder="P. ej.: Aula de 1ºA, Aula 101, Sala de profesores" value="<?php echo (isset($dependencia) && !empty($dependencia)) ? $dependencia : ''; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div><!-- /.row -->

                            <div class="form-group">
                                <label for="tipoproblema">Tipo de problema <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="tipoproblema" name="tipoproblema" onchange="submit()">
                                    <option value=""></option>
                                    <?php foreach ($tipos_incidencia as $grupo_incidencias => $array_incidencias): ?>
                                    <option value="<?php echo $grupo_incidencias; ?>"<?php echo (isset($tipoproblema) && $tipoproblema == $grupo_incidencias) ? ' selected' : ''; ?>><?php echo $grupo_incidencias; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                             <div class="form-group">
                                <label for="asunto">Asunto <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="asunto" name="asunto" onchange="submit()" <?php echo (!isset($tipoproblema) || empty($tipoproblema)) ? 'disabled' : ''; ?>>
                                    <?php foreach ($tipos_incidencia[$tipoproblema] as $incidencia_id => $incidencia_descripcion): ?>
                                    <option value="<?php echo $incidencia_id; ?>"<?php echo (isset($asunto) && $asunto == $incidencia_id) ? ' selected' : ''; ?>><?php echo $incidencia_descripcion; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                    
                            <div class="form-group">
                                <label for="descripcion">Descripción de la incidencia <?php if (isset($asunto) && substr($asunto, -2, 2) == '00'): ?><span class="text-danger">(*)</span><?php endif; ?></label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="5" placeholder="Describa brevemente el problema para ayudar a su resolución. Si hay varios ordenadores en el aula, recuerda indicar qué ordenador presenta el problema."><?php echo (isset($descripcion) && !empty($descripcion)) ? $descripcion : ''; ?></textarea>
                            </div>

                            <p class="text-danger"><small>Si el problema ha sido causado por el mal uso que le ha dado un alumno/a, registra el incidente en el modulo de Problemas de Convivencia.</small></p>

                        </fieldset>

                        <?php if ($pr == 'Administrador' || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])): ?>
                        <hr>

                        <fieldset>
                            
                            <div class="row">
                            
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="estado">Estado de la incidencia</label>
                                        <select class="form-control" id="estado" name="estado" onchange="submit()">
                                            <?php foreach ($estados_incidencia as $estado_id => $estado_descripcion): ?>
                                            <option value="<?php echo $estado_id; ?>"><?php echo $estado_descripcion; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="cga_nincidencia">Nº Incidencia (CAUCE-CGA) <span class="fa fa-question-circle fa-lg fa-fw" data-bs="tooltip" data-html="yes" title="Teléfonos: <br>300 300 | 955 06 10 71"></span></label>
                                        <input type="text" class="form-control" id="cga_nincidencia" name="cga_nincidencia" value="">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Inventario del aula</label>
                                        <p class=""><a href="//<?php echo $config['dominio']; ?>/intranet/TIC/inventario/?localizacion=<?php echo $dependencia; ?>" class="btn btn-default btn-block" target="_blank">Consultar inventario TIC del aula</a></p>
                                    </div>
                                </div>
                                
                            </div><!-- /.row -->

                             <div class="form-group">
                                <label for="resolucion">Resolución de la incidencia</label>
                                <textarea class="form-control" id="resolucion" name="resolucion" rows="5" placeholder=""><?php echo (isset($resolucion) && !empty($resolucion)) ? $resolucion : ''; ?></textarea>
                            </div>

                        </fieldset>
                        <?php endif; ?>


                        <button type="submit" class="btn btn-primary" name="registrar">Registrar incidencia</button>
                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include("../../pie.php"); ?>

    <script>  
	$(function() {
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		});
	});  
    </script>

</body>
</html>