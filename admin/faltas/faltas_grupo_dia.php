<?php
require('../../bootstrap.php');

function tipo_falta($falta) {
      
  switch ($falta) {
    case 'J' : $tipo = 'Justificada'; break;
    case 'F' : $tipo = 'Injustificada'; break;
    case 'I' : $tipo = 'Injustificada'; break;
    case 'R' : $tipo = 'Retraso'; break;
  }

  return $tipo;
}


include("../../menu.php");
include("../../faltas/menu.php");

if (isset($_POST['grupo'])) {
	$grupo = $_POST['grupo'];
} 
elseif (isset($_GET['grupo'])) {
	$grupo = $_GET['grupo'];
} 
else
{
$grupo="";
}

if (isset($_POST['fecha'])) {
	$fecha0 = $_POST['fecha'];
}
elseif (isset($_GET['fecha'])) {
	$fecha0 = $_GET['fecha'];
} 
else
{
$fecha0="";
}
?>
  
  <div class="container">

    <div class="page-header">
      <h2>Faltas de Asistencia <small> Informe de faltas de un Grupo en una Fecha</small></h2>
    </div>
    
    <br>

    <div class="row">
      
      <div class="col-sm-12">
      
        <h3>Grupo: <span class="text-danger"><?php echo $grupo;?></span><br>Fecha: <span class="text-danger"><?php echo formatea_fecha($fecha0);?></span></h3>
        <br>
    
        <?php $result = mysqli_query($db_con, "SELECT nc, apellidos, nombre, claveal FROM FALUMNOS WHERE unidad = '$grupo' ORDER BY nc"); 
        ?>

        <?php if (mysqli_num_rows($result)): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-condensed table-striped table-hover">
            <thead>
              <tr>
                <th>Alumno</th>
                <?php for ($i = 1; $i < 7; $i++): ?>
                <th><?php echo $i; ?>ª hora</th>
                <?php endfor; ?>
              </tr>
            </thead>
            
  
            
            		<tbody>
			<?php while ($row = mysqli_fetch_array($result)): 
        $claveal = $row['claveal'];
        $nc = $row['nc'];
        $apellidos = $row['apellidos'];
        $nombre = $row['nombre']; 
      ?>
			<tr>
				<th><abbr><?php echo $row['nc'].". ".$row['apellidos']." ".$row['nombre']; ?></abbr></th>
				<?php for ($i = 1; $i < 7; $i++): ?>
				<?php $result_falta = mysqli_query($db_con, "SELECT DISTINCT falta, codasi FROM FALTAS WHERE claveal = '$claveal' AND fecha = '$fecha0' AND hora = '$i'");?>
				<?php $row_falta = mysqli_fetch_array($result_falta); ?>
				<?php $result_asig = mysqli_query($db_con, "SELECT DISTINCT asignaturas.abrev, asignaturas.nombre FROM asignaturas WHERE asignaturas.codigo = '".$row_falta['codasi']."' and abrev not like '%\_%'"); ?>
				<?php $row_asig = mysqli_fetch_array($result_asig); ?>
				
				<td>
					<abbr data-bs="tooltip" title="<?php echo $row_asig['nombre']; ?>">
						<span class="label label-default"><?php echo $row_asig['abrev']; ?></span>
					</abbr>
					
					<abbr data-bs="tooltip" title="<?php echo tipo_falta($row_falta['falta']); ?>">
					<?php echo ($row_falta['falta'] == "I" || $row_falta['falta'] == "F") ? '<span class="label label-danger">'.$row_falta['falta'].'</label>' : ''; ?>
					<?php echo ($row_falta['falta'] == "R") ? '<span class="label label-warning">'.$row_falta['falta'].'</label>' : ''; ?>
					<?php echo ($row_falta['falta'] == "J") ? '<span class="label label-success">'.$row_falta['falta'].'</label>' : ''; ?>
					</abbr>
				</td>
				<?php endfor; ?>
			</tr>
			<?php endwhile; ?>
		</tbody>
		
          </table>
        </div>
        <?php endif; ?>

        <div class="hidden-print">
          <a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
          <a class="btn btn-default" href="javascript:history.go(-1);">Volver</a>
        </div>

      </div><!-- /.col-sm-12 -->

    </div><!-- /.row -->


  </div>

  <?php include("../../pie.php");?>

</body>
</html>