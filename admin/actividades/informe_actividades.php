<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
    include('config.php');
}
    include("../../menu.php");
    include("menu.php");

$numCursosAnteriores = 5; //num de años a considerar en la consulta
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
        <h2>Actividades Complementarias y Extraescolares <small> Informe sobre las actividades</small></h2>
    </div>

    <div class="text-center" id="t_larga_barra">
        <span class="lead">Cargando los datos. El proceso puede tardar un poco...</span><br><br><span class="fas fa-spinner fa-spin fa-5x"></span>
    </div>
    <div id='t_larga' style='display:none' >
        <div>
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab1" data-toggle="tab">Actividades por departamento</a></li>
               <li><a href="#tab2" data-toggle="tab">Actividades por Profesor</a></li>
               <li><a href="#tab3" data-toggle="tab">Informe por Grupo</a></li>
            </ul>

            <div class="tab-content" style="padding-bottom: 9px;">

                <div class="tab-pane fade in active" id="tab1">
                   <br /><h3>Actividades por departamento</h3>
                        <br />

                        <div class="tabbable" style="margin-bottom: 18px;">
                            <ul class="nav nav-tabs">
                            <?php
                                $cur = substr($config['curso_inicio'],0,4);
                                for ($b = 0; $b < $numCursosAnteriores; $b++)
                                {
                                    $anio_escolar = $cur - $b;
                                    $haydatos = 0;

                                    if ($b == 0)
                                    {
                                        $db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                                        mysqli_query($db_con,"SET NAMES 'utf8'");
                                        $haydatos = 1;
                                    }

                                    if($b > 0 && ! empty($config['db_host_c'.$anio_escolar]))
                                    {
                                        $db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
                                        mysqli_query($db_con,"SET NAMES 'utf8'");
                                        $haydatos = 1;
                                    }

                                    if($haydatos)
                                    {
                                    ?>
                                        <li<?php echo ($b == 0) ? ' class="active"' : '';?>><a href="#m<?php echo $b+1;?>" data-toggle="tab">Curso <?php echo $anio_escolar."-".($anio_escolar+1);?></a>
                                        </li>
                                        <?php
                                    }
                                }
                            ?>
                            </ul>

                            <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

                                <?php
                                $num="";
                                $cur = substr($config['curso_inicio'],0,4);

                                $fecha_ini = $config['curso_inicio'];
                                $fecha_fin = $config['curso_fin'];

                                for ($i = 0; $i < $numCursosAnteriores; $i++)
                                {
                                    if ($num>0) {
                                    $fecha_ini = date("Y-m-d",strtotime($config['curso_inicio']. "- $num year"));
                                    $fecha_fin = date("Y-m-d",strtotime($config['curso_fin']. "- $num year"));
                                    }
                                    else{    
                                    $fecha_ini = $config['curso_inicio'];
                                    $fecha_fin = $config['curso_fin'];
                                    }

                                    $num += 1;
                                    $num == '1' ? $activ=" active" : $activ='';
                            
                                    $anio_escolar = $cur - $i;
                                    $haydatos = 0;

                                    if ($i == 0)
                                    {
                                        $db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                                        mysqli_query($db_con,"SET NAMES 'utf8'");
                                        $haydatos = 1;
                                    }
                                    
                                    if($i > 0 && ! empty($config['db_host_c'.$anio_escolar]))
                                    {
                                        $db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
                                        mysqli_query($db_con,"SET NAMES 'utf8'");
                                        $haydatos = 1;
                                    }


                                    if($haydatos)
                                    {
                                    ?>
                                    <?php $chart_n = 0; ?>

                                    <div class="tab-pane fade in <?php echo $activ;?>" id="<?php echo "m".$num;?>">
                                    <br /><br />
                                    
                                    <?php
                                    $departamento = '';
                                    $numero = '';
                                    $numero_total = '';

                                    $tot0 = mysqli_query($db_con, "SELECT distinct departamento, count(*) as numero_actividades FROM `calendario` WHERE categoria='2' and nombre not like '%pendientes%' and fechaini >= '".$fecha_ini."' and fechafin <= '".$fecha_fin."' group by departamento ");

                                    $total_tot = mysqli_query($db_con, "SELECT * FROM `calendario` WHERE categoria='2' and nombre not like '%pendientes%' and fechaini >= '".$fecha_ini."' and fechafin <= '".$fecha_fin."'");
                                    $numero_total = mysqli_num_rows($total_tot);
                                    ?>

                                     <?php  while ($total0 = mysqli_fetch_array($tot0)) { ?>
                                        <?php $departamento.='"'.$total0[0].'",'; ?>
                                        <?php $numero.="$total0[1],"; ?>
                                        <?php } ?>

                                        <?php $chart_n++; ?>

                                <canvas id="chart_<?php echo $chart_n; ?>_<?php echo $anio_escolar; ?>_dep" width="200" height="100"></canvas>
                                <script>
                                var ctx = document.getElementById("chart_<?php echo $chart_n; ?>_<?php echo $anio_escolar; ?>_dep");
                                
                                var myChart = new Chart(ctx, {
                                    responsive: true,
                                    type: 'horizontalBar',
                                    data: {
                                      labels: [
                                        <?php echo $departamento; ?>
                                      ],
                                      datasets: [{
                                        backgroundColor: window.chartColors.green,
                                        data: [
                                            <?php echo $numero; ?>
                                        ]
                                      }]
                                    },
                                    options: {
                                        responsive: true,
                                         scales: {
                                            xAxes: [{
                                                position: 'bottom'
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
                                     
                                    <?php
                                    }
                                    ?>
                                    <h3>Total de actividades programadas: <?php echo $numero_total; ?></h3>  
                                </div>
                                <?php } ?> 
                            </div>
                        </div>
                </div>


                <div class="tab-pane fade in" id="tab2">

                    <br /><h3>Información por Profesor</h3>
                    <br />
                            
                    <?php
                    $db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                    $fecha_ini = $config['curso_inicio'];
                    $fecha_fin = $config['curso_fin'];

                        ?>
                        <?php $chart_n = 0; ?>

                        <br /><br />
                        
                        <?php
                        $profes = mysqli_query($db_con,"select nombre from departamentos order by nombre");
                        while ($profeso = mysqli_fetch_array($profes)) {
                            $profesor_dep = $profeso['nombre'];

                            

                            $tot1 = mysqli_query($db_con, "SELECT * FROM `calendario` WHERE profesores like '%$profesor_dep%' and categoria='2' and nombre not like '%pendientes%' and fechaini >= '".$fecha_ini."' and fechafin <= '".$fecha_fin."'");
                            $n_activ = mysqli_num_rows($tot1);  

                            if ($n_activ > 0) {
                            $profesor.='"'.$profesor_dep.'",';
                            $numero_p.="$n_activ,"; 
                                }                          
                            } 

                            ?>

                            <?php $chart_n++; ?>

                        <canvas id="chart_prof" width="200" height="360"></canvas>
                        <script>
                        var ctx = document.getElementById("chart_prof");
                        
                        var myChart = new Chart(ctx, {
                            responsive: true,
                            type: 'horizontalBar',
                            data: {
                              labels: [
                                <?php echo $profesor; ?>
                              ],
                              datasets: [{
                                backgroundColor: window.chartColors.blue,
                                data: [
                                    <?php echo $numero_p; ?>
                                ]
                              }]
                            },
                            options: {
                                responsive: true,
                                 scales: {
                                    xAxes: [{
                                        position: 'top'
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
                            
                            <h3>Total de actividades programadas: <?php echo $numero_total; ?></h3>  
                </div>


                <div class="tab-pane fade in" id="tab3">
                    <br />
                    <h3>Información por Grupo</h3>
                    <br>

                    <?php $chart_n = 0; ?>
                    
                    <?php
                    $unid = mysqli_query($db_con,"select distinct nomunidad from unidades order by idunidad");
                    while ($unidade = mysqli_fetch_array($unid)) {
                        $unidad_activ = $unidade['nomunidad'];

                        $tot2 = mysqli_query($db_con, "SELECT * FROM `calendario` WHERE unidades like '%$unidad_activ%' and categoria='2' and nombre not like '%pendientes%' and fechaini >= '".$fecha_ini."' and fechafin <= '".$fecha_fin."'");
                        $n_activ_uni = mysqli_num_rows($tot2);  

                            if ($n_activ_uni > 0) {
                            $unidad.='"'.$unidad_activ.'",';
                            $numero_g.="$n_activ_uni,"; 
                            $total_actividades+=$numero_g;
                            }                          
                        } 


                        ?>

                        <?php $chart_n++; ?>

                        <canvas id="chart_grupo" width="200" height="200"></canvas>
                        <script>
                        var ctx = document.getElementById("chart_grupo");
                        
                        var myChart = new Chart(ctx, {
                            responsive: true,
                            type: 'horizontalBar',
                            data: {
                              labels: [
                                <?php echo $unidad; ?>
                              ],
                              datasets: [{
                                backgroundColor: window.chartColors.red,
                                data: [
                                    <?php echo $numero_g; ?>
                                ]
                              }]
                            },
                            options: {
                                responsive: true,
                                 scales: {
                                    xAxes: [{
                                        position: 'top'
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
                        <br><br>
                        <?php
                        $total_unidad = mysqli_num_rows(mysqli_query($db_con,"select * from unidades"));
                        $media = substr(($total_actividades/$total_unidad), 0, 4);

                        $grup = mysqli_query($db_con, "SELECT nomunidad from unidades order by idunidad asc");
                        while ($grupos =mysqli_fetch_array($grup)) { $num_uni++;?>
                            <div class="col-md-6">
                                <br>
                            <?php
                            $grupo_activ = $grupos[0];
                            ?>
                            <h4><?php echo $grupo_activ; ?></h4>
                            <?php
                            $unidades++;
                            $num="";
                            $result2 = mysqli_query($db_con, "SELECT DISTINCT nombre, fechaini FROM calendario where categoria= '2' and unidades like '%$grupo_activ%' and fechaini >= '".$fecha_ini."' and fechafin <= '".$fecha_fin."' and nombre not like '%pendientes%' order by fechaini"); 
                            ?>
                            <table class="table table-bordered table-striped">
                            <?php   
                                while($rep = mysqli_fetch_array($result2)){
                            ?>
                            <tr><td class="text-muted"><?php echo $rep[1]; ?></td><td><?php echo $rep[0]; ?></td></tr>
                            <?php
                                $num++;
                                $num_media++;
                                $num_total++;
                                }
                            ?>
                           </table>
                            <h4 class='text-info'>Total: <?php echo $num; ?></h4>
                            <h4 class='text-danger'>Media: <?php echo round($media); ?></h4>
                            <br>
                        </div>
                        <?php } ?>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>
<?php
include("../../pie.php");
?>
<script>
function espera( ) {
        document.getElementById("t_larga").style.display = '';
        document.getElementById("t_larga_barra").style.display = 'none';
}
window.onload = espera;
</script>
