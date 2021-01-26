<?php
require('../../bootstrap.php');


$profesor = $_SESSION['profi'];

include("../../menu.php");

if (isset($_GET['menu']) && $_GET['menu'] == 'guardias' && acl_permiso($_SESSION['cargo'], array(1))) {
	include("../guardias/menu.php");
}
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
  <h2>Información sobre Guardias <small> Datos y Estadísticas</small></h2>
</div>
<div class="row">
<div class="well well-large" style="width:750px;margin:auto">
<p class="block-help">
<legend class=" text-warning" align="center">Aclaraciones sobre los datos presentados sobre las Guardias.</legend>
<ul class=" text-info">
<li>Las Guardias de Pasillo presentan las guardias de pasillo totales registradas en el Horario en Séneca. Quedan excluidas las guardias de Biblioteca, las guardias en el Aula de Convivencia y las guardias de Recreo. Si el profesor tiene además guardias de recreo, aparece el texto <b>(R.)</b> al lado del nombre; si el profesor tiene guardias de Biblioteca, aparece <b>(B.)</b>; y si el profesor tiene guardias de Convivencia, aparece <b>(C.)</b>.</li>
<li>Las Guardias de Biblioteca, Aula de Convivencia y Recreo aparecen en gráficos específicos para cada grupo.</li>
<li>Las Guardias en el Aula se refieren a las sustituciones realizadas por los profesores de guardia hasta la fecha. Entre paréntesis aparece el número de guardias que tiene en el horario cada profesor.</li>
</ul>
</p>
</div>
<hr />
<br />
<?php

$profes_tot = mysqli_query($db_con, "select distinct prof from horw");
$profes_total = mysqli_num_rows($profes_tot);

$sql = "SELECT DISTINCT prof, COUNT( * ) AS num
FROM  `horw` 
WHERE c_asig =  '25'
GROUP BY prof
ORDER BY  `prof` ASC ";

$sql_gu = "SELECT DISTINCT prof, COUNT( * ) AS num
FROM  `horw` 
WHERE (c_asig = '25' or c_asig = '353')
GROUP BY prof
ORDER BY  `num` ASC ";

$sql_bib = "SELECT DISTINCT prof, COUNT( * ) AS num
FROM  `horw` 
WHERE c_asig = '26'
GROUP BY prof
ORDER BY  `prof` ASC ";

$sql_conv = "SELECT DISTINCT prof, COUNT( * ) AS num
FROM  `horw` 
WHERE a_asig LIKE  'GUC%'
GROUP BY prof
ORDER BY  `prof` ASC ";

$sql_reg = "SELECT DISTINCT profesor, COUNT( * ) AS numero
FROM  `guardias` where profesor not like ''
GROUP BY profesor
ORDER BY  `numero` DESC ";

$sql_rec = "SELECT prof, count(*) as numero
FROM  `horw` where prof not like '' and c_asig = '353'
group by prof ORDER BY  prof ASC";
?>

<div class="col-sm-6">
<legend align="center" class="text-info">Guardias de Pasillo</legend>

<?php
$chart_n = 0;
$query = mysqli_query($db_con, $sql);
while ($arr = mysqli_fetch_array($query)) {
	
	$bibl="";
	$convi=""; 
	$recr="";	
	
	$biblio = mysqli_query($db_con, "select * from horw where prof = '$arr[0]' and a_asig like 'GUB%'");
	$conviven = mysqli_query($db_con, "select * from horw where prof = '$arr[0]' and a_asig like 'GUC%'");
	$recreo = mysqli_query($db_con, "select * from recreo where profesor = '$arr[0]'");
	
	if (mysqli_num_rows($biblio)>0) { $bibl =  " (B.)";}
	if (mysqli_num_rows($conviven)>0) {$convi =  " (C.)";}
	if (mysqli_num_rows($recreo)>0) { $recr =  " (R.)";}
?>
 <?php 
if ($arr[1]>0) {
	$profe_pasillo.='"'.nomprofesor($arr[0]).' '. $bibl.$convi.$recr.'",';
	$numero.="$arr[1],"; 
}
?>	
<?php	//echo "<tr><td><a href='consulta_profesores.php?profesor=".nomprofesor($arr[0])."''>".nomprofesor($arr[0])."</td><td class='col-sm-2'>$arr[1]  $bibl $convi $recr</td></tr>";
	$num_gu+=$arr[1];
	$num_prof+=1;
} 
?>
<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_pasillo" width="100" height="300"></canvas>
<script>
var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_pasillo");

var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: [
        <?php echo $profe_pasillo; ?>
      ],
      datasets: [{
        backgroundColor: window.chartColors.orange,
        data: [
            <?php echo $numero; ?>
        ]
      }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                position: 'top',
	                ticks: {
	                min: 0,
	                stepSize: 1
	            }
            }]
        },
        events: false,
        legend: {
            display: false
        },
        tooltips: {
            enabled: false
        },
        animation: {
            duration: 0,
            onComplete: function () {
            var chartInstance = this.chart;
            var ctx2 = chartInstance.ctx;
            console.log(chartInstance);
            var height = chartInstance.controller.boxes[0].bottom;
            ctx2.textAlign = "center";
            ctx2.fillStyle = "#FFFFFF";
            Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
              var meta = chartInstance.controller.getDatasetMeta(i);
              Chart.helpers.each(meta.data.forEach(function (bar, index) {
                if(dataset.data[index]>0) ctx2.fillText(dataset.data[index], bar._model.x-12, height - ((height - bar._model.y)-4));
              }),this)
            }),this);                               
            }
        },        
    }
});
</script>

<?php 
$media = substr($num_gu/$num_prof,0,3);

echo '<br /><table class="table table-striped table-bordered" align="center">';
echo "<tr><td class='text-info'><strong>Profesores con Guardias</strong></td><td nowrap class='text-warning'><strong>$num_prof <span class='muted'>($profes_total)</span></strong></td></tr>";
echo "<tr><td class='text-info'><strong>Número de Guardias en total</strong></td><td class='text-warning'><strong>$num_gu</strong></td></tr>";
echo "<tr><td class='text-info'><strong>Media de Guardias por Profesor</strong></td><td class='text-warning'><strong>$media</strong></td></tr>";
echo "</table>";
?>
</div>


<div class="col-sm-6">

<legend align="center" class="text-info">Guardias en las Aulas</legend>

<?php
$query_reg = mysqli_query($db_con, $sql_reg);
while ($arr_reg = mysqli_fetch_array($query_reg)) {
	
	$num_hr=mysqli_query($db_con, "select * from horw where c_asig='25' and prof = '$arr_reg[0]'");
	$num_horw = mysqli_num_rows($num_hr);
	$num_g0=mysqli_query($db_con, "select turno from guardias where profesor = '$arr_reg[0]'");
	$cont = 0;
	while ($reg = mysqli_fetch_array($num_g0))
	{
		if ( $reg[0] == 2 )
			$cont = $cont + 0.5;
		elseif ( $reg[0] == 3 )
			$cont = $cont + 0.5;
		else
			$cont = $cont + 1;
	}

	$profe_aula.='"'.nomprofesor($arr_reg[0]).' ('. $num_horw.')",';
	$numero_aula.="$cont,"; 

//$num_gureg+=$arr_reg[1];
$num_gureg+=$cont;
$num_profreg+=1;
}
?>
<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_aula" width="100" height="320"></canvas>
<script>
var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_aula");

var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: [
        <?php echo $profe_aula; ?>
      ],
      datasets: [{
        backgroundColor: window.chartColors.red,
        data: [
            <?php echo $numero_aula; ?>
        ]
      }]
    },
    options: {
        responsive: true,
        events: false,
        legend: {
            display: false
        },
        tooltips: {
            enabled: false
        },
        animation: {
            duration: 0,
            onComplete: function () {
            var chartInstance = this.chart;
            var ctx2 = chartInstance.ctx;
            console.log(chartInstance);
            var height = chartInstance.controller.boxes[0].bottom;
            ctx2.textAlign = "center";
            ctx2.fillStyle = "#FFFFFF";
            Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
              var meta = chartInstance.controller.getDatasetMeta(i);
              Chart.helpers.each(meta.data.forEach(function (bar, index) {
                if(dataset.data[index]>0) ctx2.fillText(dataset.data[index], bar._model.x-15, height - ((height - bar._model.y)-5));
              }),this)
            }),this);                               
            }
        },
        scales: {
            xAxes: [{
                position: 'top',
	                ticks: {
	                min: 0,
	                stepSize: 1
	            }
            }]
        }
    }
});
</script>

<?php	

$media_reg = substr($num_gureg/$num_profreg,0,4);

echo '<br /><table class="table table-striped table-bordered" align="center">';
echo "<tr><td class='text-info'><strong>Guardias totales en Aulas</strong></td><td class='text-warning'><strong>$num_gureg</strong></td></tr>";
echo "<tr><td class='text-info'><strong>Media de Guardias por Profesor</strong></td><td class='text-warning'><strong>$media_reg</strong></td></tr>";
echo "</table>";
?>

</div>

<div class="col-sm-6">
<legend align="center" class="text-info">Guardias de Biblioteca</legend>
<?php
$query_bib = mysqli_query($db_con, $sql_bib);
while ($arr_bib = mysqli_fetch_array($query_bib)) {
	$profe_biblio.='"'.nomprofesor($arr_bib[0]).'",';
	$numero_biblio.="$arr_bib[1],"; 
}
?>

<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_biblio" width="100" height="60"></canvas>
<script>
var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_biblio");

var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: [
        <?php echo $profe_biblio; ?>
      ],
      datasets: [{
        backgroundColor: window.chartColors.green,
        data: [
            <?php echo $numero_biblio; ?>
        ]
      }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                position: 'top',
	                ticks: {
	                min: 0,
	                stepSize: 1
	            }
            }]
        },
        legend: {
            display: false,
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
</script>

<br />

</div>

<div class="col-sm-6">
<legend align="center" class="text-info">Guardias de Convivencia</legend>

<?php
$query_conv = mysqli_query($db_con, $sql_conv);
while ($arr_conv = mysqli_fetch_array($query_conv)) {
	$profe_conv.='"'.nomprofesor($arr_conv[0]).'",';
	$numero_conv.="$arr_conv[1],"; 
}
?>
<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_conv" width="100" height="80"></canvas>
<script>
var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_conv");

var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: [
        <?php echo $profe_conv; ?>
      ],
      datasets: [{
        backgroundColor: window.chartColors.yellow,
        data: [
            <?php echo $numero_conv; ?>
        ]
      }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                position: 'top',
	                ticks: {
	                min: 0,
	                stepSize: 1
	            }
            }]
        },
        legend: {
            display: false,
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
</script>

<br />

</div>

<div class="col-sm-6">
<legend align="center" class="text-info">Guardias de Recreo</legend>

<?php
$query_rec = mysqli_query($db_con, $sql_rec);
while ($arr_rec = mysqli_fetch_array($query_rec)) {
	$profe_rec.='"'.nomprofesor($arr_rec[0]).'",';
	$numero_rec.="$arr_rec[1],"; 
}
?>
<?php $chart_n++; ?>
<canvas id="chart_<?php echo $chart_n; ?>_biblio" width="100" height="150"></canvas>
<script>
var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_biblio");

var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
      labels: [
        <?php echo $profe_rec; ?>
      ],
      datasets: [{
        backgroundColor: window.chartColors.blue,
        data: [
            <?php echo $numero_rec; ?>
        ]
      }]
    },
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                position: 'top',
	                ticks: {
	                min: 0,
	                stepSize: 1
	            }
            }]
        },
        legend: {
            display: false,
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
</script>
<br>
</div>


</div>
</div>
<?php
include("../../pie.php");
?>
	
</body>
</html>
