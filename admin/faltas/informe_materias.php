<?php
require('../../bootstrap.php');
include("../../menu.php");
include("../../faltas/menu.php");
?>

<script src="../../js/ChartJS/Chart.min.js"></script>
<script type="text/javascript">
window.chartColors = {
    red: 'rgb(244, 67, 54)',
    pink: 'rgb(255, 64, 129)',
    purple: 'rgb(156, 39, 176)',
    deeppurple: 'rgb(124, 77, 255)',
    indigo: 'rgb(63, 81, 181)',
    blue: 'rgb(68, 138, 255)',
    lightblue: 'rgb(3, 169, 244)',
    cyan: 'rgb(0, 188, 212)',
    teal: 'rgb(0, 0, 0)',
    green: 'rgb(76, 175, 80)',
    lightgreen: 'rgb(139, 195, 74)',
    lime: 'rgb(205, 220, 57)',
    yellow: 'rgb(255, 235, 59)',
    amber: 'rgb(255, 193, 7)',
    orange: 'rgb(255, 152, 0)',
    deeporange: 'rgb(255, 87, 34)',
    brown: 'rgb(121, 85, 72)',
    grey: 'rgb(158, 158, 158)',
    bluegrey: 'rgb(96, 125, 139)'
};
</script>

<div class="container">

<div class="page-header">
  <h2>Informe sobre Faltas de Asistencia <small>Materia</small></h2>
</div>

<div id="status-loading" class="text-center">
    <br><br><span class="lead"><span class="far fa-circle-o-notch fa-spin"></span> Cargando datos...<br><small>El proceso puede tomar algún tiempo.</small></span><br><br><span class="fas fa-spinner fa-spin fa-5x"></span>
</div>



<div id="wrap" class="row" style="display: none;">
	
	<?php include("menu_informes.php"); ?>
	
	  <br>
	  
	  <div class="alert alert-info">
	  	Junto al nombre de la asignatura, entre <strong>paréntesis</strong>, el número de alumnos que cursa la asignatura en ese nivel; en <strong>negrita</strong> la media por alumno de faltas en la asignatura; en <strong>rojo</strong> las faltas no justificadas; en <strong>verde</strong> las faltas justificadas.
	  </div>
	  
	  <br>


  <div class="col-md-10 col-md-offset-1">
    
<?php 
$nm=0;
$crs = mysqli_query($db_con,"select distinct nomcurso, unidades.idcurso from unidades, cursos where unidades.idcurso=cursos.idcurso order by idunidad");
while ($curs = mysqli_fetch_array($crs)) {

$curso=$curs[0];
$idcurso=$curs[1];
?> 

<?php 
$asig_a="";
$total_navidad_f="";
$num_navidadF_f="";
$num_navidadJ_f="";
$total_santa_f="";
$num_santaF_f="";
$num_santaJ_f="";
$total_verano_f="";
$num_veranoF_f="";
$num_veranoJ_f="";
?>

  <h3 class='text-info' align='center'><?php echo $curso;?></h3>

<?php
$unidades = mysqli_query($db_con, "select distinct nombre, codigo from asignaturas where curso = '$curso' and abrev not like '%\_%' and nombre not like 'Refuerzo%' and nombre not like '%Padres y Madres%' order by nombre");
while ($grp = mysqli_fetch_array($unidades)) {
  
  $num_asig = "";

  $cod_asig = $grp[1];
  $nom_asig = $grp[0];

  $comb = mysqli_query($db_con,"select * from alma where combasi like '%$cod_asig:%' and curso = '$curso'");
  $num_asig = mysqli_num_rows($comb);
  
  $navidadF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadF = mysqli_num_rows($navidadF);
  $navidadJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadJ = mysqli_num_rows($navidadJ);
  $total_navidad = ($num_navidadF+$num_navidadJ)/$num_asig;

  $santaF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '%Semana Santa' limit 1)");
  $num_santaF = mysqli_num_rows($santaF);
  $santaJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '%Semana Santa' limit 1)");
  $num_santaJ = mysqli_num_rows($santaJ);
  $total_santa = ($num_santaF+$num_santaJ)/$num_asig;

  $veranoF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '%Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoF = mysqli_num_rows($veranoF);
  $veranoJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '%Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoJ = mysqli_num_rows($veranoJ);
  $total_verano = ($num_veranoF+$num_veranoJ)/$num_asig;
?>

<?php if ($num_asig > 0): ?>

<?php $asig_a.='"'.$nom_asig.' ('.$num_asig.')",'; ?>
<?php $total_navidad_f.='"'.substr($total_navidad,0,4).'",'; ?>
<?php $num_navidadF_f.='"'.$num_navidadF.'",'; ?>
<?php $num_navidadJ_f.='"'.$num_navidadJ.'",'; ?>
<?php $total_santa_f.='"'.substr($total_santa,0,4).'",'; ?>
<?php $num_santaF_f.='"'.$num_santaF.'",'; ?>
<?php $num_santaJ_f.='"'.$num_santaJ.'",'; ?>
<?php $total_verano_f.='"'.substr($total_verano,0,4).'",'; ?>
<?php $num_veranoF_f.='"'.$num_veranoF.'",'; ?>
<?php $num_veranoJ_f.='"'.$num_veranoJ.'",'; ?>

<?php endif; ?>

<?php
}
?>
<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_<?php echo $idcurso; ?>" width="200" height="460"></canvas>
<script>
    var ctx = document.getElementById('chart_<?php echo $chart_n; ?>_<?php echo $idcurso; ?>').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
    labels: [<?php echo $asig_a; ?>],
    datasets: [

    {
      backgroundColor: window.chartColors.teal,
      label: '1 trim. total',
      stack: 'Stack 0',
      data: [
        <?php echo $total_navidad_f; ?>
      ]
    }, {
      label: '1 trim.',
      backgroundColor: window.chartColors.red,
      stack: 'Stack 0',
      data: [
        <?php echo $num_navidadF_f; ?>
      ]
    }, {
      label: '1 trim.',
      backgroundColor: window.chartColors.green,
      stack: 'Stack 0',
      data: [
        <?php echo $num_navidadJ_f; ?>
      ]
    }, 

    {
      backgroundColor: window.chartColors.teal,
      label: '2 trim. total',
      stack: 'Stack 1',
      data: [
        <?php echo $total_santa_f; ?>
      ]
    }, {
      label: '2 trim.',
      backgroundColor: window.chartColors.red,
      stack: 'Stack 1',
      data: [
        <?php echo $num_santaF_f; ?>
      ]
    }, {
      label: '2 trim.',
      backgroundColor: window.chartColors.green,
      stack: 'Stack 1',
      data: [
        <?php echo $num_santaJ_f; ?>
      ]
    }, 

    {
      backgroundColor: window.chartColors.teal,
      label: '3 trim. total',
      stack: 'Stack 2',
      data: [
        <?php echo $total_verano_f; ?>
      ]
    }, {
      label: '3 trim.',
      backgroundColor: window.chartColors.red,
      stack: 'Stack 2',
      data: [
        <?php echo $num_veranoF_f; ?>
      ]
    }, {
      label: '3 trim.',
      backgroundColor: window.chartColors.green,
      stack: 'Stack 2',
      data: [
        <?php echo $num_veranoJ_f; ?>
      ]
    }]
  },
      options: {
        tooltips: {
          mode: 'nearest',
          intersect: false
        },
        responsive: true,
        scales: {
          xAxes: [{
            stacked: true,
            position: 'top'
          }],
          yAxes: [{
            stacked: true
          }]
        }
      }
    });

</script>
<br>
<?php
}
?>

</div>
</div>
</div>

<?php 
include("../../pie.php");
?> 
<script>
function espera() {
  document.getElementById("wrap").style.display = '';
  document.getElementById("status-loading").style.display = 'none';        
}
window.onload = espera;
</script>  
</body>
</html>
