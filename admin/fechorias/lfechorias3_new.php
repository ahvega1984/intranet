<?php
require('../../bootstrap.php');


$PLUGIN_DATATABLES = 1;

include ("../../menu.php");
include ("menu.php");
?>
<div class="container">
<div class="page-header">
  <h2>Problemas de convivencia <small> Ranking de Fechor�as</small></h2>
</div>
<br />

<div class="row">

<div class="col-sm-12">	
		<div class="text-center" id="t_larga_barra">
			<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
		</div>
   <div id='t_larga' style='display:none' >		
<?php

		echo "<table class='table table-bordered table-striped table-vcentered datatable'>";
		$fecha1 = (date ( "d" ) . - date ( "m" ) . - date ( "Y" ));
		echo "<thead>
		<th>ALUMNO</th>
		<th>CURSO</th>
		<th>TOTAL</th>
		<th>Leves</th>
		<th>Graves</th>
		<th nowrap>Muy Graves</th>
		<th>Expulsion</th>
		<th>Convivencia</th>
		</thead><tbody>";
		mysqli_query($db_con, "create table Fechoria_temp SELECT DISTINCT claveal, COUNT( * ) as total FROM Fechoria GROUP BY claveal" );
		$num0 = mysqli_query($db_con, "select * from Fechoria_temp order by total desc" );
		while ( $num = mysqli_fetch_array ( $num0 ) ) {
			$query0 = "select apellidos, nombre, unidad from FALUMNOS where claveal = '$num[0]'";
			$result = mysqli_query($db_con, $query0 );
			$row = mysqli_fetch_array ( $result );
			$claveal = $num [0];
			$apellidos = $row [0];
			$nombre = $row [1];
			$unidad = $row [2];
			$rownumero = $num [1];
			$rowcurso = $unidad ;
			$rowalumno = $nombre . "&nbsp;" . $apellidos;

		$t1 = mysqli_query($db_con,"SELECT fecha FROM festivos WHERE nombre like '%navidad%' limit 1");	
		$t2 = mysqli_query($db_con,"SELECT fecha FROM festivos WHERE nombre like '%santa%' limit 1");
		$tt1 = mysqli_fetch_array($t1);
		$tt2 = mysqli_fetch_array($t2);
		$navidad = $tt1[0];
		$santa = $tt2[0];

		$lev1 = mysqli_query($db_con, "select grave from Fechoria where grave='leve' and claveal = '$claveal' and date(fecha) < '$navidad'");
		$leve1 = mysqli_num_rows($lev1);
		$lev2 = mysqli_query($db_con, "select grave from Fechoria where grave='leve' and claveal = '$claveal' and date(fecha) < '$santa' and date(fecha) > '$navidad'");
		$leve2 = mysqli_num_rows($lev2);
		$lev3 = mysqli_query($db_con, "select grave from Fechoria where grave='leve' and claveal = '$claveal' and date(fecha) > '$santa'");
		$leve3 = mysqli_num_rows($lev3);


		$grav1 = mysqli_query($db_con, "select grave from Fechoria where grave='grave' and claveal = '$claveal' and date(fecha) < '$navidad'");
		$grave1 = mysqli_num_rows($grav1);
		$grav2 = mysqli_query($db_con, "select grave from Fechoria where grave='grave' and claveal = '$claveal' and date(fecha) < '$santa' and date(fecha) > '$navidad'");
		$grave2 = mysqli_num_rows($grav2);
		$grav3 = mysqli_query($db_con, "select grave from Fechoria where grave='grave' and claveal = '$claveal' and date(fecha) > '$santa'");
		$grave3 = mysqli_num_rows($grav3);


		$m_grav1 = mysqli_query($db_con, "select grave from Fechoria where grave='muy grave' and claveal = '$claveal' and date(fecha) < '$navidad'");
		$m_grave1 = mysqli_num_rows($m_grav1);
		$m_grav2 = mysqli_query($db_con, "select grave from Fechoria where grave='muy grave' and claveal = '$claveal' and date(fecha) < '$santa' and date(fecha) > '$navidad'");
		$m_grave2 = mysqli_num_rows($m_grav2);
		$m_grav3 = mysqli_query($db_con, "select grave from Fechoria where grave='muy grave' and claveal = '$claveal' and date(fecha) > '$santa'");
		$m_grave3 = mysqli_num_rows($m_grav3);


		$expulsio1 = mysqli_query($db_con, "select grave from Fechoria where expulsion > '0' and claveal = '$claveal' and date(fecha) < '$navidad'");
		$expulsion1 = mysqli_num_rows($expulsio1);
		$expulsio2 = mysqli_query($db_con, "select grave from Fechoria where expulsion > '0' and claveal = '$claveal' and date(fecha) < '$santa' and date(fecha) > '$navidad'");
		$expulsion2 = mysqli_num_rows($expulsio2);
		$expulsio3 = mysqli_query($db_con, "select grave from Fechoria where expulsion > '0' and claveal = '$claveal' and date(fecha) > '$santa'");
		$expulsion3 = mysqli_num_rows($expulsio3);


		$conviv1 = mysqli_query($db_con, "select grave from Fechoria where aula_conv > '0' and claveal = '$claveal' and date(fecha) < '$navidad'");
		$conv1 = mysqli_num_rows($conviv1);
		$conviv2 = mysqli_query($db_con, "select grave from Fechoria where aula_conv > '0' and claveal = '$claveal' and date(fecha) < '$santa' and date(fecha) > '$navidad'");
		$conv2 = mysqli_num_rows($conviv2);
		$conviv3 = mysqli_query($db_con, "select grave from Fechoria where aula_conv > '0' and claveal = '$claveal' and date(fecha) > '$santa'");
		$conv3 = mysqli_num_rows($conviv3);


/*
		$grav = mysqli_query($db_con, "select grave from Fechoria where grave='grave' and claveal = '$claveal'");
		$grave = mysqli_num_rows($grav);
		$m_grav = mysqli_query($db_con, "select grave from Fechoria where grave='muy grave' and claveal = '$claveal'");
		$m_grave = mysqli_num_rows($m_grav);
		$expulsio = mysqli_query($db_con, "select expulsion from Fechoria where expulsion > '0' and claveal = '$claveal'");
		$expulsion = mysqli_num_rows($expulsio);*/

		for ($i=0; $i < 4; $i++) { 
			if (${expulsion.$i} == '0'){${expulsion.$i}='';}
		}
		
/*		$conviv = mysqli_query($db_con, "select aula_conv from Fechoria where aula_conv > '0' and claveal = '$claveal'");
		$conv = mysqli_num_rows($conviv);*/
		for ($i=0; $i < 4; $i++) { 
			if (${conv.$i} == '0'){${conv.$i}='';}
		}
/*		if ($conv== '0'){$conv='';}
*/		
		if(!(empty($apellidos))){
			echo "<tr>
		<td nowrap>";
		if ($foto = obtener_foto_alumno($claveal)) {
            echo '<img class="img-thumbnail" src="../../xml/fotos/'.$foto.'" style="width: 64px !important;" alt="">';
        }
        else {
            echo '<span class="img-thumbnail fa fa-user fa-fw fa-3x" style="width: 64px !important;"></span>';
        }				
		echo "<a href='lfechorias2.php?clave=$claveal'>$rowalumno</a></td>
		<td $bgcolor>$rowcurso</td>
		<td $bgcolor>$rownumero</td>
		<td $bgcolor>$leve1<br>$leve2<br>$leve3</td>
		<td $bgcolor>$grave</td>
		<td $bgcolor>$m_grave</td>
		<td $bgcolor>$expulsion</td>
		<td $bgcolor>$conv</td>
		</tr>";
		}
		}
		mysqli_query($db_con, "drop table Fechoria_temp" );		
		echo "</tbody></table>\n";
		mysqli_query($db_con, "drop table Fechoria_temp" );
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
     		
     		"order": [[ 2, "desc" ]],
     		
     		"language": {
     		            "lengthMenu": "_MENU_",
     		            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
     		            "info": "P�gina _PAGE_ de _PAGES_",
     		            "infoEmpty": "No hay resultados disponibles.",
     		            "infoFiltered": "(filtrado de _MAX_ resultados)",
     		            "search": "Buscar: ",
     		            "paginate": {
     		                  "first": "Primera",
     		                  "next": "�ltima",
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
