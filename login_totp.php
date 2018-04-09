<?php 
if ($_POST['totp_verificado'] && $_POST['totp_code']) {
	session_start();

	require_once('lib/google-authenticator/GoogleAuthenticator.php');
	$ga = new PHPGangsta_GoogleAuthenticator();

	$checkResult = $ga->verifyCode($_SESSION['totp_secreto'], $_POST['totp_code'], 2);    // 2 = 2*30sec clock tolerance
	if ($checkResult) {
		unset($_SESSION['totp_secreto']);
		unset($_SESSION['totp_codigo_movil']);
		$_SESSION['autentificado'] = 1;
		header("location:index.php");
		exit();
	}
	else {
		header("location:login.php");
		exit();
	}
}
else {
	defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Intranet del <?php echo $config['centro_denominacion']; ?>">
	<meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
	<meta name="robots" content="noindex, nofollow">

	<title>Intranet &middot; <?php echo $config['centro_denominacion']; ?></title>

	<link href="//<?php echo $config['dominio']; ?>/intranet/css/bootstrap.min.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/font-awesome.min.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/animate.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/otros.css" rel="stylesheet">
</head>

<body id="login">

	<div id="wrapper">

		<div class="container">

			<div class="text-center">
				<h1><?php echo $config['centro_denominacion']; ?></h1>
				<h4>Inicia sesión para acceder</h4>
			</div>

			<br>

			<div class="row">

				<div class="col-sm-offset-3 col-sm-6">
					<form id="totp-login" class="well" method="POST" action="login_totp.php" autocomplete="off">
						<div class="text-center form-signin-heading">
							<h3>Autenticación en dos pasos</h3>
							<?php if (isset($_SESSION['totp_codigo_movil']) && $_SESSION['totp_codigo_movil']): ?>
							<p class="text-center text-muted">Hemos enviado un mensaje de texto (SMS) a su teléfono ******<?php echo $_SESSION['totp_codigo_movil']; ?> con el código temporal, o también puede generar un código temporal desde su dispositivo móvil para iniciar sesión.</p>
							<?php else: ?>
							<p class="text-center text-muted">Genere un código temporal desde su dispositivo móvil para iniciar sesión</p>
							<?php endif; ?>
						</div>

						<br><br>

						<div class="col-sm-offset-2 col-sm-8">
							<div class="form-group text-center">
								<div id="totp" style="text-align: center !important;">
									<?php for ($i = 1; $i < 7; $i++): ?>
									<input type="text" class="form-control text-center" id="totp-code-<?php echo $i; ?>" name="totp-code-<?php echo $i; ?>" value="" maxlength="1" style="display: inline-block !important; width: 40px !important; padding: 0;">
									<?php endfor; ?>
									<input type="hidden" id="totp_code" name="totp_code" value="">
									<input type="hidden" name="totp_verificado" value="1">
								</div>
							</div>
						</div>

						<div class="clearfix"></div>
						<br>

						<div id="msg_error"></div>

						<div class="form-signin-footer"></div>
					</form>
				</div>

			</div>

		</div><!-- /.container -->

	</div><!-- /#wrap -->

	<footer class="hidden-print">
		<div class="container-fluid">
			<p class="pull-left text-muted">&copy; <?php echo date('Y'); ?>, I.E.S. Monterroso</p>

			<ul class="pull-right list-inline">
				<li>Versión <?php echo INTRANET_VERSION; ?></li>
				<li><a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia</a></li>
				<li><a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a></li>
			</ul>
		</div>
	</footer>


	<script src="//<?php echo $config['dominio']; ?>/intranet/js/jquery-2.1.1.min.js"></script>
	<script src="//<?php echo $config['dominio']; ?>/intranet/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {

	function totp_validate(code) {
		if (code.length == 6) {
			$.post( "./lib/google-authenticator/totp_validacion.php", { "totp_code" : code}, null, "json" )
			.done(function(data, textStatus, jqXHR) {
				if (data.status) {
					$("#totp_code").val(code);
					document.getElementById('totp-login').submit();
				}
				else {
					$("#msg_error").html('<div class="alert alert-danger text-center" role="alert">Código de verificación incorrecto.</div>');
					document.getElementById('totp-login').reset();
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

});
</script>

</body>
</html>
