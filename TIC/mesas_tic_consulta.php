<?php
require('../bootstrap.php');
if (isset($_POST['actividad'])){
	  $exp_act = explode('==>', $_POST['actividad']);
	  $mesas_profesor = trim($exp_act[0]);
	  $mesas_grupo = trim($exp_act[1]);
	  $mesas_asignatura = trim($exp_act[2]);
	  $mesas_aula = trim($exp_act[3]);
	  $mesas_codasig = trim($exp_act[4]);
	  if(isset($_POST['monopuesto'])){
		  $_SESSION['mesas_monopuesto'] = $_POST['monopuesto'];
		  }
	}
// Reasigno por comodidad
$profesor = $mesas_profesor;
$grupo = $mesas_grupo;
$asig =	$mesas_asignatura;
$aula = $mesas_aula;
$codasig =	$mesas_codasig;

// DIMENSIONES DEL AULA
$mesas_col = 9; $mesas = 48; $col_profesor = 9;

//FUNCIONES VARIAS
function obtenerAlumno($var_nie, $grupo) {
	global $db_con;
	$result = mysqli_query($db_con, "SELECT `apellidos`, `nombre`, `unidad` FROM `alma` WHERE `claveal` = '".$var_nie."' ORDER BY `apellidos` ASC, `nombre` ASC LIMIT 1");
	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		if (mb_strstr($grupo,'+')){
			return $row['apellidos'].', '.$row['nombre'].' ('.$row['unidad'].')';
			}
			else{
				return $row['apellidos'].', '.$row['nombre'];
				}
	}
	else {
		return '';
		}
}

function etiqueta($num_mesa) {
	if ($_SESSION['mesas_monopuesto']==1) {
		return($num_mesa);
		}
		else {
			$et_mesa = round($num_mesa/2);
			return($et_mesa);
		}
		}
// ACTUALIZAR PUESTOS
if (isset($_POST['listOfItems'])){
	$result_update = mysqli_query($db_con, "UPDATE `puestos_alumnos_tic` SET `monopuesto` = '".$_SESSION['mesas_monopuesto']."', `puestos` = '".$_POST['listOfItems']."' WHERE `profesor` = '$mesas_profesor' AND `grupo` = '$mesas_grupo' AND `asignatura` = '$mesas_codasig' AND `aula` = '$mesas_aula'");
	if(!$result_update) $msg_error = "La asignación de puestos en el aula no se ha podido actualizar. Error: ".mysqli_error($db_con);
	else $msg_success = "La asignación de puestos en el aula se ha actualizado correctamente.";
	}
	
	// OBTENEMOS LOS PUESTOS, SI NO EXISTE LOS CREAMOS
$result = mysqli_query($db_con, "SELECT `profesor`,`grupo`,`asignatura`,`aula`, `puestos`, `monopuesto` FROM `puestos_alumnos_tic` WHERE `profesor` = '$mesas_profesor' AND `grupo` = '$mesas_grupo' AND `asignatura` = '$mesas_codasig' AND `aula` = '$mesas_aula' LIMIT 1");
$monopuesto_reg = '';
if (! mysqli_num_rows($result)) {
	$result_insert = mysqli_query($db_con, "INSERT INTO `puestos_alumnos_tic` (`profesor`,`grupo`,`asignatura`,`aula`, `puestos`, `monopuesto`) VALUES ('".$mesas_profesor."', '".$mesas_grupo."', '".$mesas_codasig."', '".$mesas_aula."', '', '0')");
	$_SESSION['mesas_monopuesto'] ='0';
	if (! $result_insert) $msg_error = "La asignación de puestos en el aula no se ha podido guardar. Error: ".mysqli_error($db_con);
	}
	else {
		$row = mysqli_fetch_array($result);
		$cadena_puestos = $row['puestos'];
		$monopuesto_reg= $row['monopuesto'];
		mysqli_free_result($result);
		}
		if (! isset($_POST['monopuesto']) && $monopuesto_reg != ''){
			$_SESSION['mesas_monopuesto'] = $monopuesto_reg;
			}
$matriz_puestos = explode(';', $cadena_puestos);
foreach ($matriz_puestos as $value) {
	$los_puestos = explode('|', $value);
	if ($los_puestos[0] == 'allItems') {
		$sin_puesto[] = $los_puestos[1];
		}
		else {
			$con_puesto[$los_puestos[0]] = $los_puestos[1];
			}
}
include("../menu.php");
include("menu.php");
?>

	<style class="text/css">
	table tr td {
		vertical-align: top;
	}

	table tr td.active {
		background-color: #333;
	}

	#allItems {
		width: 100%;
		border: 1px solid #ecf0f1;
	}

	#allItems p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 15px;
		margin-bottom: 4px;
	}

	#allItems ul li {
		background-color: #efefef;
		padding: 5px 15px;
		margin: 5px;
		font-size: 0.8em;
		cursor: move;
	}

	#dhtmlgoodies_mainContainer table tr td div {
		border: 1px solid #ecf0f1;
		margin: 0 5px 10px 5px;
	}

	#dhtmlgoodies_mainContainer table tr td div {
	width: 98px;
	}

	#dhtmlgoodies_mainContainer table tr td div p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 2px;
		margin-bottom: 4px;
	}

	#dhtmlgoodies_mainContainer table tr td div ul {
		margin: 0 4px 4px 4px;
		min-height: 50px;
		background-color: #efefef;
	}

	#dhtmlgoodies_mainContainer table tr td div ul li {
		height: 100%;
		cursor: move;
	}


	#dhtmlgoodies_dragDropContainer .mouseover ul {
		background-color:#E2EBED;
		border: 1px solid #3FB618;
	}

	#dragContent {
		position: absolute;
		margin-top: -280px;
		margin-left: -150px;
		width: 150px;
		height: 60px;
		font-size: 0.8em;
		z-index: 2000;
		cursor: move;
	}

	.text-sm {
		font-size: 0.7em;
	}

	.col-sm-9 {
		padding-left: 0;
		padding-right: 0;
	}

	@media print {
		html, body {
			padding: 0;
		}

		.page-header {
			margin: 5px 0;
		}

  	.page-header h2 {
			font-size: 120%;
		}
		.page-header h4 {
			font-size: 100%;
		}
	}
	</style>
	<div class="container">
	
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<!-- Button trigger modal -->
			<a href="#" class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyudaMesasTIC" style="margin-right: 5px;">
				<span class="fas fa-question fa-lg"></span>
			</a>

			<!-- Modal -->
			<div class="modal fade" id="modalAyudaMesasTIC" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
							<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
						</div>
						<div class="modal-body">
							<p>Cada profesor puede crear una plantilla con la ubicación de los alumnos de sus
							grupos en las aulas TIC. Cada grupo tendrá una única plantilla, independientemente del
							aula que pueda utilizar. La distribución de las mesas que se muestra en la plantilla
							no tiene por qué ser la distribución física real, dado que las aulas de informática
							suelen tener una distribución muy variable. Lo importante es saber que mesa ocupa cada
							alumno, por si ocurre alguna incidencia.</p>
							<p>Primero debes seleccionar si los alumnos se sientan 2 por ordenador (<b>bipuesto</b>) o 1 por
							ordenador (<b>monopuesto</b>). A continuación arrastra cada alumno a su mesa, y finalmente pulsa
							el botón <b>"Guardar"</b>.
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
						</div>
					</div>
				</div>
			</div>

			<h2 style="display: inline;">Centro TIC <small>Asignación de mesas TIC</small></h2>
			<h4 class="text-info">Profesor/a: <?php echo nomprofesor($profesor); ?> - Unidad: <?php echo $grupo; ?> - Asignatura: <?php echo $asig; ?></h4>
		</div>


		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>

		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>


		<!-- SCAFFOLDING -->
		<div id="dhtmlgoodies_dragDropContainer" class="row">

			<!-- COLUMNA IZQUIERDA -->
			<div id="dhtmlgoodies_listOfItems" class="col-sm-3 hidden-print">

				<div id="allItems">
					<p>Alumnos/as</p>
					<ul class="list-unstyled">
						<?php $result = mysqli_query($db_con, "SELECT combasi, apellidos, nombre, claveal, unidad FROM alma ORDER BY unidad, apellidos ASC, nombre ASC"); ?>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						 <?php $cod_combasi = explode(':', $row['combasi']);?>
						<?php if (!in_array($row['claveal'],$con_puesto) && (in_array($codasig, $cod_combasi) || $codasig=='2') && mb_strstr($grupo,$row['unidad'])): ?>
					  <li id="<?php echo $row['claveal']; ?>">
					  <?php if (mb_strstr($grupo,'+')): ?>
					        <?php echo $row['apellidos'].', '.$row['nombre'].' ('.$row['unidad'].')'; ?>
					  <?php else: ?>
						    <?php echo $row['apellidos'].', '.$row['nombre']; ?>
					  <?php endif; ?>
					  </li>
					  <?php endif; ?>
					  <?php endwhile; ?>
					</ul>
				</div>

				<ul id="dragContent" class="list-unstyled"></ul>

			</div><!-- /.col-sm-3 -->


			<!-- COLUMNA DERECHA -->
			<div id="dhtmlgoodies_mainContainer" class="col-sm-9">

				<form class="hidden-print" action="" method="post" style="margin-bottom: 10px;">
					<h5 style="font-weight: bold; display: inline-block; margin-right: 10px;">Tipo de disposición: </h5>

					<label class="radio-inline">
						<input type="radio" name="monopuesto" value="1" onchange="submit()" <?php echo ($_SESSION['mesas_monopuesto'] == '1') ? 'checked' : ''; ?>> Monopuesto
					</label>
					<label class="radio-inline">
						<input type="radio" name="monopuesto" value="0" onchange="submit()" <?php echo ($_SESSION['mesas_monopuesto'] == '0') ? 'checked' : ''; ?> > Bipuesto
					</label>

					<input type="hidden" name="actividad" value="<?php echo $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$codasig.'==>'.$actividad; ?>">
				</form>

				<table>
					<?php for ($i = 1; $i < 7; $i++): ?>
					<tr>
						<?php for ($j = 1; $j < $mesas_col; $j++): ?>
						<td>
							<div><p class="text-center">Mesa <?php echo etiqueta($mesas); ?></p>
								<ul id="<?php echo $mesas; ?>" class="list-unstyled text-sm">
									<?php if (isset($con_puesto[$mesas])): ?>
											<li id="<?php echo $con_puesto[$mesas]; ?>"><?php echo obtenerAlumno($con_puesto[$mesas], $grupo); ?></li>
									<?php endif; ?>
								</ul>
							</div>
						</td>
						<?php if ($j == 2 || $j == 4 || $j == 6): ?>
						<td class="text-center active">|</td>
						<?php endif; ?>
						<?php $mesas--; ?>
						<?php endfor; ?>
					</tr>
					<?php endfor; ?>
					<tr>
						<td colspan="<?php echo $col_profesor; ?>">
							<br><p id="dragDropIndicator" class="text-info hidden-print">Arrastre cada alumno/a a la mesa correspondiente</p>
							</td>
						<td colspan="2" class="text-center">
							<div style="width: 96%;">
								<p>Profesor/a</p>
								<br><br><br>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.col-sm-9 -->
		</div><!-- /.row -->
		<br>
		<div class="row">
			<div class="col-sm-12">
				<div class="hidden-print">
					<form id="myForm" name="myForm" method="post" action="" onsubmit="saveDragDropNodes()">
						<input type="hidden" name="listOfItems" value="">
						<input type="hidden" name="actividad" value="<?php echo $profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$codasig.'==>'.$actividad; ?>">
						<button type="submit" class="btn btn-primary" name="saveButton">Guardar cambios</button>
						<a href="#" class="btn btn-default" onclick="javascript:print();">Imprimir</a>
						<a class="btn btn-default" href="mesas_tic_seleccion.php">Volver</a>
					</form>
				</div>
			</div><!-- /col-sm-12 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
	<?php include("../pie.php"); ?>

	<script type="text/javascript">
	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, November 2005

	Update log:

	December 20th, 2005 : Version 1.1: Added support for rectangle indicating where object will be dropped
	January 11th, 2006: Support for cloning, i.e. "copy & paste" items instead of "cut & paste"
	January 18th, 2006: Allowing multiple instances to be dragged to same box(applies to "cloning mode")

	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

		Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.

	Thank you!

	www.dhtmlgoodies.com
	Alf Magne Kalleland

	************************************************************************************************************/

	/* VARIABLES YOU COULD MODIFY */
	
	var boxSizeArray = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
	// Array indicating how many items  there is rooom for in the right column ULs
	var verticalSpaceBetweenListItems = 3;	// Pixels space between one <li> and next
											// Same value or higher as margin bottom in CSS for #dhtmlgoodies_dragDropContainer ul li,#dragContent li
	var indicateDestionationByUseOfArrow = false;	// Display arrow to indicate where object will be dropped(false = use rectangle)
	
	var cloneSourceItems = false;	// Items picked from main container will be cloned(i.e. "copy" instead of "cut").
	var cloneAllowDuplicates = false;	// Allow multiple instances of an item inside a small box(example: drag Student 1 to team A twice

	/* END VARIABLES YOU COULD MODIFY */

	var dragDropTopContainer = false;
	var dragTimer = -1;
	var dragContentObj = false;
	var contentToBeDragged = false;	// Reference to dragged <li>
	var contentToBeDragged_src = false;	// Reference to parent of <li> before drag started
	var contentToBeDragged_next = false; 	// Reference to next sibling of <li> to be dragged
	var destinationObj = false;	// Reference to <UL> or <LI> where element is dropped.
	var dragDropIndicator = false;	// Reference to small arrow indicating where items will be dropped
	var ulPositionArray = new Array();
	var mouseoverObj = false;	// Reference to highlighted DIV

	var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
	var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;

	var arrow_offsetX = -5;	// Offset X - position of small arrow
	var arrow_offsetY = 0;	// Offset Y - position of small arrow

	if(!MSIE || navigatorVersion > 6){
		arrow_offsetX = -6;	// Firefox - offset X small arrow
		arrow_offsetY = -13; // Firefox - offset Y small arrow
	}

	var indicateDestinationBox = false;
	function getTopPos(inputObj)
	{
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}

	function getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
	  }
	  return returnValue;
	}

	function cancelEvent()
	{
		return false;
	}
	function initDrag(e)	// Mouse button is pressed down on a LI
	{
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);

		dragTimer = 0;
		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';
		contentToBeDragged = this;
		contentToBeDragged_src = this.parentNode;
		contentToBeDragged_next = false;
		if(this.nextSibling){
			contentToBeDragged_next = this.nextSibling;
			if(!this.tagName && contentToBeDragged_next.nextSibling)contentToBeDragged_next = contentToBeDragged_next.nextSibling;
		}
		timerDrag();
		return false;
	}

	function timerDrag()
	{
		if(dragTimer>=0 && dragTimer<10){
			dragTimer++;
			setTimeout('timerDrag()',10);
			return;
		}
		if(dragTimer==10){

			if(cloneSourceItems && contentToBeDragged.parentNode.id=='allItems'){
				newItem = contentToBeDragged.cloneNode(true);
				newItem.onmousedown = contentToBeDragged.onmousedown;
				contentToBeDragged = newItem;
			}
			dragContentObj.style.display='block';
			dragContentObj.appendChild(contentToBeDragged);
		}
	}

	function moveDragContent(e)
	{
		if(dragTimer<10){
			if(contentToBeDragged){
				if(contentToBeDragged_next){
					contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
				}else{
					contentToBeDragged_src.appendChild(contentToBeDragged);
				}
			}
			return;
		}
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);


		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';

		if(mouseoverObj)mouseoverObj.className='';
		destinationObj = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox)indicateDestinationBox.style.display='none';
		var x = e.clientX + sl;
		var y = e.clientY + st;
		var width = dragContentObj.offsetWidth;
		var height = dragContentObj.offsetHeight;

		var tmpOffsetX = arrow_offsetX;
		var tmpOffsetY = arrow_offsetY;

		for(var no=0;no<ulPositionArray.length;no++){
			var ul_leftPos = ulPositionArray[no]['left'];
			var ul_topPos = ulPositionArray[no]['top'];
			var ul_height = ulPositionArray[no]['height'];
			var ul_width = ulPositionArray[no]['width'];

			if((x+width) > ul_leftPos && x<(ul_leftPos + ul_width) && (y+height)> ul_topPos && y<(ul_topPos + ul_height)){
				var noExisting = ulPositionArray[no]['obj'].getElementsByTagName('LI').length;
				if(indicateDestinationBox && indicateDestinationBox.parentNode==ulPositionArray[no]['obj'])noExisting--;
				if(noExisting<boxSizeArray[no-1] || no==0){
					dragDropIndicator.style.left = ul_leftPos + tmpOffsetX + 'px';
					var subLi = ulPositionArray[no]['obj'].getElementsByTagName('LI');

					var clonedItemAllreadyAdded = false;
					if(cloneSourceItems && !cloneAllowDuplicates){
						for(var liIndex=0;liIndex<subLi.length;liIndex++){
							if(contentToBeDragged.id == subLi[liIndex].id)clonedItemAllreadyAdded = true;
						}
						if(clonedItemAllreadyAdded)continue;
					}

					for(var liIndex=0;liIndex<subLi.length;liIndex++){
						var tmpTop = getTopPos(subLi[liIndex]);
						if(!indicateDestionationByUseOfArrow){
							if(y<tmpTop){
								destinationObj = subLi[liIndex];
								indicateDestinationBox.style.display='block';
								subLi[liIndex].parentNode.insertBefore(indicateDestinationBox,subLi[liIndex]);
								break;
							}
						}else{
							if(y<tmpTop){
								destinationObj = subLi[liIndex];
								dragDropIndicator.style.top = tmpTop + tmpOffsetY - Math.round(dragDropIndicator.clientHeight/2) + 'px';
								dragDropIndicator.style.display='block';
								break;
							}
						}
					}

					if(!indicateDestionationByUseOfArrow){
						if(indicateDestinationBox.style.display=='none'){
							indicateDestinationBox.style.display='block';
							ulPositionArray[no]['obj'].appendChild(indicateDestinationBox);
						}

					}else{
						if(subLi.length>0 && dragDropIndicator.style.display=='none'){
							dragDropIndicator.style.top = getTopPos(subLi[subLi.length-1]) + subLi[subLi.length-1].offsetHeight + tmpOffsetY + 'px';
							dragDropIndicator.style.display='block';
						}
						if(subLi.length==0){
							dragDropIndicator.style.top = ul_topPos + arrow_offsetY + 'px'
							dragDropIndicator.style.display='block';
						}
					}

					if(!destinationObj)destinationObj = ulPositionArray[no]['obj'];
					mouseoverObj = ulPositionArray[no]['obj'].parentNode;
					mouseoverObj.className='mouseover';
					return;
				}
			}
		}
	}

	/* End dragging
	Put <LI> into a destination or back to where it came from.
	*/
	function dragDropEnd(e)
	{
		if(dragTimer==-1)return;
		if(dragTimer<10){
			dragTimer = -1;
			return;
		}
		dragTimer = -1;
		if(document.all)e = event;

		if(cloneSourceItems && (!destinationObj || (destinationObj && (destinationObj.id=='allItems' || destinationObj.parentNode.id=='allItems')))){
			contentToBeDragged.parentNode.removeChild(contentToBeDragged);
		}
		else{
			if(destinationObj){
				if(destinationObj.tagName=='UL'){
					destinationObj.appendChild(contentToBeDragged);
				}else{
					destinationObj.parentNode.insertBefore(contentToBeDragged,destinationObj);
				}
				mouseoverObj.className='';
				destinationObj = false;
				dragDropIndicator.style.display='none';
				if(indicateDestinationBox){
					indicateDestinationBox.style.display='none';
					document.body.appendChild(indicateDestinationBox);
				}
				contentToBeDragged = false;
				return;
			}
			if(contentToBeDragged_next){
				contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
			}else{
				contentToBeDragged_src.appendChild(contentToBeDragged);
			}
		}
		contentToBeDragged = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox){
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);

		}
		mouseoverObj = false;

	}

	/*
	Preparing data to be saved
	*/
	function saveDragDropNodes()
	{
		var saveString = "";
		var uls = dragDropTopContainer.getElementsByTagName('ul');
		for(var no=0;no<uls.length;no++){	// LOoping through all <ul>
			var lis = uls[no].getElementsByTagName('li');
			for(var no2=0;no2<lis.length;no2++){
				if(saveString.length>0)saveString = saveString + ";";
				saveString = saveString + uls[no].id + '|' + lis[no2].id;
			}
		}
		saveString = saveString + ";";
		document.forms['myForm'].listOfItems.value = saveString;
		document.getElementById('saveContent').innerHTML = '<h1>Ready to save these nodes:</h1> ' + saveString.replace(/;/g,';<br>') + '<p>Format: ID of ul |(pipe) ID of li;(semicolon)</p><p>You can put these values into a hidden form fields, post it to the server and explode the submitted value there</p>';
	}

	function initDragDropScript()
	{
		dragContentObj = document.getElementById('dragContent');
		dragDropIndicator = document.getElementById('dragDropIndicator');
		dragDropTopContainer = document.getElementById('dhtmlgoodies_dragDropContainer');
		document.documentElement.onselectstart = cancelEvent;
		var listItems = dragDropTopContainer.getElementsByTagName('LI');	// Get array containing all <LI>
		var itemHeight = false;
		for(var no=0;no<listItems.length;no++){
			listItems[no].onmousedown = initDrag;
			listItems[no].onselectstart = cancelEvent;
			if(!itemHeight)itemHeight = listItems[no].offsetHeight;
			if(MSIE && navigatorVersion/1<6){
				listItems[no].style.cursor='hand';
			}
		}

		var mainContainer = document.getElementById('dhtmlgoodies_mainContainer');
		var uls = mainContainer.getElementsByTagName('UL');
		itemHeight = itemHeight + verticalSpaceBetweenListItems;
		for(var no=0;no<uls.length;no++){
			uls[no].style.height = itemHeight * boxSizeArray[no]  + 'px';
		}

		var leftContainer = document.getElementById('dhtmlgoodies_listOfItems');
		var itemBox = leftContainer.getElementsByTagName('UL')[0];

		document.documentElement.onmousemove = moveDragContent;	// Mouse move event - moving draggable div
		document.documentElement.onmouseup = dragDropEnd;	// Mouse move event - moving draggable div

		var ulArray = dragDropTopContainer.getElementsByTagName('UL');
		for(var no=0;no<ulArray.length;no++){
			ulPositionArray[no] = new Array();
			ulPositionArray[no]['left'] = getLeftPos(ulArray[no]);
			ulPositionArray[no]['top'] = getTopPos(ulArray[no]);
			ulPositionArray[no]['width'] = ulArray[no].offsetWidth;
			ulPositionArray[no]['height'] = ulArray[no].clientHeight;
			ulPositionArray[no]['obj'] = ulArray[no];
		}

		if(!indicateDestionationByUseOfArrow){
			indicateDestinationBox = document.createElement('LI');
			indicateDestinationBox.id = 'indicateDestination';
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);
		}
	}
	window.onload = initDragDropScript;
	</script>
</body>
</html>
	