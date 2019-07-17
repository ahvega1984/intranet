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
        			<?php $haveibeenpwned_datos .= str_ireplace(array('Email addresses', 'Password hints', 'Passwords', 'Usernames', 'Employers', 'Geographic locations', 'Job titles', 'Names', 'Phone numbers', 'Salutations', 'Social media profiles', 'IP addresses', 'Dates of birth', 'Genders', 'Physical addresses', 'Spoken languages'), array('Direcciones de email', 'Sugerencias de contraseñas', 'Contraseñas', 'Nombres de usuario', 'Empleados', 'Geolocalizaciones', 'Cargos laborales', 'Nombres', 'Números de teléfono' , 'Saludos', 'Perfiles de redes sociales', 'Direcciones IP', 'Fechas de nacimiento', 'Sexo', 'Domicilios', 'Idiomas hablados'), $haveibeenpwned_clases) . ", "; ?>
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
