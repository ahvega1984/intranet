<?php
require('bootstrap.php');
require_once(INTRANET_DIRECTORY.'/lib/google-authenticator/GoogleAuthenticator.php');
require_once(INTRANET_DIRECTORY.'/lib/phpmailer/class.phpmailer.php');

$ga = new PHPGangsta_GoogleAuthenticator();

$totp_activo = 0;

$result = mysqli_query($db_con, "SELECT `totp_secret`, `correo`, `telefono` FROM `c_profes` WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1");
$row = mysqli_fetch_array($result);
if ($row['totp_secret'] != NULL) {
	$secret = $row['totp_secret'];
	$qrCodeUrl = $ga->getQRCodeGoogleUrl($_SESSION['ide'], $secret, 'Intranet');
	$totp_activo = 1;
}

if (isset($_POST['totp-estado'])) {
	if (! $totp_activo) {
		$secret = $ga->createSecret();
		$qrCodeUrl = $ga->getQRCodeGoogleUrl($_SESSION['ide'], $secret, 'Intranet');
		$result_estado = mysqli_query($db_con, "UPDATE `c_profes` SET `totp_secret` = '".$secret."' WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1");
		if (! $result_estado) {
			$msg_error = 'No se ha podido activar la autenticación en dos pasos';
		}
		else {
			$totp_activo = 1;
			$_SESSION['totp_configuracion'] = 1;

			if (isset($config['mod_sms']) && $config['mod_sms'] && $row['telefono'] != '') {
				$oneCode = $ga->getCode($secret);

				include_once(INTRANET_DIRECTORY.'/lib/trendoo/sendsms.php');
				$sms = new Trendoo_SMS();
				$sms->sms_type = SMSTYPE_GOLD_PLUS;
				$sms->add_recipient('+34'.$row['telefono']);
				$sms->message = 'Tu código de verificación es '.$oneCode.'.';
				$sms->sender = $config['mod_sms_id'];
				$sms->set_immediate();
				if ($sms->validate()) $sms->send();
			}
		}
	}
	else {
		$result_estado = mysqli_query($db_con, "UPDATE `c_profes` SET `totp_secret` = NULL WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1");
		if (! $result_estado) {
			$msg_error = 'No se ha podido desactivar la autenticación en dos pasos';
		}
		else {
			$totp_activo = 0;
			$_SESSION['totp_configuracion'] = 0;

			$mail = new PHPMailer();
			$mail->Host = "localhost";
			$mail->From = 'no-reply@'.$config['dominio'];
			$mail->FromName = utf8_decode($config['centro_denominacion']);
			$mail->Sender = 'no-reply@'.$config['dominio'];
			$mail->IsHTML(true);
			
			$message = file_get_contents(INTRANET_DIRECTORY.'/lib/mail_template/index.htm');
			$message = str_replace('{{dominio}}', $config['dominio'], $message);
			$message = str_replace('{{centro_denominacion}}', $config['centro_denominacion'], $message);
			$message = str_replace('{{centro_codigo}}', $config['centro_codigo'], $message);
			$message = str_replace('{{centro_direccion}}', $config['centro_direccion'], $message);
			$message = str_replace('{{centro_codpostal}}', $config['centro_codpostal'], $message);
			$message = str_replace('{{centro_localidad}}', $config['centro_localidad'], $message);
			$message = str_replace('{{centro_provincia}}', $config['centro_provincia'], $message);
			$message = str_replace('{{centro_telefono}}', $config['centro_telefono'], $message);
			$message = str_replace('{{centro_fax}}', $config['centro_fax'], $message);
			$message = str_replace('{{centro_email}}', $config['centro_email'], $message);
			$message = str_replace('{{titulo}}', 'Autenticación en dos pasos desactivado', $message);
			$message = str_replace('{{contenido}}', '<p>Se ha desactivado la autenticación en dos pasos con tu usuario IdEA '.$_SESSION['ide'].' para iniciar sesión en la Intranet.</p><p>A partir de este momento, únicamente necesitarás la contraseña para iniciar sesión.</p><p>Atentamente:<br>La Dirección del Centro</p>', $message);
			
			$mail->msgHTML(utf8_decode($message));
			$mail->Subject = utf8_decode('Autenticación en dos pasos desactivado');
			$mail->AltBody = utf8_decode("Se ha desactivado la autenticación en dos pasos con tu usuario IdEA ".$_SESSION['ide']." para iniciar sesión en la Intranet. \n\nA partir de este momento, únicamente necesitarás la contraseña para iniciar sesión. \n\nAtentamente: \nLa Dirección del Centro</p>");

			$mail->AddAddress($row['correo'], $_SESSION['profi']);
			$mail->Send();
		}
	}
}

if (isset($_POST['totp_verificado'])) {
	$_SESSION['totp_configuracion'] = 0;
	$msg_success = 'La autenticación en dos pasos se ha activado correctamente';

	if ($row['correo'] != '') {
		$mail = new PHPMailer();
		$mail->Host = "localhost";
		$mail->From = 'no-reply@'.$config['dominio'];
		$mail->FromName = utf8_decode($config['centro_denominacion']);
		$mail->Sender = 'no-reply@'.$config['dominio'];
		$mail->IsHTML(true);
		
		$message = file_get_contents(INTRANET_DIRECTORY.'/lib/mail_template/index.htm');
		$message = str_replace('{{dominio}}', $config['dominio'], $message);
		$message = str_replace('{{centro_denominacion}}', $config['centro_denominacion'], $message);
		$message = str_replace('{{centro_codigo}}', $config['centro_codigo'], $message);
		$message = str_replace('{{centro_direccion}}', $config['centro_direccion'], $message);
		$message = str_replace('{{centro_codpostal}}', $config['centro_codpostal'], $message);
		$message = str_replace('{{centro_localidad}}', $config['centro_localidad'], $message);
		$message = str_replace('{{centro_provincia}}', $config['centro_provincia'], $message);
		$message = str_replace('{{centro_telefono}}', $config['centro_telefono'], $message);
		$message = str_replace('{{centro_fax}}', $config['centro_fax'], $message);
		$message = str_replace('{{centro_email}}', $config['centro_email'], $message);
		$message = str_replace('{{titulo}}', 'Autenticación en dos pasos activado', $message);
		$message = str_replace('{{contenido}}', '<p>Se ha activado la autenticación en dos pasos con tu usuario IdEA '.$_SESSION['ide'].' para iniciar sesión en la Intranet.</p><p>En el caso de que olvides la contraseña o tengas problemas con el código temporal, ponte en contacto con un miembro del Equipo Directivo para restablecer el acceso a tu cuenta.</p><p>Atentamente:<br>La Dirección del Centro</p>', $message);
		
		$mail->msgHTML(utf8_decode($message));
		$mail->Subject = utf8_decode('Autenticación en dos pasos activado');
		$mail->AltBody = utf8_decode("Se ha activado la autenticación en dos pasos con tu usuario IdEA ".$_SESSION['ide']." para iniciar sesión en la Intranet. \n\nEn el caso de que olvides la contraseña o tengas problemas con el código temporal, ponte en contacto con un miembro del Equipo Directivo para restablecer el acceso a tu cuenta. \n\nAtentamente: \nLa Dirección del Centro</p>");

		$mail->AddAddress($row['correo'], $_SESSION['profi']);
		$mail->Send();
	}
	
}

include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Autenticación en dos pasos</h2>
		</div>
		
		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<div class="row">
			
			<div class="col-sm-offset-3 col-sm-6">
				
				<div class="well">
					
					<form id="totp-servicio" method="post" action="">
						<fieldset>

							<div class="form-horizontal">
								<div class="form-group">
									<label for="" class="col-sm-9 control-label" style="text-align: left !important;"><?php echo ($totp_activo) ? 'La autenticación en dos pasos está activada' : 'La autenticación en dos pasos está desactivada'; ?></label>
									<div class="col-sm-3" style="text-align: right !important;">
										<button class="btn btn-primary btn-sm" name="totp-estado"<?php echo ($totp_activo) ? ' data-bb="totp-desactivar"' : ''; ?>><?php echo ($totp_activo) ? 'Desactivar' : 'Activar'; ?></button>
									</div>
								</div>
							</div>

						</fieldset>
					</form>

					<?php if ($totp_activo && (! isset($_SESSION['totp_configuracion']) || ! $_SESSION['totp_configuracion'])): ?>

					<hr>

					<h4>Inicio de sesión:</h4>
					<br>
					<?php if (isset($config['mod_sms']) && $config['mod_sms']): ?>
					<h5 class="text-info"><strong><span class="fa fa-mobile fa-fw fa-lg"></span> Mensaje de texto (SMS)</strong></h5>
					<p>Usa tu teléfono como nivel adicional de seguridad para evitar que otras personas inicien sesión en tu cuenta.</p>
					<p><a href="clave.php" class="pull-right btn btn-xs btn-default">Cambiar</a> <strong>Teléfono móvil: <?php echo $row['telefono']; ?></strong></p>

					<hr>
					<?php endif; ?>

					<h5 class="text-info"><strong><span class="fa fa-code fa-fw fa-lg"></span> Generador de códigos</strong></h5>
					<p>Puedes utilizar la aplicación generador de códigos en tu dispositivo Android o iOS para iniciar sesión.</p>

					<hr>

					<h4>Notificaciones:</h4>
					<br>

					<h5 class="text-info"><strong><span class="fa fa-envelope fa-fw fa-lg"></span> Correo electrónico</strong></h5>
					<p>Te notificaremos cuando se inicie sesión desde un nuevo dispositivo o navegador, y si se ha desactivado la autenticación en dos pasos.</p>
					<p><a href="clave.php" class="pull-right btn btn-xs btn-default">Cambiar</a> <strong>Correo electrónico: <?php echo $row['correo']; ?></strong></p>

					<?php if (isset($_GET['tour']) && $_GET['tour']): ?>
					<br>
					<a href="admin/fotos/fotos_profes.php?tour=1" class="btn btn-primary">Siguiente</a>
					<?php endif; ?>

					<?php elseif ($totp_activo && isset($_SESSION['totp_configuracion']) && $_SESSION['totp_configuracion']): ?>

					<hr>

					<form id="totp-configuracion" method="post" action="">
						<fieldset>		

							<p>Sigue las siguientes instrucciones para configurar la autenticación en dos pasos en tu dispositivo:</p>
							<ol>
								<li>Descarga e instala la App Google Authenticator o Latch, disponibles para Android e iOS en tu dispositivo móvil.</li>
								<li>Escanea el código QR o escribe el código secreto manualmente.</li>
								<li>Genera un código temporal para verificar la configuración.</li>
							</ol>

							<br>

							<div class="text-center">
								<h4>Escanea el siguiente código QR</h4>
								<img src="<?php echo $qrCodeUrl; ?>" alt="" style="margin-bottom: 10px;">
								<p><code><strong><?php echo substr($secret,0,4).' '.substr($secret,4,4).' '.substr($secret,8,4).' '.substr($secret,12,4); ?></strong></code></p>
							</div>

							<br>

							<div class="col-sm-offset-2 col-sm-8">
								<div class="form-group text-center">
									<label for="totp-code-1">Introduce código temporal</label>
									<div id="totp" style="text-align: center !important;">
										<?php for ($i = 1; $i < 7; $i++): ?>
										<input type="text" class="form-control text-center" id="totp-code-<?php echo $i; ?>" name="totp-code-<?php echo $i; ?>" value="" maxlength="1" style="display: inline-block !important; width: 40px !important; padding: 0;">
										<?php endfor; ?>
									</div>
								</div>

								<div class="clearfix"></div>
								<p id="totp-validacion" class="text-center">&nbsp;</p>
								<input type="hidden" name="totp_verificado" value="1">
							</div>
						
						</fieldset>
					</form>

					<?php else: ?>	

					<p>Agrega un nivel adicional de seguridad para evitar que otras personas inicien sesión en tu cuenta. Inicia sesión con un código de tu teléfono y una contraseña</p>

					<?php if (isset($_GET['tour']) && $_GET['tour']): ?>
					<br>
					<a href="admin/fotos/fotos_profes.php?tour=1" class="btn btn-default">Omitir</a>
					<?php endif; ?>

					<?php endif; ?>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
		</div><!-- /.row -->

		<?php if (! isset($_SESSION['totp_configuracion']) || ! $_SESSION['totp_configuracion']): ?>
		<br><br><br>
		<?php endif; ?>	
		
	</div><!-- /.container -->

<?php include("pie.php"); ?>

<script>
$(document).ready(function () {

	function totp_validate(code) {
		if (code.length == 6) {
			$.post( "./lib/google-authenticator/totp_validacion.php", { "totp_code" : code}, null, "json" )
			.done(function(data, textStatus, jqXHR) {
				if (data.status) {
					$("#totp-validacion").html('<strong class="text-success">El código temporal es válido.</strong>');
					document.getElementById('totp-configuracion').submit();
				}
				else {
					$("#totp-validacion").html('<strong class="text-danger">El código temporal no es válido</strong>');
					document.getElementById('totp-configuracion').reset();
					$("#totp-code-1").focus();
				}
			});
		}
	}

	var totp_code = '';

	$("#totp-code-1").focus();
	
	$("#totp-code-1").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
					totp_code = value;
					$("#totp-code-2").focus();
					totp_validate(totp_code);
			}
	});
	$("#totp-code-2").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
				totp_code = totp_code + value;
				$("#totp-code-3").focus();
				totp_validate(totp_code);
			}
			else {
				$("#totp-code-1").focus();
			}
	});
	$("#totp-code-3").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
				totp_code = totp_code + value;
				$("#totp-code-4").focus();
				totp_validate(totp_code);
			}
			else {
				$("#totp-code-2").focus();
			}
	});
	$("#totp-code-4").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
				totp_code = totp_code + value;
				$("#totp-code-5").focus();
				totp_validate(totp_code);
			}
			else {
				$("#totp-code-3").focus();
			}
	});
	$("#totp-code-5").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
				totp_code = totp_code + value;
				$("#totp-code-6").focus();
				totp_validate(totp_code);
			}
			else {
				$("#totp-code-4").focus();
			}
	});
	$("#totp-code-6").keyup(function () {
			var value = $(this).val();
			if (value.length > 0) {
				totp_code = totp_code + value;
				totp_validate(totp_code);
			}
			else {
				$("#totp-code-5").focus();
			}
	});

	$(document).on("click", "button[data-bb]", function(e) {
		
		var type = $(this).data("bb");
		
		if (type == 'totp-desactivar') {
			bootbox.setDefaults({
				locale: "es",
				show: true,
				backdrop: true,
				closeButton: true,
				animate: true,
				title: "Desactivar autenticación en dos pasos",
			});
			
			bootbox.confirm("¿Está seguro que desea desactivar la autenticación en dos pasos?", function(result) {
					if (! result) {
						e.preventDefault();
					}
			});
		}
	});

});
</script>

</body>
</html>
