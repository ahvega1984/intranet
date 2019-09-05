<?php
require('../../../bootstrap.php');
include("../../../menu.php");
?>

	<div class="container">

		<div class="page-header">
			<h2 class="page-title">Brechas de seguridad</h2>
		</div>

		<div class="row">
			<div class="col-sm-12">

        <?php if (isset($haveibeenpwned) && $haveibeenpwned_total > 0): ?>
      	<p>Tu cuenta de correo electrónico <strong><?php echo $haveibeenpwned_email; ?></strong> se ha visto comprometida en <?php echo (($haveibeenpwned_total > 1) ? '<strong>'.$haveibeenpwned_total.'</strong> sitios' : '<strong>1</strong> sitio'); ?>. Te recomendamos que <?php echo (((stristr($haveibeenpwned_email, '@juntadeandalucia.es') === false) || (stristr($haveibeenpwned_email, '@'.$_SERVER['SERVER_NAME']) === false)) ? '<strong>utilices el correo corporativo en lugar de tu dirección de correo electrónico personal</strong> y que ' : ''); ?>revises los datos que han sido comprometidos en las siguientes plataformas:</p>

        <div class="well">
          <?php $i = 0; ?>
          <?php foreach ($haveibeenpwned as $haveibeenpwned_item): ?>
          <?php $i++; ?>
        	<?php if ($i > 1): ?>
          <hr style="border-color: #999;">
          <?php endif; ?>
        	<div class="row">
        		<div class="col-xs-2 col-sm-1">
        			<img class="img-responsive" src="<?php echo $haveibeenpwned_item->LogoPath; ?>" alt="<?php echo $haveibeenpwned_item->Title; ?>">
        		</div>
        		<div class="col-xs-10 col-sm-11">
        			<h4><?php echo $haveibeenpwned_item->Title; ?> <span class="pull-right"><?php echo strftime('%b. %Y', strtotime($haveibeenpwned_item->BreachDate)); ?></span></h4>
        			<p><strong>Datos comprometidos:</strong>
        			<?php $haveibeenpwned_datos = ""; ?>
        			<?php foreach ($haveibeenpwned_item->DataClasses as $haveibeenpwned_clases): ?>
        			<?php $haveibeenpwned_datos .= str_ireplace(array('Email addresses', 'Password hints', 'Passwords', 'Usernames', 'Employers', 'Geographic locations', 'Job titles', 'Names', 'Phone numbers', 'Salutations', 'Social media profiles', 'IP addresses', 'Dates of birth', 'Genders', 'Physical addresses', 'Spoken languages', 'Private messages', 'Ages', 'Auth tokens', 'Employment statuses', 'Marital statuses', 'Security questions and answers', 'Website activity', 'Account balances', 'Eating habits', 'Physical attributes', 'Browser user agent details', 'Purchases', 'Years of professional experience', 'Professional skills', 'Device information', 'Device usage tracking data', 'Credit status information', 'Home loan information', 'Personal descriptions', 'Income levels', 'Payment histories', 'Partial credit card data', 'Support tickets', 'Credit cards', 'Homepage URLs', 'Instant messenger identities', 'Email messages', 'PINs'), array('Direcciones de email', 'Sugerencias de contraseñas', 'Contraseñas', 'Nombres de usuario', 'Empleados', 'Geolocalizaciones', 'Cargos laborales', 'Nombres', 'Números de teléfono' , 'Saludos', 'Perfiles de redes sociales', 'Direcciones IP', 'Edad', 'Fechas de nacimiento', 'Sexo', 'Domicilios', 'Idiomas hablados', 'Tokens de autenticación', 'Estados laborales', 'Estados civiles', 'Mensajes privados', 'Preguntas y respuestas de seguridad', 'Actividad en el sitio web', 'Balance de cuentas', 'Hábitos de comida', 'Atributos físicos', 'Detalles del agente de usuario del navegador', 'Compras', 'Años de experiencia profesional', 'Habilidades profesionales', 'Información de dispositivos', 'Seguimiento de uso de tu dispositivo', 'Información sobre créditos', 'Información sobre préstamos', 'Descripciones personales', 'Ingresos', 'Historial de pagos', 'Datos parciales de tarjetas de crédito', 'Tickets de soporte', 'Tarjetas de crédito', 'URLs de página de inicio', 'Identidades de mensajería instantánea', 'Mensajes de correo', 'PINs'), $haveibeenpwned_clases) . ", "; ?>
        			<?php endforeach; ?>
        			<?php echo rtrim($haveibeenpwned_datos, ", ") . "."; ?>
        			</p>
        		</div>
        	</div>
        	<?php endforeach; ?>
          <?php else: ?>
          <p>Tu cuenta de correo electrónico no se ha visto comprometida.</p>
          <?php endif; ?>
        </div>

      </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->

  </div><!-- /.container -->

<?php include('../../../pie.php'); ?>

</body>
</html>
