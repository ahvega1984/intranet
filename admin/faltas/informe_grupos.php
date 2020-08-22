<?php
require('../../bootstrap.php');

$PLUGIN_DATATABLES = 1;

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
    teal: 'rgb(0, 150, 136)',
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
  <h2>Informe sobre Faltas de Asistencia <small>Grupos</small></h2>
</div>

<div id="status-loading" class="text-center">
    <br><br><span class="lead"><span class="far fa-circle-o-notch fa-spin"></span> Cargando datos...<br><small>El proceso puede tomar algún tiempo.</small></span><br><br><span class="fas fa-spinner fa-spin fa-5x"></span>
</div>


<div id="wrap" class="row" style="display: none;">

	<?php include("menu_informes.php"); ?>
  
  <br>

  <div class="col-md-10 col-md-offset-1">
<?php 
$nm=0;
$crs = mysqli_query($db_con,"select distinct nomcurso, unidades.idcurso from unidades, cursos where unidades.idcurso=cursos.idcurso order by idcurso");
while ($curs = mysqli_fetch_array($crs)) {

$curso=$curs[0];
$idcurso=$curs[1];

?> 
  <h4 class='text-info' align='center'><?php echo $curso;?></h4>
  
<?php
$grupo="";
$total_navidad="";
$total_santa="";
$total_verano="";

$unidades = mysqli_query($db_con, "select nomunidad from unidades where idcurso = '$idcurso' order by idcurso");
while ($grp = mysqli_fetch_array($unidades)) {


  $unidad = $grp[0];
  
  $navidadF = mysqli_query($db_con,"select * from FALTAS where falta='F' and unidad = '$unidad' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadF = mysqli_num_rows($navidadF);
  $navidadJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and unidad = '$unidad' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadJ = mysqli_num_rows($navidadJ);
  $total_navidad = $num_navidadF+$num_navidadJ;

  $santaF = mysqli_query($db_con,"select * from FALTAS where falta='F' and unidad = '$unidad' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '% Semana Santa' limit 1)");
  $num_santaF = mysqli_num_rows($santaF);
  $santaJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and unidad = '$unidad' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '% Semana Santa' limit 1)");
  $num_santaJ = mysqli_num_rows($santaJ);
  $total_santa = $num_santaF+$num_santaJ;

  $veranoF = mysqli_query($db_con,"select * from FALTAS where falta='F' and unidad = '$unidad' and date(fecha) > (select fecha from festivos where nombre like '% Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoF = mysqli_num_rows($veranoF);
  $veranoJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and unidad = '$unidad' and date(fecha) > (select fecha from festivos where nombre like '% Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoJ = mysqli_num_rows($veranoJ);
  $total_verano = $num_veranoF+$num_veranoJ;
?>

<?php $grupo.='"'.$unidad.'",'; ?>
<?php $f_navidad.="$num_navidadF,"; ?>
<?php $j_navidad.="$num_navidadJ,"; ?>
<?php $f_santa.="$num_santaF,"; ?>
<?php $j_santa.="$num_santaJ,"; ?>
<?php $f_verano.="$num_veranoF,"; ?>
<?php $j_verano.="$num_veranoJ,"; ?>

<?php $chart_n++; ?>

<?php
}
?>
                                                
<canvas id="chart_<?php echo $chart_n; ?>_<?php echo $unidad; ?>" width="200" height="80"></canvas>

<script>
    var ctx = document.getElementById('chart_<?php echo $chart_n; ?>_<?php echo $unidad; ?>').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
    labels: [<?php echo $grupo; ?>],
    datasets: [{
      label: '1Tr. No justificadas',
      backgroundColor: window.chartColors.red,
      stack: 'Stack 0',
      data: [
        <?php echo $f_navidad; ?>
      ]
    }, {
      label: '1Tr. Justificadas',
      backgroundColor: window.chartColors.green,
      stack: 'Stack 0',
      data: [
        <?php echo $j_navidad; ?>
      ]
    }, {
      label: '2Tr. No justificadas',
      backgroundColor: window.chartColors.blue,
      stack: 'Stack 1',
      data: [
        <?php echo $f_santa; ?>
      ]
    }, {
      label: '2Tr. Justificadas',
      backgroundColor: window.chartColors.yellow,
      stack: 'Stack 1',
      data: [
        <?php echo $j_santa; ?>
      ]
    }, {
      label: '3Tr. No justificadas',
      backgroundColor: window.chartColors.brown,
      stack: 'Stack 2',
      data: [
        <?php echo $f_verano; ?>
      ]
    }, {
      label: '3Tr. Justificadas',
      backgroundColor: window.chartColors.cyan,
      stack: 'Stack 2',
      data: [
        <?php echo $j_verano; ?>
      ]
    }]
  },
      options: {
        tooltips: {
          mode: 'index',
          intersect: false
        },
        responsive: true,
        scales: {
          xAxes: [{
            stacked: true,
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
  $(document).ready(function() {
    var table = $('.datatable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false,
        
        "lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
        
        "order": [[ 1, "asc" ]],
        
        "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay resultados disponibles.",
                    "infoFiltered": "(filtrado de _MAX_ resultados)",
                    "search": "Buscar: ",
                    "paginate": {
                          "first": "Primera",
                          "next": "Última",
                          "next": "",
                          "previous": ""
                        }
                }
      });
  });
  </script> 

<script>
function espera() {
  document.getElementById("wrap").style.display = '';
  document.getElementById("status-loading").style.display = 'none';        
}
window.onload = espera;
</script>  
</body>
</html>
