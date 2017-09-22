<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); ?>

<?php if (stristr($_SESSION['cargo'],'1') or stristr($_SESSION['cargo'],'2') or stristr($_SESSION['cargo'],'8') or $_SESSION['profi']==$tutor): ?>
<a name="intervenciones"></a>
<h3>Intervenciones de tutoría</h3>

<br>
<?php $prohibido = (stristr($_SESSION['cargo'],'1') == true || stristr($_SESSION['cargo'],'8') == true) ? "" : " and prohibido = '0'"; ?>
<?php $result_tutoria = mysqli_query($db_con, "SELECT fecha, accion, causa, observaciones FROM tutoria WHERE claveal = '$claveal' $prohibido"); ?>
<?php if (mysqli_num_rows($result_tutoria)): ?>

<?php while ($row = mysqli_fetch_array($result_tutoria)): ?>
<?php $exp_fecha = explode("-", $row['fecha']); ?>
<?php $fecha = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0]; ?>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th width="20%">Fecha</th>
      <th width="40%">Tipo de entrevista</th>
      <th width="40%">Causa</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><?php echo $fecha; ?></strong></td>
      <td><strong><?php echo rtrim($row['accion'], '; '); ?></strong></td>
      <td><strong><?php echo $row['causa']; ?></strong></td>
    </tr>
    <tr>
      <td colspan="3">
        <strong>Observaciones:</strong><br>
        <?php echo $row['observaciones']; ?>
      </td>
    </tr>
  </tbody>
</table>
<?php endwhile; ?>
<?php else: ?>

<h3 class="text-muted">El alumno/a no tiene intervenciones de tutoría</h3>

<?php endif; ?>
<?php endif; ?>