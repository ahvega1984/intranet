<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($config['convivencia']['listados_direccion']) && $config['convivencia']['listados_direccion'] == 1) {
	acl_acceso($_SESSION['cargo'], array('1'));
}

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("menu.php");

if(isset($_GET['id'])){$id = $_GET['id'];}
?>
<style>
.table th{
	font-size:0.8em;
	vertical-align:top !important;
}
</style>

<div class="container">

	<div class="page-header">
		<h2 style="display: inline;">Problemas de convivencia <small>Últimos problemas</small></h2>

		<!-- Button trigger modal -->
		<a href="#"class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
			<span class="fas fa-question fa-lg"></span>
		</a>

		<!-- Modal -->
		<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
						<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
					</div>
					<div class="modal-body">
						<p>Esta página presenta un listado de los últimos 300 Problemas de Convivencia que se han
						registrado.</p>
						<p>La tabla con los resultados puede ser ordenada de forma ascendente o descendente pulsando
						con el ratón sobre el nombre de las columnas o las flechas que las acompañan. También admite
						la introducción de criterios selectivos escribiendo datos en el campo de búsqueda que aparece
						en la parte superior derecha de la tabla.</p>
						<p>La columna <strong>NUM.</strong> indica el número total de Problemas registrados para un
						alumno; la columna <strong>CAD.</strong> indica si el Problema ha caducado o no (30 días para
						los Problemas Leves y Graves, 60 días para los Muy Graves); la siguiente columna nos dice si
						el Tutor ha recibido y procesado la Notificación del Problema en su página de inicio. La
						última columna presenta un grupo de iconos con distintas funciones: podemos ver los detalles
						del Problema; acceder al historial de Convivencia de un alumno; y, si procede, podemos editar
						(hasta un máximo de 2 días después de haberlo registrado) o eliminar un Problema de Convivencia.</p>
						<p>El Equipo Directivo tiene una columna con una casilla de verificación que indica si el
						Problema ha sido <strong>Revisado</strong> por Jefatura de Estudios.</p>

						<hr>
						<?php if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']): ?>
						<h5>Sistema de puntos</h5>
						<p>Este sistema se basa en una serie de puntos iniciales que se van perdiendo (o ganando) según la conducta del alumno.
							Aquí es importante contar con una actuación concreta y conocida por todos cuando se pierdan todos los puntos
							(mal comportamiento reiterado y/o grave).</p>

						<p>Cada alumno comienza con
							<strong><?php echo (isset($config['convivencia']['puntos']['total'])) ? $config['convivencia']['puntos']['total'] : '8'; ?> puntos</strong>
							y los va perdiendo según la gravedad del problema de convivencia. Por cada parte leve pierde
							<strong><?php echo (isset($config['convivencia']['puntos']['resta_leves'])) ? $config['convivencia']['puntos']['resta_leves'] : '2'; ?> puntos</strong>,
							por cada parte grave
							<strong><?php echo (isset($config['convivencia']['puntos']['resta_graves'])) ? $config['convivencia']['puntos']['resta_graves'] : '4'; ?> puntos</strong>
							y por cada parte muy grave
							<strong><?php echo (isset($config['convivencia']['puntos']['resta_mgraves'])) ? $config['convivencia']['puntos']['resta_mgraves'] : '6'; ?> puntos</strong>.</p>
						</p>

						<p>Si en una semana no ha registrado ningún problema se
							<strong>incrementa <?php echo (isset($config['convivencia']['puntos']['recupera_semana'])) ? $config['convivencia']['puntos']['recupera_semana'] : '0,15'; ?> puntos</strong>
							y así sucesivamente hasta un <strong>máximo de <?php echo (isset($config['convivencia']['puntos']['maximo'])) ? $config['convivencia']['puntos']['maximo'] : '15'; ?> puntos</strong>.</p>

						<p>Cuando el alumno se reincorpora de una expulsión se
							<strong>recuperan los <?php echo (isset($config['convivencia']['puntos']['total'])) ? $config['convivencia']['puntos']['total'] : '8'; ?> puntos</strong>.</p>

						<p>Cuando el alumno es expulsado al aula de convivencia, tiene la posibilidad de
							<strong>recuperar <?php echo (isset($config['convivencia']['puntos']['recupera_convivencia'])) ? $config['convivencia']['puntos']['recupera_convivencia'] : '2'; ?> puntos</strong>
							si asiste y realiza las tareas.</p>
						<?php endif; ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>

	</div>

  <div class="row">
  <div class="col-sm-12">

<?php
    echo '<div class="text-center" id="t_larga_barra">
    	<span class="lead"><span class="far fa-circle-o-notch fa-spin"></span> Cargando...</span>
    </div>';

    echo "<div id='t_larga' style='display:none' >";
  if (isset($_POST['confirma']) && $_POST['confirma'] == 1) {
  	foreach ($_POST as $clave => $valor){
  		if (strlen($valor) > '0' and $clave !== 'confirma') {
  		$actualiza = "update Fechoria set confirmado = '1' where id = '$clave'";
  		$act = mysqli_query($db_con, $actualiza);
  		}
  	}
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            El registro ha sido confirmado.
          </div></div>';
  }
   if(isset($_GET['borrar']) and $_GET['borrar']=="1"){
$query = "DELETE FROM Fechoria WHERE id = '$id'";
$result = mysqli_query($db_con, $query) or die ("Error en la Consulta: $query. " . mysqli_error($db_con));
echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            El registro ha sido eliminado de la base de datos.
          </div></div>';
}

  mysqli_query($db_con, "create table FechCaduca select id, fecha, TO_DAYS(now()) - TO_DAYS(fecha) as dias from Fechoria order by fecha desc limit 300");
  mysqli_query($db_con, "ALTER TABLE  `FechCaduca` ADD INDEX (  `id` )");
  mysqli_query($db_con, "ALTER TABLE  `FechCaduca` ADD INDEX (  `fecha` )");
  $query0 = "select alma.apellidos, alma.nombre, alma.unidad, alma.claveal, Fechoria.fecha, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.claveal, Fechoria.id, Fechoria.expulsion, Fechoria.expulsionaula, Fechoria.medida, Fechoria.tutoria, recibido, dias, aula_conv, inicio_aula, fin_aula, confirmado, horas from Fechoria, alma, FechCaduca where FechCaduca.id = Fechoria.id and alma.claveal = Fechoria.claveal  order by Fechoria.fecha desc limit 300";
  // echo $query0;
  $result = mysqli_query($db_con, $query0);
 echo "<form action='lfechorias.php' method='post' name='cnf'>
 <div class='table-responsive'>
 <table class='table table-bordered' style='width:auto' align='center'><tr><td class='expulsion-centro'>Expulsión del Centro</td><td class='amonestacion-escrita'>Amonestación escrita</td><td class='expulsion-aula'>Expulsión del aula</td>";
if ($config['mod_convivencia']==1) {
 echo "<td class='aula-convivencia-jefatura'>Aula de convivencia (Jefatura)</td><td class='aula-convivencia-profesor'>Aula de convivencia (Profesor)</td>";
 }
 echo "</tr></table><br />";
		echo '<div class="table-responsive"><table class="table table-striped table-bordered table-vcentered datatable">';
		$fecha1 = (date("d").-date("m").-date("Y"));
        echo "<thead><tr>
		<th></th>
        <th>ALUMNO</th>
		<th nowrap>UNIDAD</th>
		<th>FECHA</th>
		<th>TIPO</th>
		<th>INFORMA</th>
		<th>GRAV.</th>
		<th>NUM.</th>
		<th>CAD.</th>";
		if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']) {
			echo "<th>PUNTOS</th>";
		}
		echo "
		<th></th>
		<th></th>
		<th></th>
		</tr></thead><tbody>";
		$num_row = 0;
   while($row = mysqli_fetch_array($result)) {
		$num_row++;
    $marca = '';
		$apellidos = $row[0];
		$nombre = $row[1];
		$unidad = $row[2];
		$fecha = $row[4];
		$asunto = $row[5];
		$informa = $row[6];
		$grave = $row[7];
		$claveal = $row[8];
		$id = $row[9];
		$expulsion=$row[10];
		$expulsionaula=$row[11];
		$medida=$row[12];
		$tutoria=$row[13];
		$recibido=$row[14];
		$dias=$row[15];
		$aula_conv=$row[16];
		$inicio_aula=$row[17];
		$fin_aula=$row[18];
		$confirmado=$row[19];
		$horas=$row[20];
		if ($confirmado == '1') {
			$marca = " checked = 'checked'";
		}
		if(($dias > 30 and ($grave == 'leve' or $grave == 'grave')) or ($dias > 60 and $grave == 'muy grave'))
		{$caducada="Sí";} else {$caducada="No";}
		$numero = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal
		like '%$claveal%' and Fechoria.fecha >= '".$config['curso_inicio']."' order by Fechoria.fecha");
		$rownumero= mysqli_num_rows($numero);
		$rowcurso = $unidad;
        $rowalumno = $nombre."&nbsp;".$apellidos;
				$bgcolor="class=''";
				if($medida == "Amonestación escrita" and $expulsionaula !== "1" and $expulsion == 0){$bgcolor="class='amonestacion-escrita'";}
				if($expulsionaula == "1"){$bgcolor="class='expulsion-aula'";}

				if($aula_conv > 0){
					if ($horas == "123R456") {
						$bgcolor="class='aula-convivencia-jefatura'";
					}
					else{
						$bgcolor="class='aula-convivencia-profesor'";
					}
				}

				if($expulsion > 0){$bgcolor="class='expulsion-centro'";}
				if($recibido == '1'){$comentarios1="<i class='fas fa-check' data-bs='tooltip'  title='El Tutor ha recibido la notificación.'> </i>";}elseif($recibido == '0'  and ($grave == 'grave' or $grave == 'muy grave' or $expulsionaula == '1' or $expulsion > '0' or $aula_conv > '0')){$comentarios1="<i class='fas fa-exclamation-triangle'  data-bs='tooltip' title='El Tutor NO ha recibido la notificación.'> </i>";}else{$comentarios1="";}
		echo "<tr>
		<td>";
		if ($foto = obtener_foto_alumno($claveal)) {
            echo '<img class="img-thumbnail" src="../../xml/fotos/'.$foto.'" style="width: 64px !important;" alt="">';
        }
        else {
            echo '<span class="img-thumbnail far fa-user fa-fw fa-3x" style="width: 64px !important;"></span>';
        }
		echo "</td>";
		echo "<td>$rowalumno</td>
		<td>$rowcurso</td>
		<td nowrap>$fecha</td>
		<td>$asunto</td>
		<td>".nomprofesor($informa)."</td>";
		if (isset($config['convivencia']['convivencia_seneca']) && $config['convivencia']['convivencia_seneca'] == 1) {
			switch ($grave) {
				case 'leve':
					$titulo_gravedad = "Otras";
					break;
				case 'grave':
					$titulo_gravedad = "Contrarias";
					break;
				case 'muy grave':
					$titulo_gravedad = "Graves";
					break;
			}
			echo "<td $bgcolor>$titulo_gravedad</td>";
			unset($titulo_gravedad);
		}
		else {
			echo "<td $bgcolor>$grave</td>";
		}
		echo "<td><center>$rownumero</center></td>
		<td>$caducada</td>";
		if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']) {
			echo "<td>".sistemaPuntos($claveal)."</td>";
		}
		echo "
		<td nowrap>$comentarios1 $comentarios</td><td nowrap>";

		echo " <a href='detfechorias.php?id=$id&claveal=$claveal'><span class='fas fa-search fa-fw fa-lg' data-bs='tooltip' title='Detalles'></span></a>
		<a href='lfechorias2.php?clave=$claveal'><span class='fas fa-history fa-fw fa-lg' data-bs='tooltip' title='Historial'></span></a>
		";
		$ahora = mktime();
		$tr_f = explode("-",$fecha);
		$antes = mktime(0,0,0,$tr_f[1],$tr_f[2],$tr_f[0])+172800;
        if($_SESSION['profi']==$row[6] or stristr($_SESSION['cargo'],'1') == TRUE){
        	if (stristr($_SESSION['cargo'],'1') == TRUE) {
        		echo "<a href='infechoria.php?id=$id&nombre=$claveal'><span class='far fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>";
        	}
        	elseif($ahora < $antes){
        		echo "<a href='infechoria.php?id=$id&nombre=$claveal'><span class='far fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>";
        	}
        	echo "<a href='lfechorias.php?id= $row[9]&borrar=1' data-bb='confirm-delete'><span class='far fa-trash-alt fa-fw fa-lg' data-bs='tooltip' title='Eliminar'></span></a>";
        }
		echo "</td>
		<td>";
		//echo "$expulsion >  $expulsionaula";
		if (stristr($_SESSION['cargo'],'1') and $grave=="muy grave" and $confirmado=="1") {
			echo "<input type='checkbox' name='confirmado' value='1' $marca data-bs='tooltip' title='El problema de conducta Muy grave ha sido confirmado por el Jefe de Estudios.' />";
		}
		elseif (stristr($_SESSION['cargo'],'1') and $grave=="muy grave" and $confirmado!=="1") {
			echo "<input type='checkbox' name='confirmado' value='' $marca data-bs='tooltip' title='El problema de conducta Muy grave está pendiente de confrimación por parte del Jefe de Estudios. Para completar el proceso de registro, pulsa sobre el icono de edición y actualiza los datos en el formulario.'/>";
		}
		echo "</td></tr>";
        }

        mysqli_query($db_con, "drop table FechCaduca");

        echo "</tbody></table></div></div>";
        echo "<input type='hidden' name='confirma' value='si' />";
        echo "</form>";
		echo "</div></div></div></div></div>";
  ?>
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
