<?php
require('../../bootstrap.php');

include("../../menu.php");
if (isset($_GET['id']))
    $id = $_GET['id'];
elseif (isset($_POST['id']))
    $id = $_POST['id'];
else
    $id="";
if (isset($_GET['profe_baja']))
    $profe_baja = $_GET['profe_baja'];
elseif (isset($_POST['profe_baja']))
    $profe_baja = $_POST['profe_baja'];
else
    $profe_baja="";

$pr_trozos=explode(", ",$profe_baja);		
?>
<br />
<div align="center">
    <div class="page-header">
        <h2>Ausencias del profesorado <small> Profesores ausentes</small></h2>
        <h3 class="muted"><?php echo "$pr_trozos[1] $pr_trozos[0]";?></h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-5">
                <div align="left">
                    <?php
                    echo "<h3 align=center>Datos de la ausencia</h3><br />";
                    echo "<div class='well well-large'>";
                    echo '<table class="table table-striped table-bordered" style="width:100$;">';
	                echo "<thead><th>Inicio</hd><th>Fin</hd><th>Horas</th><th>Archivo adjunto</th>";
	                echo "</thead><tbody><tr>";
	                // Consulta de datos del alumno.
	                $result = mysqli_query($db_con, "select inicio, fin, tareas, id, profesor, horas, archivo from ausencias  where id = '$id' order by inicio" );
	                $row = mysqli_fetch_array ($result);
	                $tar = $row[2];
	                if ($row[5] > "0")
		                $hora = $row[5];
	                else
		                $hora = "Todas";
	                echo "<td nowrap>".cambia_fecha($row[0])."</td><td nowrap>".cambia_fecha($row[1])."</td><td>$hora</td>";
	                if ($row[6] != "") 
                        echo "<td><a href='archivos/$row[6]' target='_blank'><i class='far fa-file'> </i> $row[6]</a></td>";
	                else 
                        echo "<td><em class=\"muted\">No hay archivo adjunto</em></td>";		
	                echo "</tr></table>";
	                echo "<hr>";
	                if (strlen($tar) > '1')
                    {
	                    echo "<legend class='text-warning'>Tareas para los Alumnos durante la Baja</legend>";
	                    echo "<p class='text-info'>$tar</p>";
	                }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <?php	
	        echo "<h3>Horario del Profesor hoy</h3><br />";
	        echo "<table class='table table-striped table-bordered' style='width:auto'>";
	        $result_tramos = mysqli_query($db_con, "SELECT `hora`, `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` <> 'R' AND `hora` <> 'Rn' ORDER BY `horini` ASC");
	        $total_tramos = mysqli_num_rows($result_tramos);

	        echo "<thead>";
	        $n_cols = 0;
	        while ($row_tramos = mysqli_fetch_array($result_tramos)) {
	        	echo "<th>".$row_tramos['hora']."ª Hora<br>".substr($row_tramos['hora_inicio'], 0, 5)." - ".substr($row_tramos['hora_fin'], 0, 5)."</th>";
	        	$n_cols++;
	        }
	        echo "</thead><tbody>";

	            echo "<tr>";

	            $ndia = date ( "w" );
	            
	            $result_tramos = mysqli_query($db_con, "SELECT `hora`, `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` <> 'R' AND `hora` <> 'Rn' ORDER BY `horini` ASC");
	            $total_tramos = mysqli_num_rows($result_tramos);

	            while ($row_tramos = mysqli_fetch_array($result_tramos))
                {
	                echo "<td align='center'>";	
    	            $hor = mysqli_query($db_con, "select a_asig, a_grupo, a_aula, c_asig from horw where prof = '$profe_baja' and dia = '$ndia' and hora = '".$row_tramos['hora']."'");
	                //echo "select a_asig, a_grupo, a_aula from horw where prof = '$profe_baja' and dia = '$ndia' and hora = '$i'<br>";
    	            $hor_asig = mysqli_fetch_array($hor);
    	            if (mysqli_num_rows($hor) > '0')
                    {
	                    echo "Actividad<div style='color:#46a546;'><span style='font-weight:normal;'>$hor_asig[0]</div><br/>";
	                    if (strlen($hor_asig[2] > '1'))
	                        echo "Aula<div style='color:#9d261d'><span style='font-weight:normal;'>$hor_asig[2]</div><br />";

	                    if (strlen($hor_asig[1]) > '1' and $hor_asig[3] != "25")
                        {
		                    $hor2 = mysqli_query($db_con, "select a_grupo from horw where prof = '$profe_baja' and dia = '$ndia' and hora = '".$row_tramos['hora']."'");
		                    echo "Grupos<div style='color:#08c'>";
                            while ($hor_bj = mysqli_fetch_array($hor2))
	                            echo "<span style='font-weight:normal;'>".$hor_bj[0]."</div><br /> ";
	                    }
	                }
	                echo "</td>";
	            }
	            echo "</tr>";
	        echo "</table>";
            ?>
        </div>
    </div>
</div>

<?php include("../../pie.php"); ?>

