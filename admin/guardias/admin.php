<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$profesor = $_SESSION['profi'];

include("../../menu.php");
if (isset($_GET['profeso'])) {$profeso = $_GET['profeso'];}elseif (isset($_POST['profeso'])) {$profeso = $_POST['profeso'];}else{$profeso="";}
if (isset($_GET['sustituido'])) {$sustituido = $_GET['sustituido'];}elseif (isset($_POST['sustituido'])) {$sustituido = $_POST['sustituido'];}else{$sustituido="";}
if (isset($_GET['hora'])) {$hora = $_GET['hora'];}elseif (isset($_POST['hora'])) {$hora = $_POST['hora'];}else{$hora="";}
if (isset($_POST['gu_fecha'])) {$gu_fecha = $_POST['gu_fecha'];}else{$gu_fecha="";}

include 'menu.php';
?>
<div class="container">

	<div class="page-header">
		<h2 style="display:inline">Guardias de Aula <small> Registro de guardias</small></h2>
		
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
						<p>Selecciona el Profesor al que quieres apuntar una sustituci�n no registrada. Te 
						aparecer� el horario del Profesor, para que puedas determinar con precisi�n la hora 
						de la guardia (1� hora, 2� hora, etc) del d�a en cuesti�n.</p>
						<p>Seleccionas a continuaci�n el Profesor sustituido. Al hacer clic en el campo de 
						la fecha, aparecer� una nueva ventana con el calendario en el que debes pinchar sobre 
						la fecha elegida. Escribe la hora de la guardia (1, 2, 3, etc) y env�a los datos.</p>
						<p>Si quieres consultar el historial de guardias de un Profesor, pincha en 
						<em>Consultar guardias y profesores</em>. Selecciona el Profesor y aparecer� un 
						hist�rico con todas las sustituciones realizadas. Si pinchas en una de las guardias 
						de su horario, podr�s ver las sustituciones de todos los profesores de esa guardia 
						en esa hora a lo largo del curso.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>

<div class="row">
<div class="col-sm-5 col-sm-offset-1"><br>
<?php if ($config['mod_horarios']) {
	?>
<div class="well well-large">
<FORM action="admin.php" method="POST" name="Cursos">
<div class="form-group"><label> Selecciona Profesor </label> 
<SELECT
	name=profeso onChange="submit()" class="form-control" required>
	<option value="<?php echo $profeso;?>"><?php echo nomprofesor($profeso); ?></option>
	<?php
	$profe = mysqli_query($db_con, "SELECT distinct prof FROM horw where c_asig = '25' order by prof asc");
	if ($filaprofe = mysqli_fetch_array($profe))
	{
		do {

			$opcion1 = printf ('<OPTION value="'.$filaprofe[0].'">'.nomprofesor($filaprofe[0]).'</OPTION>');
			echo "$opcion1";

		} while($filaprofe = mysqli_fetch_array($profe));
	}
	?>
</select></div>
</FORM>
	<?php
	if ($profeso) {
		$pr=$profeso;
		$link="1";
		include("horario.php");
		?> <?php
	}
	?></div>
</div>
<div class="col-sm-5">
<br>
<div class="well well-large">
<FORM action="guardias.php" method="POST" name="f1">
	<input type="hidden" name="profeso" value="<?php echo $profeso;?>">	<div class="form-group">
	<label>Profesor a sustituir</label>
              <SELECT  name="sustituido" class="form-control" required>
              <option value="<?php echo $sustituido; ?>"><?php echo nomprofesor($sustituido); ?></option>
		        <?php
  $profe = mysqli_query($db_con, " SELECT distinct prof FROM horw order by prof asc");
  if ($filaprofe = mysqli_fetch_array($profe))
        {
        do {

	      $opcion1 = printf ('<OPTION value="'.$filaprofe[0].'">'.nomprofesor($filaprofe[0]).'</OPTION>');
	      echo "$opcion1";

	} while($filaprofe = mysqli_fetch_array($profe));
        }
	?>
              </select>
    </div>    
    
    <div class="form-group" id="datetimepicker1">     
	<label>Fecha de la sustituci�n</label>
	     <div class="input-group">
<input name="gu_fecha" type="text" class="form-control" value="<?php echo $gu_fecha;?>" data-date-format="DD-MM-YYYY" id="gu_fecha" required>
  <span class="input-group-addon"><i class="far fa-calendar"></i></span>
</div>   
</div>


<div class="form-group">
<label>Hora de la Guardia: </label> 
<select	name="hora" class="form-control">
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	<option>6</option>
</select>
</div>

<input type="submit" name="submit2" value="Enviar datos"
	class="btn btn-success">
</form>
<br />

</div>
</div>
</div>
</div>

	<?php
}
else {
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
El m�dulo de Horarios debe ser activado en la Configuraci�n general de la Intranet para poder acceder a estas p�ginas, y ahora mismo est� desactivado
          </div></div>';
}
?> 

	<?php include("../../pie.php"); ?>
	
<script>  
$(function ()  
{ 
	$('#datetimepicker1').datetimepicker({
		language: 'es',
		pickTime: false
	})
});  
</script>
	
</body>
</html>
