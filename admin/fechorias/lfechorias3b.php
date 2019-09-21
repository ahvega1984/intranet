<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$PLUGIN_DATATABLES = 1;

include ("../../menu.php");
include ("menu.php");
?>
<div class="container">
<div class="page-header">
  <h2>Problemas de convivencia <small> Ranking de Fechorías desde la última expulsión</small></h2>
</div>
<br />

<div class="row">

<div class="col-sm-12">
		<div class="text-center" id="t_larga_barra">
			<span class="lead"><span class="far fa-circle-o-notch fa-spin"></span> Cargando...</span>
		</div>
   <div id='t_larga' style='display:none' >
<?php

		echo "<table class='table table-bordered table-striped table-vcentered datatable'>";
		$fecha1 = (date ( "d" ) . - date ( "m" ) . - date ( "Y" ));
		echo "<thead>
		<th width=\"60\"></th>
		<th>ALUMNO</th>
		<th>CURSO</th>
		<th>TOTAL</th>";
		if (isset($config['convivencia']['convivencia_seneca']) && $config['convivencia']['convivencia_seneca'] == 1) {
			echo "<th>Otras conductas</th>
			<th>Contrarias</th>
			<th nowrap>Graves</th>";
		}
		else {
			echo "<th>Leves</th>
			<th>Graves</th>
			<th nowrap>Muy Graves</th>";
		}
		echo "<th>Expulsion</th>
		<th>Convivencia</th>";
		if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']) {
    	echo "<th>Puntos</th>";
		}
		echo "
		</thead><tbody>";
		mysqli_query($db_con, "create table Fechoria_ult_exp SELECT DISTINCT claveal, Max(fecha) as Ultima FROM Fechoria where date(inicio)>'0000-00-00' GROUP BY claveal" );
		mysqli_query($db_con, "create table Fechoria_post_exp SELECT Fechoria.* FROM Fechoria LEFT JOIN Fechoria_ult_exp ON Fechoria_ult_exp.claveal=Fechoria.claveal WHERE date(Fechoria.fecha)>date(Fechoria_ult_exp.Ultima) OR Fechoria_ult_exp.Ultima IS NULL" );
		mysqli_query($db_con, "create table Fechoria_tem2 SELECT DISTINCT claveal, COUNT( * ) as total FROM Fechoria_post_exp GROUP BY claveal" );
		$num0 = mysqli_query($db_con, "select * from Fechoria_tem2 order by total desc" );
		while ( $num = mysqli_fetch_array ( $num0 ) ) {
			$query0 = "select apellidos, nombre, unidad from alma where claveal = '$num[0]'";
			$result = mysqli_query($db_con, $query0 );
			$row = mysqli_fetch_array ( $result );
			$claveal = $num [0];

			$apellidos = $row [0];
			$nombre = $row [1];
			$unidad = $row [2];
			$rownumero = $num [1];
			$rowcurso = $unidad ;
			$rowalumno = $nombre . "&nbsp;" . $apellidos;
		$lev_sql="select grave from Fechoria_post_exp where grave='leve' and claveal = '$claveal'";
		//echo "por aqui".$lev_sql.aa;
		$lev = mysqli_query($db_con,$lev_sql );
		$leve = mysqli_num_rows($lev);

		$grav = mysqli_query($db_con, "select grave from Fechoria_post_exp where grave='grave' and claveal = '$claveal'");
		$grave = mysqli_num_rows($grav);
		$m_grav = mysqli_query($db_con, "select grave from Fechoria_post_exp where grave='muy grave' and claveal = '$claveal'");
		$m_grave = mysqli_num_rows($m_grav);
		$expulsio = mysqli_query($db_con, "select expulsion from Fechoria where expulsion > '0' and claveal = '$claveal'");
		$expulsion = mysqli_num_rows($expulsio);
		if ($expulsion == '0'){$expulsion='';}
		$conviv = mysqli_query($db_con, "select aula_conv from Fechoria_post_exp where aula_conv > '0' and claveal = '$claveal'");
		$conv = mysqli_num_rows($conviv);
		if ($conv== '0'){$conv='';}
		if(!(empty($apellidos))){
			echo "<tr>
		<td>";

		if ($foto = obtener_foto_alumno($claveal)) {
            echo '<img class="img-thumbnail" src="../../xml/fotos/'.$foto.'" style="width: 64px !important;" alt="">';
        }
        else {
            echo '<span class="img-thumbnail far fa-user fa-fw fa-3x" style="width: 64px !important;"></span>';
		}
		echo "</td>";
		echo "<td nowrap><a href='lfechorias2.php?clave=$claveal'>$rowalumno</a></td>
		<td $bgcolor>$rowcurso</td>
		<td $bgcolor>$rownumero</td>
		<td $bgcolor>$leve</td>
		<td $bgcolor>$grave</td>
		<td $bgcolor>$m_grave</td>
		<td $bgcolor>$expulsion</td>
		<td $bgcolor>$conv</td>";
		if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']) {
    	echo "<td $bgcolor>".sistemaPuntos($claveal)."</td>";
		}
		echo "
		</tr>";
		}
		}
		mysqli_query($db_con, "drop table Fechoria_tem2" );
		echo "</tbody></table>\n";

		mysqli_query($db_con, "drop table Fechoria_tem2" );
		mysqli_query($db_con, "drop table Fechoria_ult_exp" );
		mysqli_query($db_con, "drop table Fechoria_post_exp" );

		?>
		</div>
		</div>
		</div>

        <?php include("../../pie.php");?>
   <script>
   $(document).ready(function() {
     var table = $('.datatable').DataTable({
     		"paging":   true,
         "ordering": true,
         "info":     false,

     		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],

     		"order": [[ 3, "desc" ]],

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
function espera( ) {
        document.getElementById("t_larga").style.display = '';
        document.getElementById("t_larga_barra").style.display = 'none';
}
window.onload = espera;
</script>
  </body>
</html>
