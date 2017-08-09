<?php
require('../../bootstrap.php');

$pr = $_SESSION['profi'];

if (isset($_GET['id_prof'])) {
	$idprof = $_GET['id_prof'];
	$id = $_GET['id_text'];
}
else {
	$idprof = intval($_GET['idprof']);
	$id = intval($_GET['id']);
}

// MARCAR COMO TAREA
if (isset($_GET['esTarea']) && $_GET['esTarea'] == 1) {
	mysqli_query($db_con, "UPDATE mens_profes SET esTarea = 1 WHERE id_profe = '$idprof'");
}

// MARCAR COMO TAREA REALIZADA
if ((isset($_GET['esTarea']) && $_GET['esTarea'] == 1) && (isset($_GET['estadoTarea']) && $_GET['estadoTarea'] == 1)) {
	mysqli_query($db_con, "UPDATE mens_profes SET esTarea = 1, estadoTarea = 1 WHERE id_profe = '$idprof'");
}

// MARCAR COMO TAREA PENDIENTE
if ((isset($_GET['esTarea']) && $_GET['esTarea'] == 1) && (isset($_GET['estadoTarea']) && $_GET['estadoTarea'] == 0)) {
	mysqli_query($db_con, "UPDATE mens_profes SET esTarea = 1, estadoTarea = 0 WHERE id_profe = '$idprof'");
}

// DESMARCAR COMO TAREA
if ((isset($_GET['esTarea']) && $_GET['esTarea'] == 0)) {
	mysqli_query($db_con, "UPDATE mens_profes SET esTarea = 0, estadoTarea = 0 WHERE id_profe = '$idprof'");
}


$result = mysqli_query($db_con, "SELECT mens_texto.asunto, mens_texto.ahora, mens_texto.texto, mens_texto.origen, mens_profes.esTarea, mens_profes.estadoTarea FROM mens_texto JOIN mens_profes ON mens_texto.id = mens_profes.id_texto WHERE mens_texto.id = '$id' LIMIT 1") or die (mysqli_error($db_con));
$mensaje = mysqli_fetch_array($result);

if(mysqli_num_rows($result)<1) {
	header('Location:'.'index.php');
	exit();
}

include("../../menu.php");
include("menu.php");
?>

	<div class="container">
	  
	  <!-- TITULO DE LA PAGINA -->
	  <div class="page-header">
	    <h2>Mensajes <small>Leer un mensaje</small></h2>
	  </div>
		
	  <!-- SCAFFOLDING -->
	  <div class="row">
	  	
	  	<!-- COLUMNA CENTRAL -->
	    <div class="col-sm-12">
	    	<?php 
				$query = mysqli_query($db_con,"SELECT nombre FROM departamentos WHERE idea = '".$mensaje['origen']."' LIMIT 1");
				$row = mysqli_fetch_array($query);
				$nom_profesor = $row['nombre'];
				?>
	    	
	    	<h3 class="text-info"><?php echo $mensaje['asunto']; ?></h3>
	    	<h5 class="text-muted">Enviado por <?php echo nomprofesor($nom_profesor); ?> el <?php echo fecha_actual2($mensaje['ahora']); ?>
	    	</h5>
	    	
	    	<br>
	    	
	      <?php echo stripslashes(html_entity_decode($mensaje['texto'], ENT_QUOTES, 'UTF-8')); ?>
	      
				<br>
				<br>
	      
	      <div class="hidden-print">
	      	<a href="index.php" class="btn btn-default">Volver</a>
	      	<a href="redactar.php?profes=1&amp;origen=<?php echo $mensaje['origen']; ?>&amp;asunto=RE: <?php echo $mensaje['asunto']; ?>" class="btn btn-primary">Responder</a>
	      	<a href="#" class="btn btn-info" onclick="javascript:print();">Imprimir</a>
	      	<?php $id !== $idprof ? $buzon='recibidos' : $buzon='enviados'; ?>
					<a href="index.php?inbox=<?php echo $buzon; ?>&amp;delete=<?php echo $idprof; ?>" class="btn btn-danger" data-bb="confirm-delete">Eliminar</a>
					<?php if ($mensaje['esTarea']): ?>
					<div class="btn-group">
						<?php 
						if ($mensaje['esTarea'] == 1 && $mensaje['estadoTarea'] == 0) {
							$btn_style = 'btn-warning';
						}
						elseif ($mensaje['esTarea'] == 1 && $mensaje['estadoTarea'] == 1) {
							$btn_style = 'btn-success';
						}
						else {
							$btn_style = 'btn-default';
						}
						?>
						<button type="button" class="btn <?php echo $btn_style; ?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php	echo ($mensaje['esTarea'] == 1 && $mensaje['estadoTarea'] == 0) ? 'Tarea pendiente' : 'Tarea realizada'; ?> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php if ($mensaje['esTarea'] == 1 && $mensaje['estadoTarea'] != 0): ?>
							<li><a href="mensaje.php?id_prof=<?php echo $idprof; ?>&amp;id_text=<?php echo $id; ?>&amp;esTarea=1&amp;estadoTarea=0">Marcar como tarea pendiente</a></li>
							<?php else: ?>
							<li><a href="mensaje.php?id_prof=<?php echo $idprof; ?>&amp;id_text=<?php echo $id; ?>&amp;esTarea=1&amp;estadoTarea=1">Marcar como tarea realizada</a></li>
							<?php endif; ?>
							<li><a href="mensaje.php?id_prof=<?php echo $idprof; ?>&amp;id_text=<?php echo $id; ?>&amp;esTarea=0">Desmarcar como tarea</a></li>
						</ul>
					</div>
					<?php else: ?>
					<a href="mensaje.php?id_prof=<?php echo $idprof; ?>&amp;id_text=<?php echo $id; ?>&amp;esTarea=1" class="btn btn-default">Marcar como tarea</a>
					<?php endif; ?>
	      </div>
	      
	    </div><!-- /.col-sm-12 -->
	    
	  </div><!-- /.row -->
	  
	  <br>
	  
	  <div class="row hidden-print">
	  
	  	<div class="col-sm-12">
	  	
			  <div class="well">
			    <fieldset>
			      <legend>Destinatarios</legend>
			    
			    <?php
			    $result = mysqli_query($db_con, "SELECT recibidoprofe, profesor from mens_profes where id_texto = '$id'");
			    $destinatarios = '';
			    while($destinatario = mysqli_fetch_array($result)) {			      	
				// Profesor
			    	$query = mysqli_query($db_con,"select nombre from departamentos where idea = '$destinatario[1]'");	
			      	if (mysqli_num_rows($query)>0) {			      	
					$row = mysqli_fetch_array($query);
					$nom_profesor = $row[0];
			      if ($destinatario[0] == '1') {
			      	$destinatarios .= '<span class="text-success">'.nomprofesor($nom_profesor).'</span> | ';
			      }
			      else {
			      	$destinatarios .= '<span class="text-danger">'.nomprofesor($nom_profesor).'</span> | ';
			      }
			      }
			   // Alumno   
			      else{
			    	$query2 = mysqli_query($db_con,"select nombre, apellidos from alma where claveal = '$destinatario[1]'");			      
			      	$row2 = mysqli_fetch_array($query2);
					$nom_alumno = $row2[0]." ".$row2[1];
			      if ($destinatario[0] == '1') {
			      	$destinatarios .= '<span class="text-success">'.$nom_alumno.'</span> | ';
			      }
			      else {
			      	$destinatarios .= '<span class="text-danger">'.$nom_alumno.'</span> | ';
			      }
			      }
			    }
			    echo trim($destinatarios, ' | ');
			    ?>
			    </fieldset>
			    
			  </div><!-- /.well -->
	  	
	  	</div><!-- /.col-sm-12 -->
	  
	  </div><!-- /.row -->
	  
	</div><!-- /.container -->

<?php include('../../pie.php'); ?>

</body>
</html>