<?php
require('../../bootstrap.php');
require("../../lib/class.phpmailer.php");

acl_acceso($_SESSION['cargo'], array(1));

function generador_password($long)
{
	$alfabeto = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $long_alfabeto = strlen($alfabeto) - 1;
    for ($i = 0; $i < $long; $i++) {
        $p = rand(0, $long_alfabeto);
        $pass[] = $alfabeto[$p];
    }
    return implode($pass);
}

if (isset($_POST['enviar'])) {

	$num = 0;
	foreach($_POST['cambio'] as $p_dni){
		mysqli_query($db_con, "update c_profes set pass='".sha1($p_dni)."', estado=0 where dni='$p_dni'");
		
		$mail0 = mysqli_query($db_con, "select correo, profesor from c_profes where dni='$p_dni'");
		$mail = mysqli_fetch_array($mail0);
		
		$mail_correo = $mail[0];
		$mail_nomprofesor = $mail[1];
		
		if($mail_correo != "") {
		
			// Excepción para el usuario Administrador
			if ($mail_nomprofesor == 'Administrador') {
				$pass_admin = generador_password(9);
				$pass_sha1	= sha1($pass_admin);
				
				mysqli_query($db_con, "UPDATE c_profes SET pass='$pass_sha1', dni='$pass_admin', estado=0 WHERE PROFESOR='Administrador' LIMIT 1");
				mysqli_query($db_con, "UPDATE departamentos SET DNI='$pass_admin' WHERE NOMBRE='Administrador' LIMIT 1");
			}
			
			$mail = new PHPMailer();
			$mail->Host = "localhost";
			$mail->From = 'no-reply@'.$config['dominio'];
			$mail->FromName = $config['centro_denominacion'];
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
						
			// Excepción para el usuario Administrador
			if ($mail_nomprofesor == 'Administrador') {
				$message = str_replace('{{titulo}}', 'Restablecimiento de la cuenta de Administrador', $message);
				$message = str_replace('{{contenido}}', 'Estimado '.$mail_nomprofesor.',<br><br>Su contraseña ha sido restablecida por algún miembro del Equipo Directivo. Para acceder a la Intranet haga click en el botón Iniciar sesión y utilice la contraseña que aparece a continuación:<br><br>
				<center>
				<table cellpadding="0" cellspacing="0" width="250">
				  <tr>
				    <td class="code-block">
				      '.$pass_admin.'
				    </td>
				  </tr>
				  <tr>
				    <td class="button">
				      <div style="text-align: center;"><!--[if mso]>
				        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://'.$config['dominio'].'/intranet/" style="height:45px;v-text-anchor:middle;width:155px;" arcsize="15%" strokecolor="#ffffff" fillcolor="#ff6f6f">
				          <w:anchorlock/>
				          <center style="color:#ffffff;font-family:Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;">Iniciar sesión</center>
				        </v:roundrect>
				      <![endif]--><a class="button-mobile" href="http://'.$config['dominio'].'/intranet/"
				      style="background-color: rgb(48,119,67);border-radius:5px;color:#ffffff;display:inline-block;font-family:\'Cabin\', Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;">Iniciar sesión</a></div>
				    </td>
				  </tr>
				</table>
				</center>
				<br><br>Para mantener su seguridad utilice una contraseña segura.<br><hr><small>Este es un mensaje automático y no es necesario responder.</small>', $message);
				
				$mail->Subject = $config['centro_denominacion'].' - Restablecimiento de la cuenta de Administrador';
				$mail->AltBody = "Estimado ".$mail_nomprofesor.",\n\n>Su contraseña ha sido restablecida por algún miembro del Equipo Directivo. Para acceder a la Intranet haga click en la siguiente dirección http://".$config['dominio']."/intranet/ y utilice la contraseña que aparece a continuación:\n\n".$pass_admin."\n\n\nPara mantener su seguridad utilice una contraseña segura.\n\nEste es un mensaje automático y no es necesario responder.";
			}
			else {
				$message = str_replace('{{titulo}}', 'Restablecimiento de contraseña', $message);
				$message = str_replace('{{contenido}}', 'Estimado '.$mail_nomprofesor.',<br><br>Su contraseña ha sido restablecida por algún miembro del Equipo Directivo. Para acceder a la Intranet haga click en el botón Iniciar sesión y utilice la contraseña que aparece a continuación:<br>br>
				<center>
				<table cellpadding="0" cellspacing="0" width="250">
				  <tr>
				    <td class="code-block">
				      '.$p_dni.'
				    </td>
				  </tr>
				  <tr>
				    <td class="button">
				      <div style="text-align: center;"><!--[if mso]>
				        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://'.$config['dominio'].'/intranet/" style="height:45px;v-text-anchor:middle;width:155px;" arcsize="15%" strokecolor="#ffffff" fillcolor="#ff6f6f">
				          <w:anchorlock/>
				          <center style="color:#ffffff;font-family:Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;">Iniciar sesión</center>
				        </v:roundrect>
				      <![endif]--><a class="button-mobile" href="http://'.$config['dominio'].'/intranet/"
				      style="background-color: rgb(48,119,67);border-radius:5px;color:#ffffff;display:inline-block;font-family:\'Cabin\', Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;">Iniciar sesión</a></div>
				    </td>
				  </tr>
				</table>
				</center>
				<br><br>Para mantener su seguridad utilice una contraseña segura.<br><hr><small>Este es un mensaje automático y no es necesario responder.</small>', $message);
				
				$mail->Subject = 'Restablecimiento de contraseña';
				$mail->AltBody = "Estimado ".$mail_nomprofesor.",\n\nTu contraseña ha sido restablecida por algún miembro del Equipo Directivo. Para acceder a la Intranet haz click en la siguiente dirección http://".$config['dominio']."/intranet/ y utilice la contraseña que aparece a continuación:\n\n".$p_dni."\n\n\nPara mantener su seguridad utilice una contraseña segura.\n\nEste es un mensaje automático y no es necesario responder.";
			}
			
			$mail->msgHTML($message);
			
			$mail->AddAddress($mail_correo, $mail_nomprofesor);
			$mail->Send();
		}
		
		$num++;
	}
	
	if($num > 1) {
		$msg_success = 'Se han restablecido las contraseñas seleccionadas. Se ha enviado un correo electrónico a los usuarios afectados.';
	}
	else {
		$msg_success = 'Se ha restablecido la contraseña seleccionada. Se ha enviado un correo electrónico al usuario afectado.';
	}

}

include("../../menu.php");
?>
<div class="container">

	<div class="page-header">
  		<h2>Administración <small> Restablecer contraseña</small></h2>
	</div>
	
	<?php if(isset($msg_success)): ?>
	<div class="alert alert-success alert-block">
		<?php echo $msg_success; ?>
	</div>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-sm-6">
			
			<form name="cargos" action="reset_password.php" method="post">

				<div class="well">
				
					<div class="form-group">
						<label for="resetPassword">Selecciona los profesores</label>
						<select class="form-control" id="resetPassword" name="cambio[]" multiple size="15">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT profesor, dni FROM c_profes ORDER BY profesor"); ?>
							<?php while($row = mysqli_fetch_array($result)): ?>
							<option value="<?php echo $row['dni'];?>"><?php echo $row['profesor'];?></option>
							<?php endwhile; ?>
						</select>
					</div>

					<button type="submit" class="btn btn-primary" name="enviar" value="Reasignar clave">Restablecer contraseñas</button>
					<a class="btn btn-default" href="../index.php">Volver</a>
					
				</div>

			</form>
		</div>
		
		<div class="col-sm-6">
			<legend>Instrucciones</legend>
			
			<p class="block-help">Selecciona en primer lugar el profesor o profesores a los que se necesita restablecer la clave de acceso. Si quieres seleccionar varios usuarios, mantén pulsada la tecla <kbd>Ctrl</kbd> mientras haces click con el ratón en cada uno de ellos.</p>
			
			<p>Tras enviar los datos del formulario se les enviará un correo comunicándoles que la clave ha sido restablecida y la nueva clave provisional es ahora el DNI como si el usuario entrase por primera vez en la aplicación. </p>
		</div>
	</div>
</div>

<?php include("../../pie.php");?>
</body>
</html>