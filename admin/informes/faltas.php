<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO RESUMEN FALTAS DE ASISTENCIA -->

<a name="faltas"></a>
<h3>Resumen de faltas de asistencia</h3>

<div class="row">
	
	<div class="col-sm-3">
	
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'F' and FALTAS.claveal = '$claveal'"); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">faltas injustificadas</small>
		</h3>
		
	</div>
	
	<div class="col-sm-3">
		
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'J' AND  FALTAS.claveal = '$claveal'"); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">faltas justificadas</small>
		</h3>
		
	</div>

	<div class="col-sm-3">
	
		<?php $result = mysqli_query($db_con, "SELECT COUNT(*) AS total FROM FALTAS WHERE FALTAS.falta = 'R' and FALTAS.claveal = '$claveal'"); ?>
		<?php $total = 0; ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php $row = mysqli_fetch_array($result); ?>
		<?php $total = $row['total']; ?>
		<?php mysqli_free_result($result); ?>
		<?php endif; ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">retrasos injustificados</small>
		</h3>
		
	</div>
	
	<div class="col-sm-3">
		
		<?php $result = mysqli_query($db_con, "SELECT distinct FALTAS.fecha FROM FALTAS where FALTAS.falta = 'F' and  FALTAS.claveal = $claveal order by FALTAS.fecha"); ?>
		<?php $total = 0; ?>
		<?php $total = mysqli_num_rows($result); ?>
		
		<h3 class="text-info text-center">
			<?php echo $total; ?><br>
			<small class="text-uppercase">días con faltas injustificadas</small>
		</h3>
		
	</div>

</div>

<br>

<!-- FIN MODULO RESUMEN FALTAS DE ASISTENCIA -->
