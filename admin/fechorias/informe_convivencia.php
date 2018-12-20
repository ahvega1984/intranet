<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}
    include("../../menu.php");

$numCursosAnteriores = 4; //num de años a considerar en la consulta
?>

<div class="container">

  	<div class="page-header">
    	<h2>Jefatura de Estudios <small> Informe de Problemas de Convivencia</small></h2>
  	</div>

  	<div class="text-center" id="t_larga_barra">
        <span class="lead"><span class="far fa-circle-o-notch fa-spin"></span>Cargando los datos. El proceso puede tardar un poco...</span>
  	</div>
  	<div id='t_larga' style='display:none' >
        <div>
           	<ul class="nav nav-tabs">
               <li class="active"><a href="#tab1" data-toggle="tab">Resumen general</a></li>
               <li><a href="#tab2" data-toggle="tab">Resumen por Nivel</a></li>
               <li><a href="#tab3" data-toggle="tab">Resumen por Grupo</a></li>
               <?php
               if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE)
               {
                   echo '<li><a href="#tab4" data-toggle="tab">Informe por Profesor</a></li>';
               }
               ?>
               <li><a href="#tab5" data-toggle="tab">Informe por Tipo</a></li>
            </ul>

            <div class="tab-content" style="padding-bottom: 9px;">
                <div class="tab-pane fade in active" id="tab1">
                    <br /><h3>Resumen General</h3><br />

                    <?php
                    $cur = substr($config['curso_inicio'],0,4);
                    for ($i = 0; $i < $numCursosAnteriores; $i++)    
                    {
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
                            <h4 class="text-info">Curso <?php echo $anio_escolar; echo "-".($anio_escolar+1);?></h4>
                            <table class="table table-striped" style="width:auto">
                                <tr>
                                <th>Absentismo</th>
                                <th>Convivencia</th>
                                <th>Leves</th>
                                <th>Graves</th>
                                <th>Muy Graves</th>
                                <th>Expulsiones</th>
                                <th>Alumnos Expulsados</th>
                                <th>Expulsión del Aula</th>
                                <th>Acciones Tutoría</th>
                                <th>Informes</th>
                                <th>Comunicaciones</th>
                                </tr>
                                <?php
                                
                                $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minNav, max(fecha) as maxNav from festivos where nombre like '%Navidad%'"));
                                $minNavidad = $consulta['minNav'];
                                $maxNavidad = $consulta['maxNav'];
                                
                                $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minSanta, max(fecha) as maxSanta from festivos where nombre like '%Santa%'"));
                                $minSanta = $consulta['minSanta'];
                                $maxSanta = $consulta['maxSanta'];
                            
                                //echo '[1T desde '.$minNavidad. ' hasta '.$maxNavidad;
                                //echo '][2T desde '.$minSanta. ' hasta '.$maxSanta.']';
                            
                                $SQL = "select count(*) as total from Fechoria where month(fecha) >='09' and fecha <= '$minNavidad'";          
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_conv1 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_conv2 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_conv3 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'leve' and month(fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_leves1 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'leve' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_leves2 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'leve' and fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_leves3 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'grave' and month(fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_graves1 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'grave' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_graves2 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'grave' and fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_graves3 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'muy grave' and month(Fechoria.fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_muygraves1 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'muy grave' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_muygraves2 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where grave = 'muy grave' and fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_muygraves3 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where expulsion > '0' and month(fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsion1 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where expulsion > '0' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsion2 = $result['total'];
                                
                                $SQL = "select count(*) as total from Fechoria where expulsion > '0' and fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsion3 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsion > '0' and month(fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsados1 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsion > '0' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsados2 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsion > '0' and fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsados3 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsionaula = '1' and month(fecha) >='09' and fecha <= '$minNavidad'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsadosaula1 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsionaula = '1' and fecha >= '$maxNavidad' and fecha <= '$minSanta'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsadosaula2 = $result['total'];
                                
                                $SQL = "select count(distinct claveal) as total from Fechoria where expulsionaula = '1' and Fechoria.fecha >='$maxSanta' and fecha <= '".$config['curso_fin']."'";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_expulsadosaula3 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from infotut_alumno where month(F_ENTREV) >='09' and date(F_ENTREV) <= '$minNavidad' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_informes1 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from infotut_alumno where F_ENTREV >= '$maxNavidad' and date(F_ENTREV) <= '$minSanta' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_informes2 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from infotut_alumno where date(F_ENTREV) >='$maxSanta' and F_ENTREV <= '".$config['curso_fin']."' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_informes3 = $result['total'];
                                
                                $chk = mysqli_query($db_con,"select id from tutoria");
                                if (mysqli_num_rows($chk)<=0)
                                  mysqli_query($db_con,"ALTER TABLE `tutoria` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");

                                $SQL = "select count(distinct id) as total from tutoria where month(fecha) >='09' and fecha <= '$minNavidad' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_acciones1 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from tutoria where fecha >= '$maxNavidad' and fecha <= '$minSanta' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_acciones2 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from tutoria where fecha >='$maxSanta' and month(fecha) <= '06' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_acciones3 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from tutoria where causa = 'Faltas de Asistencia' and month(fecha) >='09' and fecha <= '$minNavidad' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_comunica1 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from tutoria where causa = 'Faltas de Asistencia' and fecha >= '$maxNavidad' and fecha <= '$minSanta' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_comunica2 = $result['total'];
                                
                                $SQL = "select count(distinct id) as total from tutoria where causa = 'Faltas de Asistencia' and fecha >='$maxSanta' and month(fecha) <= '06' ";
                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                $num_comunica3 = $result['total'];

                                // Control de Absentismo.

                                $absent = mysqli_query($db_con,"select * from absentismo");

                                $menor_edad=date("Y-m-d", strtotime ("-16years"));   

                                if (mysqli_num_rows($absent)>0) {
                                    $faltas = "select distinct claveal from absentismo where claveal in (select claveal from alma where  STR_TO_DATE( fecha,  '%d/%m/%Y' ) > DATE( '$menor_edad' ) )";
                                    $faltas0 = mysqli_query($db_con, $faltas);
                                    $num_faltas = mysqli_num_rows($faltas0);
                                }
                                else{
                                    $SQL = "select count(distinct t1.claveal) as total from (SELECT claveal, unidad, month(fecha) as mes, count(*) AS numero FROM FALTAS where falta = 'F' and claveal in (select claveal from alma) group by claveal, month(fecha) having numero > 25) as t1";                               
                                    $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                    $num_faltas = $result['total'];
                                }
                               
                               // Fin de control de Absentismo

                                $num_conv = $num_conv1 + $num_conv2 + $num_conv3;
                                $num_leves = $num_leves1 + $num_leves2 + $num_leves3;
                                $num_graves = $num_graves1 + $num_graves2 + $num_graves3;
                                $num_muygraves = $num_muygraves1 + $num_muygraves2 + $num_muygraves3;
                                $num_expulsion = $num_expulsion1 + $num_expulsion2 + $num_expulsion3;
                                $num_expulsados = $num_expulsados1 + $num_expulsados2 + $num_expulsados3;
                                $num_expulsadosaula = $num_expulsadosaula1 + $num_expulsadosaula2 + $num_expulsadosaula3;
                                $num_acciones = $num_acciones1 + $num_acciones2 + $num_acciones3;
                                $num_informes = $num_informes1 + $num_informes2 + $num_informes3;
                                $num_comunica = $num_comunica1 + $num_comunica2 + $num_comunica3;
                                ?>
                                <tr>
                                    <td><?php echo $num_faltas; ?><br /><br /><br />
                                        <hr><strong>Totales:</strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_conv1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_conv2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_conv3; ?>
                                        <hr><strong><?php echo $num_conv; ?></strong>
                                    </td>
                                    <td nowrap>
                                        <span style="color:#abc">1T.</span>  <?php echo $num_leves1; ?><br />
                                        <span style="color:#abc">2T.</span>  <?php echo $num_leves2; ?><br />
                                        <span style="color:#abc">3T.</span>  <?php echo $num_leves3; ?>
                                        <hr><strong><?php echo $num_leves; ?></strong>
                                    </td>
                                    <td nowrap>
                                        <span style="color:#abc">1T.</span> <?php echo $num_graves1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_graves2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_graves3; ?>
                                        <hr><strong><?php echo $num_graves; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_muygraves1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_muygraves2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_muygraves3; ?>
                                        <hr><strong><?php echo $num_muygraves; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsion1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsion2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsion3; ?>
                                        <hr><strong><?php echo $num_expulsion; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsados1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsados2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsados3; ?>
                                        <hr><strong><?php echo $num_expulsados; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsadosaula1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsadosaula2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsadosaula3; ?>
                                        <hr><strong><?php echo $num_expulsadosaula; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_acciones1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_acciones2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_acciones3; ?>
                                        <hr><strong><?php echo $num_acciones; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_informes1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_informes2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_informes3; ?>
                                        <hr><strong><?php echo $num_informes; ?></strong>
                                    </td>
                                    <td>
                                        <span style="color:#abc">1T.</span> <?php echo $num_comunica1; ?><br />
                                        <span style="color:#abc">2T.</span> <?php echo $num_comunica2; ?><br />
                                        <span style="color:#abc">3T.</span> <?php echo $num_comunica3; ?>
                                        <hr><strong><?php echo $num_comunica; ?></strong>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        } //cierre if ($hayDatos)
                    } //cierre del for
                    ?>
                    <hr style="width:950px">
                </div>
                <div class="tab-pane fade in" id="tab2">

                    <br /><h3>Información por Nivel</h3>
                    <br />

                    <div class="tabbable" style="margin-bottom: 18px;">
                        <ul class="nav nav-tabs">
                            <?php
                            $cur = substr($config['curso_inicio'],0,4);
                            for ($a = 0; $a < $numCursosAnteriores; $a++)
                            {
                                $anio_escolar = $cur - $a;
                                $haydatos = 0;

                                if ($a == 0)
                                {
                                    $db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                                    mysqli_query($db_con,"SET NAMES 'utf8'");
                                    $haydatos = 1;
                                }
                                
                                if($a > 0 && ! empty($config['db_host_c'.$anio_escolar]))
                                {
                                    $db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
                                    mysqli_query($db_con,"SET NAMES 'utf8'");
                                    $haydatos = 1;
                                }

                                if($haydatos)
                                {
                            ?>
                                    <li<?php echo ($a == 0) ? ' class="active"' : '';?>><a href="#n<?php echo $a+1;?>" data-toggle="tab">Curso <?php echo $anio_escolar."-".($anio_escolar+1);?></a></li>
                            <?php
                                }
                            }
                            ?>
                        </ul>

                        <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

                            <?php
                            $num="";
                            $cur = substr($config['curso_inicio'],0,4);
                            for ($i = 0; $i < $numCursosAnteriores; $i++)
                            {
                                $num +=1;
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
                                    $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minNav, max(fecha) as maxNav from festivos where nombre like '%Navidad%'"));
                                    $minNavidad = $consulta['minNav'];
                                    $maxNavidad = $consulta['maxNav'];
                                
                                    $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minSanta, max(fecha) as maxSanta from festivos where nombre like '%Santa%'"));
                                    $minSanta = $consulta['minSanta'];
                                    $maxSanta = $consulta['maxSanta'];
                                    ?>
                                    <div class="tab-pane fade in <?php echo $activ;?>" id="<?php echo "n".$num;?>">
                                        <br>
                                        <?php
                                        $nivel0 = "select distinct curso from alma order by curso";
                                        $nivel1 = mysqli_query($db_con, $nivel0);
                                        while($nivel = mysqli_fetch_array($nivel1))
                                        {
                                            $nivel = $nivel[0];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            //echo $SQL."<br>";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_conv1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_conv2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_conv3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'leve' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_leves1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'leve' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_leves2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'leve' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_leves3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'grave' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_graves1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'grave' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_graves2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'grave' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_graves3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'muy grave' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_muygraves1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'muy grave' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_muygraves2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and grave = 'muy grave' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_muygraves3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsion1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsion2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsion3 = $result['total'];
                                            
                                            $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsados1 = $result['total'];
                                            
                                            $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsados2 = $result['total'];
                                            
                                            $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsion > '0' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsados3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsionaula = '1' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsadosaula1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsionaula = '1' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsadosaula2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel'  and expulsionaula = '1' and Fechoria.fecha >='$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_expulsadosaula3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and month(F_ENTREV) >='09' and date(F_ENTREV) <= '$minNavidad'";
                                            //echo $SQL."<br>";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_informes1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and F_ENTREV >= '$maxNavidad' and date(F_ENTREV) <= '$minSanta'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_informes2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and date(F_ENTREV) >= '$maxSanta' and F_ENTREV <= '".$config['curso_fin']."'";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_informes3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and month(tutoria.fecha) >='09' and date(tutoria.fecha) <= '$minNavidad' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_acciones1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.fecha >= '$maxNavidad' and date(tutoria.fecha) <= '$minSanta' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_acciones2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and date(tutoria.fecha) >= '$maxSanta' and tutoria.fecha <= '".$config['curso_fin']."' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_acciones3 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and causa = 'Faltas de Asistencia' and month(tutoria.fecha) >='09' and date(tutoria.fecha) <= '$minNavidad' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_comunica1 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and causa = 'Faltas de Asistencia' and tutoria.fecha >= '$maxNavidad' and date(tutoria.fecha) <= '$minSanta' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_comunica2 = $result['total'];
                                            
                                            $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and causa = 'Faltas de Asistencia' and date(tutoria.fecha) >= '$maxSanta' and tutoria.fecha <= '".$config['curso_fin']."' ";
                                            $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                            $num_comunica3 = $result['total'];
                                            

                                            if (mysqli_num_rows($absent)>0) {
                                                $faltas = "select distinct claveal from absentismo where claveal in (select claveal from alma where  STR_TO_DATE( fecha,  '%d/%m/%Y' ) > DATE( '$menor_edad' ) and curso = '$nivel')";
                                                $faltas0 = mysqli_query($db_con, $faltas);
                                                $num_faltas = mysqli_num_rows($faltas0);
                                            }
                                            else{
                                                $SQL = "select count(distinct t1.claveal) as total from (select f.claveal, month(fecha) as mes, count(*) as numero from FALTAS f, unidades u, cursos c where c.idcurso = u.idcurso and f.unidad = u.nomunidad and f.falta = 'F' and c.nomcurso = '$nivel' group by f.claveal, mes having numero > 25) as t1";                   
                                                $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                                                $num_faltas = $result['total'];
                                            }
                                  
                                            $num_conv = $num_conv1 + $num_conv2 + $num_conv3;
                                            $num_leves = $num_leves1 + $num_leves2 + $num_leves3;
                                            $num_graves = $num_graves1 + $num_graves2 + $num_graves3;
                                            $num_muygraves = $num_muygraves1 + $num_muygraves2 + $num_muygraves3;
                                            $num_expulsion = $num_expulsion1 + $num_expulsion2 + $num_expulsion3;
                                            $num_expulsados = $num_expulsados1 + $num_expulsados2 + $num_expulsados3;
                                            $num_expulsadosaula = $num_expulsadosaula1 + $num_expulsadosaula2 + $num_expulsadosaula3;
                                            $num_acciones = $num_acciones1 + $num_acciones2 + $num_acciones3;
                                            $num_informes = $num_informes1 + $num_informes2 + $num_informes3;
                                            $num_comunica = $num_comunica1 + $num_comunica2 + $num_comunica3;
                                            ?>
                                            <h4 class="badge badge-success"><?php echo $nivel; ?></h4>
                                            <br>
                                            <table class="table table-striped" style="width:auto">
                                                <tr>
                                                    <th>Absentismo</th>
                                                    <th>Convivencia</th>
                                                    <th>Leves</th>
                                                    <th>Graves</th>
                                                    <th>Muy Graves</th>
                                                    <th>Expulsiones</th>
                                                    <th>Alumnos Expulsados</th>
                                                    <th>Expulsi&oacute;n del Aula</th>
                                                    <th>Acciones</th>
                                                    <th>Informes</th>
                                                    <th>Comunicaciones</th>
                                                </tr>
                                                
                                                <tr>
                                                    <td><?php echo $num_faltas; ?><br /><br /><br />
                                                        <hr><strong>Totales:</strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_conv1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_conv2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_conv3; ?>
                                                        <hr><strong><?php echo $num_conv; ?></strong>
                                                    </td>
                                                    <td nowrap>
                                                        <span style="color:#abc">1T.</span>  <?php echo $num_leves1; ?><br />
                                                        <span style="color:#abc">2T.</span>  <?php echo $num_leves2; ?><br />
                                                        <span style="color:#abc">3T.</span>  <?php echo $num_leves3; ?>
                                                        <hr><strong><?php echo $num_leves; ?></strong>
                                                    </td>
                                                    <td nowrap>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_graves1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_graves2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_graves3; ?>
                                                        <hr><strong><?php echo $num_graves; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_muygraves1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_muygraves2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_muygraves3; ?>
                                                        <hr><strong><?php echo $num_muygraves; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsion1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsion2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsion3; ?>
                                                        <hr><strong><?php echo $num_expulsion; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsados1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsados2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsados3; ?>
                                                        <hr><strong><?php echo $num_expulsados; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_expulsadosaula1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_expulsadosaula2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_expulsadosaula3; ?>
                                                        <hr><strong><?php echo $num_expulsadosaula; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_acciones1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_acciones2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_acciones3; ?>
                                                        <hr><strong><?php echo $num_acciones; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_informes1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_informes2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_informes3; ?>
                                                        <hr><strong><?php echo $num_informes; ?></strong>
                                                    </td>
                                                    <td>
                                                        <span style="color:#abc">1T.</span> <?php echo $num_comunica1; ?><br />
                                                        <span style="color:#abc">2T.</span> <?php echo $num_comunica2; ?><br />
                                                        <span style="color:#abc">3T.</span> <?php echo $num_comunica3; ?>
                                                        <hr><strong><?php echo $num_comunica; ?></strong>
                                                    </td>
                                                </tr>
                                            </table>
                                            <hr>
                                        <?php
                                        }
                                        ?>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <hr style="width:950px">
                </div>
                <div class="tab-pane fade in" id="tab3">
                    <br />
                    <h3>Información por Grupo</h3>
                    <br>
                    <h4 class="text-info">Curso <?php echo $config['curso_actual']; ?></h4>
                    <?php
                    $db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                    mysqli_query($db_con,"SET NAMES 'utf8'");
                    $cursos0 = "select distinct curso, unidad from alma order by curso";
                    $cursos1 = mysqli_query($db_con, $cursos0);
                    
                    $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minNav, max(fecha) as maxNav from festivos where nombre like '%Navidad%'"));
                    $minNavidad = $consulta['minNav'];
                    $maxNavidad = $consulta['maxNav'];
                                
                    $consulta = mysqli_fetch_assoc(mysqli_query($db_con, "select min(fecha) as minSanta, max(fecha) as maxSanta from festivos where nombre like '%Santa%'"));
                    $minSanta = $consulta['minSanta'];
                    $maxSanta = $consulta['maxSanta'];
                    
                    while($cursos = mysqli_fetch_array($cursos1))
                    {
                        $nivel = $cursos[0];
                        $grupo = $cursos[1];
                        $unidad = $cursos[0]."-".$cursos[1];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_conv1 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_conv2 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_conv3 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'leve' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_leves1 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'leve' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_leves2 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'leve' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_leves3 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'grave' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_graves1 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'grave' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_graves2 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'grave' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_graves3 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'muy grave' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_muygraves1 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'muy grave' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_muygraves2 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and grave = 'muy grave' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_muygraves3 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsion1 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsion2 = $result['total'];
                        
                        $SQL = "select count(*) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsion3 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados1 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados2 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados3 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados1 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados2 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsion > '0' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsados3 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsionaula = '1' and month(Fechoria.fecha) >='09' and Fechoria.fecha <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsadosaula1 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsionaula = '1' and Fechoria.fecha >= '$maxNavidad' and Fechoria.fecha <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsadosaula2 = $result['total'];
                        
                        $SQL = "select count(distinct Fechoria.claveal) as total from Fechoria inner join alma on alma.claveal = Fechoria.claveal where alma.curso = '$nivel' and unidad = '$grupo' and expulsionaula = '1' and Fechoria.fecha >= '$maxSanta' and Fechoria.fecha <= '".$config['curso_fin']."'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_expulsadosaula3 = $result['total'];
                        
                        $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and infotut_alumno.unidad = '$grupo' and month(F_ENTREV) >='09' and date(F_ENTREV) <= '$minNavidad'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_informes1 = $result['total'];
                        
                        $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and infotut_alumno.unidad = '$grupo' and F_ENTREV >= '$maxNavidad' and date(F_ENTREV) <= '$minSanta'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_informes2 = $result['total'];
                        
                        $SQL = "select count(*) as total from infotut_alumno inner join alma on alma.claveal = infotut_alumno.claveal where curso = '$nivel' and infotut_alumno.unidad = '$grupo' and date(F_ENTREV) >= '$maxSanta' and month(F_ENTREV) >= '06'";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_informes3 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and month(tutoria.fecha) >='09' and date(tutoria.fecha) <= '$minNavidad' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_acciones1 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and tutoria.fecha >= '$maxNavidad' and date(tutoria.fecha) <= '$minSanta' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_acciones2 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and date(tutoria.fecha) >= '$maxSanta' and tutoria.fecha <= '".$config['curso_fin']."' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_acciones3 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and causa = 'Faltas de Asistencia' and month(tutoria.fecha) >='09' and date(tutoria.fecha) <= '$minNavidad' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_comunica1 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and causa = 'Faltas de Asistencia' and tutoria.fecha >= '$maxNavidad' and date(tutoria.fecha) <= '$minSanta' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_comunica2 = $result['total'];
                        
                        $SQL = "select count(*) as total from tutoria inner join alma on tutoria.claveal = alma.claveal where curso = '$nivel' and tutoria.unidad = '$grupo' and causa = 'Faltas de Asistencia' and date(tutoria.fecha) >= '$maxSanta' and tutoria.fecha <= '".$config['curso_fin']."' ";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_comunica3 = $result['total'];

                        /*
                        $faltas = "select count(distinct absentismo.claveal) as total from absentismo inner join alma on alma.claveal = absentismo.claveal where curso = '$nivel' and absentismo.unidad = '$grupo'";
                        $faltas0 = mysqli_fetch_assoc(mysqli_query($db_con, $faltas));
                        $num_faltas = $result['total'];
                        */
                        $SQL = "select count(distinct t1.claveal) as total from (SELECT claveal, unidad, month(fecha) as mes, count(*) AS numero FROM FALTAS where falta = 'F' group by claveal, mes having numero > 25 and unidad = '$grupo') as t1";
                        $result = mysqli_fetch_assoc(mysqli_query($db_con, $SQL));
                        $num_faltas = $result['total'];
                        
                        $num_conv = $num_conv1 + $num_conv2 + $num_conv3;
                        $num_leves = $num_leves1 + $num_leves2 + $num_leves3;
                        $num_graves = $num_graves1 + $num_graves2 + $num_graves3;
                        $num_muygraves = $num_muygraves1 + $num_muygraves2 + $num_muygraves3;
                        $num_expulsion = $num_expulsion1 + $num_expulsion2 + $num_expulsion3;
                        $num_expulsados = $num_expulsados1 + $num_expulsados2 + $num_expulsados3;
                        $num_expulsadosaula = $num_expulsadosaula1 + $num_expulsadosaula2 + $num_expulsadosaula3;
                        $num_acciones = $num_acciones1 + $num_acciones2 + $num_acciones3;
                        $num_informes = $num_informes1 + $num_informes2 + $num_informes3;
                        $num_comunica = $num_comunica1 + $num_comunica2 + $num_comunica3;
                    ?>
                        <h4  class="badge badge-info"><?php echo $unidad;?></h4>
                        <br />
                        <table class="table table-striped" style="width:auto">
                            <tr>
                                <th>Absentismo</th>
                                <th>Convivencia</th>
                                <th>Leves</th>
                                <th>Graves</th>
                                <th>Muy Graves</th>
                                <th>Expulsiones</th>
                                <th>Alumnos Expulsados</th>
                                <th>Expulsi&oacute;n del Aula</th>
                                <th>Acciones</th>
                                <th>Informes</th>
                                <th>Comunicaciones</th>
                            </tr>
                            <tr>
                                <td><?php echo $num_faltas; ?><br /><br /><br />
                                    <hr><strong>Totales:</strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_conv1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_conv2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_conv3; ?>
                                    <hr><strong><?php echo $num_conv; ?></strong>
                                </td>
                                <td nowrap>
                                    <span style="color:#abc">1T.</span>  <?php echo $num_leves1; ?><br />
                                    <span style="color:#abc">2T.</span>  <?php echo $num_leves2; ?><br />
                                    <span style="color:#abc">3T.</span>  <?php echo $num_leves3; ?>
                                    <hr><strong><?php echo $num_leves; ?></strong>
                                </td>
                                <td nowrap>
                                    <span style="color:#abc">1T.</span> <?php echo $num_graves1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_graves2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_graves3; ?>
                                    <hr><strong><?php echo $num_graves; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_muygraves1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_muygraves2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_muygraves3; ?>
                                    <hr><strong><?php echo $num_muygraves; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_expulsion1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_expulsion2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_expulsion3; ?>
                                    <hr><strong><?php echo $num_expulsion; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_expulsados1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_expulsados2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_expulsados3; ?>
                                    <hr><strong><?php echo $num_expulsados; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_expulsadosaula1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_expulsadosaula2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_expulsadosaula3; ?>
                                    <hr><strong><?php echo $num_expulsadosaula; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_acciones1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_acciones2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_acciones3; ?>
                                    <hr><strong><?php echo $num_acciones; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_informes1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_informes2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_informes3; ?>
                                    <hr><strong><?php echo $num_informes; ?></strong>
                                </td>
                                <td>
                                    <span style="color:#abc">1T.</span> <?php echo $num_comunica1; ?><br />
                                    <span style="color:#abc">2T.</span> <?php echo $num_comunica2; ?><br />
                                    <span style="color:#abc">3T.</span> <?php echo $num_comunica3; ?>
                                    <hr><strong><?php echo $num_comunica; ?></strong>
                                </td>
                            </tr>
                        </table>

                        <hr>
                        <br />
                        <?php
                        /*$tabla = 'tmp_'.$grupo;
                        $temp = mysqli_query($db_con, "CREATE TEMPORARY TABLE `$tabla` SELECT Fechoria.asunto FROM Fechoria, alma WHERE Fechoria.claveal = alma.claveal and alma.unidad = '$grupo'");
                        $ini0 = mysqli_query($db_con, "SELECT distinct asunto, COUNT(*) FROM  `$tabla` group by asunto");
                        */
                        $ini0 = mysqli_query($db_con, "SELECT f.asunto, count(*) as total FROM Fechoria f, alma a WHERE f.claveal = a.claveal and a.unidad = '$grupo' group by asunto order by total desc");
                            
                        if (mysqli_num_rows($ini0)):
                            ?>
                            <table class="table table-striped" align="left" style="width:800px">
                                <tr>
                                    <th>Tipo de Problema</th>
                                    <th>Número</th>
                                </tr>
                                <?php
                                while ($ini = mysqli_fetch_array($ini0))
                                {
                                ?>
                                    <tr>
                                        <td><?php  echo $ini[0];?></td>
                                        <td><?php  echo $ini[1];?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        <?php
                        endif;
                        echo '<hr style="width:800px"><br />';
                        //mysqli_query($db_con, "DROP TABLE `$tabla`");
                }
                        ?>
                </div>

                <?php
                if(stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE)
                {
                ?>
                    <div class="tab-pane fade in" id="tab4">

                        <br /><h3>Información por Profesor</h3>
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
                                for ($i = 0; $i < $numCursosAnteriores; $i++)
                                {
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
                                        <div class="tab-pane fade in <?php echo $activ;?>" id="<?php echo "m".$num;?>">
                                        <br /><br />
                                        <table class="table table-bordered table-striped table-hover" style="width:auto">
                                            <thead>
                                                <tr>
                                                    <th>Profesor</th><th width="62">Número</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                            $tot0 = '';
                                            //$tot1 = mysqli_query($db_con, "create table fech_temp select informa, count(*) as numeros from Fechoria group by informa");
                                            //$tot0 = mysqli_query($db_con, "select informa, numeros from fech_temp order by numeros desc");
                                        
                                            $tot0 = mysqli_query($db_con, "select informa, count(*) as numeros from Fechoria group by informa order by numeros desc");
                                        
                                            while ($total0 = mysqli_fetch_array($tot0))
                                            {
                                    ?>
                                                <tr>
                                                    <td><?php  echo nomprofesor($total0[0]);?></td>
                                                    <td><?php  echo $total0[1];?></td>
                                                </tr>
                                    <?php
                                            }
                                        ?>
                                            </tbody>
                                        </table>
                                        <?php
                                        //mysqli_query($db_con, "drop table fech_temp");
                                    }
                                    ?>
                                        </div>
                                    <?php
                                }
                    	?>
                    	</div>
                        </div>
                    </div>
                <?php
                }
                ?>	
                <div class="tab-pane fade in" id="tab5">
                       	<br /><h3>Informe por Tipo de problema</h3><br />
                        	<div class="tabbable" style="margin-bottom: 18px;">
                            	<ul class="nav nav-tabs">
                                	<?php
                                	$cur = substr($config['curso_inicio'],0,4);
                                	for ($c = 0; $c < $numCursosAnteriores; $c++)
                                	{
                                    	$anio_escolar = $cur - $c;
                                    	$haydatos = 0;

                                        if ($c == 0)
                                    	{
                                        	$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die('error');
                                        	mysqli_query($db_con,"SET NAMES 'utf8'");
                                        	$haydatos = 1;
                                    	}

                                    	if($c > 0 && ! empty($config['db_host_c'.$anio_escolar]))
                                    	{
                                        	$db_con = mysqli_connect($config['db_host_c'.$anio_escolar], $config['db_user_c'.$anio_escolar], $config['db_pass_c'.$anio_escolar], $config['db_name_c'.$anio_escolar]);
                                        	mysqli_query($db_con,"SET NAMES 'utf8'");
                                        	$haydatos = 1;
                                    	}

                                    	if($haydatos)
                                    	{
                                    	?>
                                        	<li<?php echo ($c == 0) ? ' class="active"' : '';?>><a href="#p<?php echo $c+1;?>" data-toggle="tab">Curso <?php echo $anio_escolar."-".($anio_escolar+1);?></a></li>
                                    	<?php
                                    	}
                                	}
                                ?>
                            	</ul>

                                <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

                                <?php
                                $num="";
                                $cur = substr($config['curso_inicio'],0,4);
                                for ($i = 0; $i < $numCursosAnteriores; $i++)
                                {
                                    $num+=1;
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
                                        <div class="tab-pane fade in <?php echo $activ;?>" id="<?php echo "p".$num;?>">
                                            <br /><br />
                                            <table class="table table-bordered table-striped table-hover" style="width:auto">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo de Problema</th>
                                                        <th width="62">Número</th>
                                                        <th width="72">Gravedad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $tot = '';
                                                    $tot = mysqli_query($db_con, "select asunto, count(*) as total, grave from Fechoria group by grave, asunto order by total desc");
                                                    while ($total = mysqli_fetch_array($tot))
                                                    {
                                                ?>
                                                        <tr>
                                                            <td><?php  echo $total[0];?></td>
                                                            <td><?php  echo $total[1];?></td>
                                                            <td><?php  echo $total[2];?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                            </table>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                            </div>
                </div>   
            </div>
        </div>
    </div>
</div>
<?php
$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
mysqli_query($db_con,"SET NAMES 'utf8'");
include("../../pie.php");?>
<script>
function espera( ) {
        document.getElementById("t_larga").style.display = '';
        document.getElementById("t_larga_barra").style.display = 'none';
}
window.onload = espera;
</script>
