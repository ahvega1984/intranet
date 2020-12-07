<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

$tutor = $_SESSION['profi'];


include("../../menu.php");
include("menu.php");
?>
	<div class="container">

		<div class="page-header">
			<h2 style="display: inline;">Problemas de convivencia <small> Informe personal del Problema</small></h2>

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
							<p>Esta página tiene varias funciones. En primer lugar, ofrece información detallada de un
							problema de convivencia registrado por un Profesor. Presenta también datos numéricos sobre
							los problemas y tipos de problema del alumno. En la parte inferior tenemos una tabla donde
							se recoge el historial delictivo del alumno.</p>
							<p>En la parte derecha nos encontramos, si pertenecemos al Equipo directivo, un par de
							formularios para expulsar al alumno del Centro o expulsarlo al Aula de Convivencia una serie
							de horas o días. La fecha de la expulsión no debe ser inmediata, considerando que los
							Profesores del Equipo educativo del alumno que va a ser expulsado necesitarán algún tiempo
							para rellenar su Informe de Tareas de tal modo que éste trabaje durante su ausencia.</p>
							<p>También nos encontramos una serie de botones para imprimir partes oficiales relacionados
							con el problema registrado, en caso de que necesitemos hacerlo. Generan documentos oficiales
							preparados para ser enviados a los Padres del alumno, por lo que su uso está limitado a
							Tutores y Equipo directivo.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
						</div>
					</div>
				</div>
			</div>

		</div>

<?php
if(!($_POST['id'])){$id = $_GET['id'];}else{$id = $_POST['id'];}
if(!($_POST['claveal'])){$claveal = $_GET['claveal'];}else{$claveal = $_POST['claveal'];}
if (isset($_POST['expulsion'])) { $expulsion = $_POST['expulsion']; }
if (isset($_POST['inicio'])) { $inicio = $_POST['inicio']; }
if (isset($_POST['fin'])) { $fin = $_POST['fin']; }
if (isset($_POST['mens_movil'])) { $mens_movil = $_POST['mens_movil']; }
if (isset($_POST['submit'])) { $submit = $_POST['submit']; }
if (isset($_POST['convivencia'])) { $convivencia = $_POST['convivencia']; }
if (isset($_POST['horas'])) { $horas = $_POST['horas']; }
if (isset($_POST['fechainicio'])) { $fechainicio = $_POST['fechainicio']; }
if (isset($_POST['fechafin'])) { $fechafin = $_POST['fechafin']; }
if (isset($_POST['tareas'])) { $tareas = $_POST['tareas']; }
if (isset($_POST['tareas_exp'])) { $tareas_exp = $_POST['tareas_exp']; }
if (isset($_POST['imprimir4'])) { $imprimir4 = $_POST['imprimir4']; }
if (isset($_POST['imprimir'])) { $imprimir = $_POST['imprimir']; }
if (isset($_POST['imprimir5'])) { $imprimir5 = $_POST['imprimir5']; }
if (isset($_POST['imprimir2'])) { $imprimir2 = $_POST['imprimir2']; }
if (isset($_POST['imprimir3'])) { $imprimir3 = $_POST['imprimir3']; }
if (isset($_POST['inicio_aula'])) { $inicio_aula = $_POST['inicio_aula']; }
if (isset($_POST['fin_aula'])) { $fin_aula = $_POST['fin_aula']; }
if (isset($_POST['convivencia'])) { $convivencia = $_POST['convivencia']; }

include("expulsiones.php");
if (strlen($mensaje)>"0") {
echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCIÓN:</h5>'.
            $mensaje.'
          </div></div>';
}
$result = mysqli_query($db_con, "select alma.apellidos, alma.nombre, alma.unidad, alma.claveal, alma.fecha AS fechancto, Fechoria.fecha, Fechoria.notas, Fechoria.asunto, Fechoria.informa, Fechoria.grave, Fechoria.medida, listafechorias.medidas2, Fechoria.expulsion, Fechoria.tutoria, Fechoria.inicio, Fechoria.fin, aula_conv, inicio_aula, fin_aula, Fechoria.horas, Fechoria.claveal, Fechoria.adjunto from Fechoria, alma, listafechorias where Fechoria.claveal = alma.claveal and listafechorias.fechoria = Fechoria.asunto  and Fechoria.id = '$id' order by Fechoria.fecha DESC");
  if ($row = mysqli_fetch_array($result))
        {
		$apellidos = $row[0];
		$nombre = $row[1];
		$unidad = $row[2];
    $fechancto = $row[4];
		$fecha = $row[5];
		$notas = $row[6];
		$asunto = $row[7];
		$informa = $row[8];
		$grave = $row[9];
		$medida = $row[10];
		$medidas2 = $row[11];
		$expulsion = $row[12];
		$tutoria = $row[13];
		$inicio = $row[14];
		$fin = $row[15];
		$convivencia = $row[16];
		$inicio_aula = $row[17];
		$fin_aula = $row[18];
		$horas = $row[19];
		$adjunto = $row[21];

    if (!$claveal) {
      $claveal = $row[20];
    }

 	if($inicio){ $inicio1 = explode("-",$inicio); $inicio = $inicio1[2] . "-" . $inicio1[1] ."-" . $inicio1[0];}
    if($fin){ $fin1 = explode("-",$fin); $fin = $fin1[2] . "-" . $fin1[1] ."-" . $fin1[0];}
	 if($inicio_aula){ $inicio1 = explode("-",$inicio_aula); $inicio_aula = $inicio1[2] . "-" . $inicio1[1] ."-" . $inicio1[0];}
    if($fin_aula){ $fin1 = explode("-",$fin_aula); $fin_aula = $fin1[2] . "-" . $fin1[1] ."-" . $fin1[0];}
		}
		$numero = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal
		like '%$claveal%' and Fechoria.fecha >= '2006-09-15' order by Fechoria.fecha");
		$numerototal= mysqli_num_rows($numero);
		$numerograves0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal
		like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and grave = 'grave' order by Fechoria.fecha");
		$numerograves= mysqli_num_rows($numerograves0);
		$numeromuygraves0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal
		like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and grave = 'muy grave' order by Fechoria.fecha");
		$numeromuygraves= mysqli_num_rows($numeromuygraves0);
		$numeroexpulsiones0 = mysqli_query($db_con, "select Fechoria.claveal from Fechoria where Fechoria.claveal
		like '%$claveal%' and Fechoria.fecha >= '2006-09-15' and expulsion >= '1' order by Fechoria.fecha");
		$numeroexpulsiones= mysqli_num_rows($numeroexpulsiones0);
?>
<legend align="center">
  <?php echo "$nombre $apellidos ($unidad)";?>
  </legend>
  <br />
<div class="row">
  <div class="col-sm-7">
      <div class="well well-large">
        <?php
        if ($foto = obtener_foto_alumno($claveal)) {
            echo '<img class="img-thumbnail" src="../../xml/fotos/'.$foto.'" style="width: 64px !important;" alt="">';
        }
        else {
            echo '<span class="img-thumbnail far fa-user fa-fw fa-3x" style="width: 64px !important;"></span>';
        }
        ?>
        <table class="table table-striped">
          <tr>
            <th colspan="5"><h4>Información detallada sobre el Problema</h4></th>
          </tr>
          <tr>
            <th>NOMBRE</th>
            <td colspan="4"><?php echo $nombre." ".$apellidos; ?>
            </td>
          </tr>
          <tr>
            <th>GRUPO</th>
            <td colspan="4"><?php echo $unidad; ?></td>
          </tr>
          <tr>
            <th>FECHA NCTO.</th>
            <td colspan="4"><?php echo $fechancto; ?></td>
          </tr>
          <tr style="border-top: 2px solid #2c3e50;">
            <th>FECHA</th>
            <td colspan="4"><?php echo $fecha; ?></td>
          </tr>
          <tr>
            <th>OBSERVACIONES</th>
            <td colspan="4"><?php echo $notas; ?></td>
          </tr>
					<?php if (isset($adjunto) && ! empty($adjunto)): ?>
					<tr>
						<th>ADJUNTO</th>
						<td colspan="4" style="word-wrap: break-word;"><a href="//<?php echo $config['dominio']; ?>/intranet/lib/obtenerAdjunto.php?mod=convivencia&file=<?php echo $adjunto; ?>" target="_blank"><?php echo $adjunto; ?></a></td>
					</tr>
					<?php endif; ?>
          <tr>
            <th>ASUNTO</th>
            <td colspan="4"><?php echo $asunto; ?></td>
          </tr>
          <tr>
            <th>MEDIDAS</th>
            <td colspan="4"><?php echo $medida; ?></td>
          </tr>
          <tr>
            <th>GRAVEDAD</th>
            <?php
            if (isset($config['convivencia']['convivencia_seneca']) && $config['convivencia']['convivencia_seneca']) {
              switch($grave) {
                case 'leve' : $nom_gravedad = "Otra conducta"; break;
                case 'grave' : $nom_gravedad = "Conducta contraria"; break;
                case 'muy grave' : $nom_gravedad = "Conducta grave"; break;
              }
              echo "<td colspan=\"4\">$nom_gravedad</td>";
            }
            else {
              echo "<td colspan=\"4\">$grave</td>";
            }
            ?>
          </tr>
          <tr>
            <th>ANTECEDENTES</th>
            <td >Total: <?php echo $numerototal; ?></td>
            <?php if (isset($config['convivencia']['convivencia_seneca']) && $config['convivencia']['convivencia_seneca']): ?>
            <td >Contrarias: <?php echo $numerograves; ?></td>
            <td >Graves: <?php echo $numeromuygraves; ?></td>
            <td >Expulsiones: <?php echo $numeroexpulsiones; ?></td>
            <?php else: ?>
            <td >Graves: <?php echo $numerograves; ?></td>
            <td >Muy Graves: <?php echo $numeromuygraves; ?></td>
            <td >Expulsiones: <?php echo $numeroexpulsiones; ?></td>
            <?php endif; ?>
          </tr>
          <tr>
            <th>PROTOCOLOS</th>
            <td colspan="4"><?php echo $medidas2; ?></td>
          </tr>
          <tr>
            <th>PROFESOR</th>
            <td colspan="4"><?php echo $informa; ?></td>
          </tr>
        </table>
        <br />
        <div align="center"><a href="../informes/index.php?claveal=<?php echo $claveal;?>&todos=1" target="_blank" class="btn btn-primary">
        Ver Informe del Alumno
        </a>
        <a href="../jefatura/index.php?alumno=<?php echo $apellidos.", ".$nombre;?>&unidad=<?php echo $unidad;?>&grupo=<?php echo $grupo;?>" target="_blank" class="btn btn-primary">Registrar intervención de Jefatura</a></div>
    </div>
    <hr>
    <br />
    <h4>Problemas de Convivencia en el Curso</h4>
    <?php
    echo "<br /><table class='table table-striped' style='width:auto;'>";
	echo "<tr>
		<th>Fecha</th>
		<th>Tipo</th>
		<th>Gravedad</th>
		<th></th>
		</tr>";
	// Consulta de datos del alumno.
	$result = mysqli_query($db_con, "select distinct Fechoria.fecha, Fechoria.asunto, Fechoria.grave, Fechoria.id, Fechoria.informa from Fechoria where claveal = '$claveal' and fecha >= '".$config['curso_inicio']."' order by fecha DESC" );

	while ( $row = mysqli_fetch_array ( $result ) ) {
		echo "<tr>
	<td nowrap>$row[0]</td>
	<td>$row[1]</td>";
  if (isset($config['convivencia']['convivencia_seneca']) && $config['convivencia']['convivencia_seneca']) {
    switch($row[2]) {
      case 'leve' : $nom_gravedad = "Otra conducta"; break;
      case 'grave' : $nom_gravedad = "Conducta contraria"; break;
      case 'muy grave' : $nom_gravedad = "Conducta grave"; break;
    }
    echo "<td>$nom_gravedad</td>";
  }
  else {
    echo "<td>$row[2]</td>";
  }
	echo "<td nowrap><a href='detfechorias.php?id= $row[3]&claveal=$claveal' data-bs='tooltip' title='Detalles'><i class='fas fa-search fa-fw fa-lg'></i></a>";
  if($_SESSION['profi']==$row[4] or stristr($_SESSION['cargo'],'1') == TRUE){
    echo "<a href='delfechorias.php?id= $row[3]' data-bs='tooltip' data-bb='confirm-delete' title='Eliminar'><i class='far fa-trash-alt fa-fw fa-lg'></i></a>";
  }
  echo "</td></tr>";
	}
	echo "</table>\n";
    ?>

  </div>

  <div class="col-sm-5">
    <?php
   $pr = $_SESSION ['profi'];
   $conv = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos WHERE cargo like '%b%' AND nombre = '$pr'");
   if (mysqli_num_rows($conv) > '0') {$gucon = '1';}
   if (mysqli_num_rows($expulsion) > '0') {$expul = '1';}
	if(stristr($_SESSION['cargo'],'1') == TRUE or $gucon == '1' or stristr($_SESSION['cargo'],'8') == TRUE)
		{
	if (stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE) {
	?>

    <div class="well"><h4>Expulsión/Ausencia del alumno</h4><br>
    <form id="form1" name="form1" method="post" action="detfechorias.php" class="">
      <div class="form-group">
	<label> N&ordm; de D&iacute;as:</label>
        <input name="expulsion" type="text" id="textfield" <?php if($expulsion > 0){echo "value=$expulsion";}?> maxlength="2" class="form-control" />

      </div>

      <input name="id" type="hidden" value="<?php echo $id; ?>"/>
      <input name="claveal" type="hidden" value="<?php echo $claveal; ?>"/>


<div class="form-group " id="datetimepicker1">
<label>Inicio:</label>
<div class="input-group">
  <input name="inicio" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="inicio" <?php if(strlen($inicio) > '0' and !($inicio == '00-00-0000')){echo "value='$inicio'";}?>  >
  <span class="input-group-addon"><i class="far fa-calendar"></i></span>
</div>
</div>

<div class="form-group " id="datetimepicker2">
<label>Fin:</label>
<div class="input-group">
  <input name="fin" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fin" <?php if(strlen($fin) > '0' and !($fin == '00-00-0000')){echo "value='$fin'";}?>  >
  <span class="input-group-addon"><i class="far fa-calendar"></i></span>
</div>
</div>

<div class="row">
<?php if($config['mod_sms']){?>
   <div class="form-group col-sm-4">
      <div class="checkbox">
         <label>
         <input name="mens_movil" type="checkbox" id="sms" value="envia_sms" checked="checked" />
        Enviar SMS </label>
      </div>
      </div>
 <?php } ?>
  <div class="form-group col-sm-4">
          <div class="checkbox">
         <label for='tareas'>
          <input name="tareas_exp" type="checkbox" id="tareas" value="insertareas_exp" checked="checked" />
          Activar Tareas
          </label>
          </div>
          </div>
  <div class="form-group col-sm-4">
      <div class="checkbox pull-right">
         <label>
         <input name="borrar_exp" type="checkbox" id="borrar_exp" value="<?php echo $id;?>" />
        Borrar datos </label>
      </div>
      </div>
      </div>
        <input name="submit" type="submit" value="Enviar datos" class="btn btn-primary" />

    </form>
    </div>
    <?php
		}
    ?>
    <?php
 $hora = date ( "G" ); // hora
	$ndia = date ( "w" );
	if (($hora == '8' and $minutos > 15) or ($hora == '9' and $minutos < 15)) {
		$hora_dia = '1';
	} elseif (($hora == '9' and $minutos > 15) or ($hora == '10' and $minutos < 15)) {
		$hora_dia = '2';
	} elseif (($hora == '10' and $minutos > 15) or ($hora == '11' and $minutos < 15)) {
		$hora_dia = '3';
	} elseif (($hora == '11' and $minutos > 15) or ($hora == '11' and $minutos < 45)) {
		$hora_dia = 'R';
	} elseif (($hora == '11' and $minutos > 45) or ($hora == '12' and $minutos < 45)) {
		$hora_dia = '4';
	} elseif (($hora == '12' and $minutos > 45) or ($hora == '13' and $minutos < 45)) {
		$hora_dia = '5';
	} elseif (($hora == '13' and $minutos > 45) or ($hora == '14' and $minutos < 45)) {
		$hora_dia = '6';
	} else {
		$hora_dia = "0";
	}
 ?>

 <?php if ($config['mod_convivencia']==1) { ?>
 <div class="well">
    <h4>Expulsión al Aula de convivencia </h4><br>
    <form id="form2" name="form2" method="post" action="detfechorias.php" >

      <div class="form-group">
      <label >N&uacute;mero de D&iacute;as</label>
        <input name="convivencia" type="text" id="expulsion" <?php if($convivencia > 0){echo "value=$convivencia";}else{ if ($gucon == '1') {
          	echo "value=''";}}?> size="2" maxlength="2" class="form-control" />
      </div>

      <div class="form-group">
      	<label >Horas sueltas</label>
        <input name="horas" type="text" <?php if($horas > 0 || $horas == 'R'){echo "value=$horas";}else{
          	if (stristr($_SESSION['cargo'],'1') == TRUE) {
          		echo "value=123R456";
          	}else{
          		echo "value=$hora_dia";
          	}
          	}

          	?> size="6" maxlength="6" class="form-control" />
      </div>
      <input name="id" type="hidden" value="<?php echo $id;?>" />
      <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
      <hr>

     <div class="form-group"  id="datetimepicker3">
<label>Inicio:</label>
<div class="input-group">
  <input name="fechainicio" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fechainicio" <?php if($inicio_aula){echo "value=$inicio_aula";}else{if ($gucon == '1'){	$def_inicio = date ( 'd' ) . "-" . date ( 'm' ) . "-" . date ( 'Y' ); 	echo "value='$def_inicio'";}} ?> >
  <span class="input-group-addon"><i class="far fa-calendar"></i></span>
</div>
</div>

    <div class="form-group" id="datetimepicker4">
<label>Fin:</label>
<div class="input-group">
  <input name="fechafin" type="text" class="form-control" data-date-format="DD-MM-YYYY" id="fechafin" <?php if($fin_aula){echo "value=$fin_aula";}else{ if ($gucon == '1'){$def_fin = date ( 'd' ) . "-" . date ( 'm' ) . "-" . date ( 'Y' );  echo "value='$def_fin'";}} ?>  >
  <span class="input-group-addon"><i class="far fa-calendar"></i></span>
</div>
</div>
<div class="row">
          <div class="form-group col-sm-6">
          <div class="checkbox">
         <label for='tareas'>
          <input name="tareas" type="checkbox" id="tareas" value="insertareas" <?php if ($gucon == '1') {}else{          	echo 'checked="checked"';
          }?> />
          Activar Tareas
          </label>
          </div>
          </div>
          <?php if($config['mod_sms']){ ?>
          <div class="form-group  col-sm-6">
           <div class="checkbox">
          <label for='sms'>
          <input name="mens_movil" type="checkbox" id="sms" value="envia_sms" checked="checked"  />
          Enviar SMS
          </label>
          </div>
          </div>
          <?php } ?>
          <div class="form-group  col-sm-6">
           <div class="checkbox">
          <label for='borrar_aula'>
          <input name="borrar_aula" type="checkbox" id="borrar_aula" value="<?php echo $id;?>"  />
          Borrar datos
          </label>
          </div>
          </div>
</div>

<input type="submit" name="imprimir4" value="Enviar datos" class="btn btn-primary"/>

    </form>
    </div>
    <?php } ?>
    <?php } ?>
   <div>
   <div class="well">

    <h4>Impresión de partes</h4><br>

    <?php if(stristr($_SESSION['cargo'],'1') == TRUE): ?>

		<?php if ($expulsion > 0 && ! empty($inicio) && ! empty($fin)): ?>
    <h6>EXPULSI&Oacute;N DEL CENTRO</h6>
    <form id="form2" name="form2" method="post" action="imprimir/expulsioncentro.php">
      <input name="id" type="hidden" value="<?php echo $id;?>" />
      <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
      <input name="fechainicio" type="hidden" id="textfield2" size="10" maxlength="10" <?php if($inicio){echo "value=$inicio";}?> />
      <input name="fechafin" type="hidden" id="textfield3" size="10" maxlength="10" <?php if($fin){echo "value=$fin";}?> />

      <input type="submit" name="imprimir" value="Expulsi&oacute;n del Centro" class="btn btn-danger"/>
    </form>
		<?php endif; ?>

    <?php if ($config['mod_convivencia']==1): ?>
		<?php if ($convivencia > 0 && ! empty($horas) && ! empty($inicio_aula) && ! empty($fin_aula)): ?>
    <h6>EXPULSI&Oacute;N AL AULA DE CONVIVENCIA</h6>
    <form id="form3" name="form3" method="post" action="imprimir/convivencia.php">
      <input name="id" type="hidden" value="<?php echo $id;?>" />
      <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
      <input name="fechainicio" type="hidden" id="textfield2" size="10" maxlength="10" <?php if($inicio_aula){echo "value=$inicio_aula";}?> />
      <input name="fechafin" type="hidden" id="textfield3" size="10" maxlength="10" <?php if($fin_aula){echo "value=$fin_aula";}?> />
      <input name="horas" type="hidden" value="<?php echo $horas;?>" />
      <input type="submit" name="imprimir5" value="Aula de Convivencia"  class="btn btn-danger" />
    </form>
		<?php endif; ?>
		<?php endif; ?>

		<?php endif; ?>

		<?php $result_tutor = mysqli_query($db_con, "SELECT `tutor` FROM `FTUTORES` WHERE `unidad` = '$unidad' LIMIT 1"); ?>
		<?php if (mysqli_num_rows($result_tutor)): ?>
    <h6>EXPULSI&Oacute;N DEL AULA </h6>
    <form id="form3" name="form3" method="post" action="imprimir/expulsionaula.php">
        <input name="id" type="hidden" value="<?php echo $id;?>" />
        <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
        <input type="submit" name="imprimir2" value="Parte de Expulsi&oacute;n del Aula" class="btn btn-danger" />
    </form>

    <h6>AMONESTACI&Oacute;N ESCRITA </h6>
    <form id="form3" name="form3" method="post" action="imprimir/amonestescrita.php">
        <input name="id" type="hidden" value="<?php echo $id;?>" />
        <input name="claveal" type="hidden" value="<?php echo $claveal;?>" />
        <input type="submit" name="imprimir3" value="Amonestaci&oacute;n escrita " class="btn btn-danger" />
    </form>
		<?php else: ?>
		<div class="alert alert-warning">
			No hay tutor/a asignado a la unidad <?php $unidad;?>.
		</div>
		<?php endif; ?>

    </div>
  </div>
</div>
</div>
</div>
<?php include("../../pie.php");?>
	<script>
	$(function ()
	{
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		});

		$('#datetimepicker2').datetimepicker({
			language: 'es',
			pickTime: false
		});

		$('#datetimepicker3').datetimepicker({
			language: 'es',
			pickTime: false
		});

		$('#datetimepicker4').datetimepicker({
			language: 'es',
			pickTime: false
		});
	});
	</script>
</body>
</html>
