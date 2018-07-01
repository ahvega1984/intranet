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

$result = mysqli_query($db_con, "SELECT mens_texto.asunto, mens_texto.ahora, mens_texto.texto, mens_texto.origen FROM mens_texto JOIN mens_profes ON mens_texto.id = mens_profes.id_texto WHERE mens_texto.id = '$id' LIMIT 1") or die (mysqli_error($db_con));
$mensaje = mysqli_fetch_array($result);

if(mysqli_num_rows($result)<1) {
	header('Location:'.'index.php');
	exit();
}

// Obtenemos el nombre del profesor de origen
$query = mysqli_query($db_con,"SELECT nombre FROM departamentos WHERE idea = '".$mensaje['origen']."' LIMIT 1");
$row = mysqli_fetch_array($query);
$nom_profesor = $row['nombre'];

// Consultamos si el mensaje se ha marcado como tarea
$result_tarea = mysqli_query($db_con, "SELECT id FROM tareas WHERE titulo = '".$mensaje['asunto']."' AND tarea = '".$mensaje['texto']."'");
if (mysqli_num_rows($result_tarea)) {
	$esTarea = 1;
}
else {
	$esTarea = 0;

	// Marcar como tarea
	if (isset($_GET['tarea']) && $_GET['tarea']) {
		$titulo = $mensaje['asunto'];
		$enlace = '//'.$config['dominio'].'/intranet/admin/mensajes/redactar.php?profes=1&origen='.$mensaje['origen'].'&asunto=RE:%20'.$titulo;
		$tarea = htmlspecialchars_decode($mensaje['texto']).'<p><br></p><p>Enviado por: '.$nom_profesor.'</p><p><a id="enlace_respuesta" href="'.$enlace.'"></a>';
		$fechareg = date('Y-m-d H:i:s');
		mysqli_query($db_con, "INSERT tareas (idea, titulo, tarea, estado, fechareg, prioridad) VALUES ('".$idea."', '".$titulo."', '".$tarea."', 0, '".$fechareg."', 0)");	
	}
}

// Marcamos como leido
mysqli_query($db_con, "UPDATE mens_profes SET recibidoprofe = 1 WHERE id_profe = '".$idprof."' AND id_texto = '".$id."' LIMIT 1");

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

	    	<h3><?php echo $mensaje['asunto']; ?></h3>
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
					<?php if (! $esTarea): ?>
					<a href="mensaje.php?id=<?php echo $id; ?>&amp;idprof=<?php echo $idprof; ?>&amp;tarea=1" class="btn btn-warning">Marcar como tarea</a>
					<?php endif; ?>
	      	<?php $id !== $idprof ? $buzon='recibidos' : $buzon='enviados'; ?>
					<a href="index.php?inbox=<?php echo $buzon; ?>&amp;delete=<?php echo $idprof; ?>" class="btn btn-danger" data-bb="confirm-delete">Eliminar</a>
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