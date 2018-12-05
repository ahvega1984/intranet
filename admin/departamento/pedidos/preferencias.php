<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'UTF-8'));
}

if (isset($_POST['btnGuardar'])) {

	$prefLimitePedidos		= limpiar_string($_POST['prefLimitePedidos']);
	$prefLimiteArticulos	= limpiar_string($_POST['prefLimiteArticulos']);
	$prefFechaLimite		= limpiar_string($_POST['prefFechaLimite']);
	$prefCondiciones		= limpiar_string($_POST['prefCondiciones']);

	// CREACIÓN DEL ARCHIVO DE CONFIGURACIÓN
	if($file = fopen('config.php', 'w+'))
	{
		fwrite($file, "<?php \r\n");

		fwrite($file, "\r\n// CONFIGURACIÓN MÓDULO DE PEDIDOS\r\n");
		fwrite($file, "\$config['pedidos']['limite_pedidos']\t= $prefLimitePedidos;\r\n");
		fwrite($file, "\$config['pedidos']['limite_articulos']\t= $prefLimiteArticulos;\r\n");
		fwrite($file, "\$config['pedidos']['condiciones']\t\t= '$prefCondiciones';\r\n");

		fwrite($file, "\r\n\r\n// Fin del archivo de configuración");

		fclose($file);

		$msg_success = "Las preferencias han sido guardadas correctamente.";
	}

}

if (file_exists('config.php')) {
	include('config.php');
}


include("../../../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
		<h2>Pedido de material <small>Preferencias</small></h2>
	</div>

	<!-- MENSAJES -->
	<?php if (isset($msg_error)): ?>
	<div class="alert alert-danger alert-fadeout">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>

	<?php if (isset($msg_success)): ?>
	<div class="alert alert-success alert-fadeout">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>


	<div class="row">

		<div class="col-sm-12">

			<form class="form-horizontal" method="post" action="preferencias.php">

				<div class="well">

					<fieldset>
						<legend>Preferencias</legend>

						<div class="form-group">
							<label for="prefLimitePedidos" class="col-sm-4 control-label">Límite de pedidos por departamento</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="prefLimitePedidos" name="prefLimitePedidos" value="<?php echo (isset($config['pedidos']['limite_pedidos'])) ? $config['pedidos']['limite_pedidos'] : 0; ?>">
							</div>
							<p class="col-sm-5 form-control-static help-block">(0 = Sin límite)</p>
						</div>

						<div class="form-group">
							<label for="prefLimiteArticulos" class="col-sm-4 control-label">Límite de artículos por pedido</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="prefLimiteArticulos" name="prefLimiteArticulos" value="<?php echo (isset($config['pedidos']['limite_articulos'])) ? $config['pedidos']['limite_articulos'] : 15; ?>">
							</div>
							<p class="col-sm-5 form-control-static help-block">(0 = Sin límite)</p>
						</div>

						<div class="form-group">
							<label for="prefCondiciones" class="col-sm-4 control-label">Condiciones de compra</label>
							<div class="col-sm-8">
								<textarea class="form-control" id="prefCondiciones" name="prefCondiciones" rows="10"><?php echo (isset($config['pedidos']['condiciones'])) ? $config['pedidos']['condiciones'] : '<p>El presupuesto del centro determinará la asignación de partidas específicas a cada Departamento en función del número de miembros del Departamento y de necesidades fundamentadas y para ello, cada Departamento entregará en Secretaría un proyecto escrito de presupuesto de funcionamiento anual.</p>

								<p>En él deberán detallarse las partidas que componen el gasto desglosado por conceptos (material general, fungible, bibliografía, actividades, repografía, etc.) y valorarlas con la mayor exactitud posible (I.V.A. incluido).</p>

								<p>Para la adquisición de material se sigue el siguiente protocolo:</p>

								<ol>
									<li>El primer paso es recoger en acta el acuerdo del Departamento</li>
									<li>Todo gasto deberá contar previamente con la conformidad del Secretario/a y del Director/a mediante petición por escrito</li>
									<li>Se solicitará en el momento de la compra una factura con todos los requisitos legales oportunos:
										<ul>
											<li>Factura a nombre del '.$config['centro_denominacion'].'</li>
											<li>NIF de la Consejería de Educación de la Junta de Andalucía: S4111001F</li>
											<li>CIF o NIF del proveedor</li>
											<li>Nombre del proveedor</li>
											<li>Fecha y número de la factura</li>
											<li>Firma y sello de la empresa proveedora</li>
										</ul>
									</li>
									<li>El original del albarán o factura se entregará al Secretario/a, quedando una copia en poder del Jefe de Departamento correspondiente</li>
									<li>El Secretario/a sólo entregará a los Jefes de Departamento el valor nominal de la compra efectuada</li>
								</ol>'; ?></textarea>
							</div>
						</div>

					</fieldset>

				</div>

				<button type="submit" class="btn btn-primary" name="btnGuardar">Guardar cambios</button>
				<?php if (isset($_GET['esAdmin']) && $_GET['esAdmin'] == 1): ?>
				<a href="../../../xml/index.php" class="btn btn-default">Volver</a>
				<?php else: ?>
				<a href="index.php" class="btn btn-default">Volver</a>
				<?php endif; ?>

			</form>

		</table>

		</div>

	</div>

</div>

<?php include("../../../pie.php"); ?>

<script>
$(document).ready(function() {
	$('#prefCondiciones').summernote({
		height: 260,
		lang: 'es-ES',
		toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['media', ['link', 'picture', 'video']],
			['code', ['codeview']]
		]
	});
});
</script>

</body>
</html>
