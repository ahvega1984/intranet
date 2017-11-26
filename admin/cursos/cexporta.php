<?php
require('../../bootstrap.php');

$mostrarTodas = (isset($_POST['mostrarTodas']) && $_POST['mostrarTodas'] == 1) ? 1 : 0;
$profesor = $_SESSION['profi'];

include("../../menu.php");
include("../informes/menu_alumno.php");

?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Alumnos y Grupos <small>Exportación de Datos</small></h2>
	</div>
	
	<br>
	<br>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<div class="col-sm-6">
			
			<div class="well">
				
				<form id="exportarDatos" action="exportar.php" method="post">

					<fieldset>
						<legend>Exportar datos</legend>
						
						<div class="form-group">
							<?php 
							if (acl_permiso($carg, array('1','7'))) {
								$result = mysqli_query($db_con, "SELECT DISTINCT nomunidad FROM unidades ORDER BY nomunidad ASC");
								$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo WHERE m.abrev LIKE 'AMB%' ORDER BY u.nomunidad ASC");
							}
							else {
								$result = mysqli_query($db_con, "SELECT DISTINCT grupo AS nomunidad FROM profesores WHERE profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY grupo ASC");
								$result_pmar = mysqli_query($db_con, "SELECT DISTINCT CONCAT(u.nomunidad, ' (PMAR)') AS nomunidad FROM unidades AS u JOIN materias AS m ON u.nomunidad = m.grupo JOIN profesores AS p ON u.nomunidad = p.grupo WHERE m.abrev LIKE 'AMB%' AND p.profesor='".mb_strtoupper($_SESSION['profi'], 'UTF-8')."' ORDER BY u.nomunidad ASC");											
							}

							$array_unidades = array();
							while ($row = mysqli_fetch_array($result)) {
								array_push($array_unidades, $row);
							}
							while ($row = mysqli_fetch_array($result_pmar)) {
								array_push($array_unidades, $row);
							}

							asort($array_unidades);
							?>
							<select class="form-control" name="unidad">
							<?php foreach ($array_unidades as $unidad): ?>
								<option value="<?php echo $unidad['nomunidad']; ?>" <?php echo (isset($curso) && $curso == $unidad['nomunidad']) ? 'selected' : ''; ?>><?php echo $unidad['nomunidad']; ?></option>
							<?php endforeach; ?>
							</select>
					  	</div>

						<div class="checkbox">
							<label>
								<input type="checkbox" name="datos" value="1"> Incluir datos personales de los alumnos
							</label>
						</div>

						<p class="help-block">Selecciona la unidad para exportar los datos en formato XLSX (Microsoft Excel 2007 o superior), ODS (OpenOffice o LibreOffice Calc) o CSV (importación para otros programas o para iDoceo).</p>

					  	<input type="hidden" id="exportar_formato" name="formato" value="">

						<br>

						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalLOPD">Información sobre LOPD</button>

						<!-- Modal -->
						<div class="modal fade" id="modalLOPD" tabindex="-1" role="dialog" aria-labelledby="modalLOPDlabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="modalLOPDlabel">Información sobre Ley Orgánica de Protección de Datos</h4>
								</div>
								<div class="modal-body">
									<p>La utilización de aplicaciones por los profesores en dispositivos personales (tableta, móvil, etc.) debe garantizar la 
									política de privacidad definida por el centro o Secretaría General Técnica de la Junta de Andalucía con las garantías 
									establecidas en la normativa de protección de datos:</p>
									
									<p>Tienes la obligación de proteger la información y los datos a los que tienes acceso. Esta protección debe prevenir 
									el empleo de operaciones que puedan producir una alteración indebida, inutilización o destrucción, robo o uso no autorizado.</p>

									<p>Tienes la obligación de notificar al equipo directivo o responsable informático del centro cualquier incidencia o 
									anomalía en el uso de los medios informáticos que detectes: pérdida de información, de listados, acceso no autorizado, uso de 
									su identificador de usuario o de su contraseña, introducción de virus, recuperación de datos, desaparición de soportes 
									informáticos y, en general, toda situación que pueda comprometer el buen uso y funcionamiento de los sistemas de información.</p>

									<p>Solo podrás crear ficheros temporales que contengan datos de carácter personal, cuando sean necesarios para el desempeño de 
									tus funciones, en todo caso, deberán ser eliminados cuando hayan dejado de ser útiles para la finalidad para la que fueron creados.</p>

									<p>Finalizada la relación funcionarial o laboral, con la Administración de la Junta de Andalucía o traslado de puesto de trabajo, 
									deberás dejar sin perjudicar todas las aplicaciones informáticas, ficheros, información, datos y documentos electrónicos que hayas 
									utilizado en tu actividad profesional.</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
								</div>
								</div>
							</div>
						</div>

						<div class="btn-group">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Exportar <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="#" onclick="$('#exportar_formato').val('xlsx'); $('#exportarDatos').submit()">Formato XLSX</a></li>
								<li><a href="#" onclick="$('#exportar_formato').val('ods'); $('#exportarDatos').submit()">Formato ODS</a></li>
								<li><a href="#" onclick="$('#exportar_formato').val('csv'); $('#exportarDatos').submit()">Formato CSV</a></li>
								<li><a href="#" onclick="$('#exportar_formato').val('idoceo'); $('#exportarDatos').submit()">Formato CSV (iDoceo)</a></li>
							</ul>
						</div>

				  </fieldset>
				  
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
	
	</div><!-- /.row -->
	
	<br>
	
</div><!-- /.container -->

<?php include("../../pie.php"); ?>
</body>
</html>
