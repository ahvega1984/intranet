<?php
if (! defined('MOD_CALENDARIO')) die ('<h3>FORBIDDEN</h3>');

// CALENDARIOS PRIVADOS DEL PROFESOR
$result_calendarios1 = mysqli_query($db_con, "SELECT id, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0");
while ($calendario1 = mysqli_fetch_assoc($result_calendarios1)) {
	
	$result_eventos1 = mysqli_query($db_con, "SELECT * FROM calendario WHERE categoria='".$calendario1['id']."' AND YEAR(fechaini)=$anio AND MONTH(fechaini)=$mes");
	
	while ($eventos1 = mysqli_fetch_assoc($result_eventos1)) {
		echo '<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
		      </div>
		      <div class="modal-body">
		        
		        <form id="formEditarEvento" method="post" action="post/editarEvento.php">
		        	<fieldset>
		        		
		        		<input type="hidden" name="cmp_evento_id" value="'.$eventos1['id'].'">
		        		
		        		<div class="form-group">
		        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
		        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" value="'.$eventos1['nombre'].'" autofocus>
		        		</div>
		        		
		        		
		        		<div class="row">
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker1">
		        					<label for="cmp_fecha_ini">Fecha inicio</label>
		        					<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="'.$eventos1['fechaini'].'" data-date-format="DD/MM/YYYY">
		            					<span class="input-group-addon"><span class="fa fa-calendar">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker2">
		            				<label for="cmp_hora_ini">Hora inicio</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_hora_ini" name="cmp_hora_ini" value="'.$eventos1['horaini'].'" data-date-format="HH:mm">
		            					<span class="input-group-addon"><span class="fa fa-clock-o">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker3">
		            				<label for="cmp_fecha_fin">Fecha fin</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="'.$eventos1['fechafin'].'" data-date-format="DD/MM/YYYY">
		            					<span class="input-group-addon"><span class="fa fa-calendar">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker4">
		            				<label for="cmp_hora_fin">Hora fin</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_hora_fin" name="cmp_hora_fin" value="'.$eventos1['horafin'].'" data-date-format="HH:mm">
		            					<span class="input-group-addon"><span class="fa fa-clock-o">
		            				</div>
		            			</div>
		        			</div>
		        		</div>
		        		
		        		<div class="form-group">
		        			<label for="cmp_descripcion">Descripción</label>
		        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion">'.$eventos1['descripcion'].'</textarea>
		        		</div>
		        		
		        		<div class="form-group">
		        			<label for="cmp_lugar">Lugar Tarari</label>
		        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar" value="'.$eventos1['lugar'].'">
		        		</div>
		        		
		        		
		        		<div id="opciones_diario">';
		        		
		        		if ($eventos1['unidades'] != "") {
		        			$exp_unidades = explode('; ', $eventos1['unidades']);
		        		}
		        		
		        		$result = mysqli_query($db_con, "SELECT DISTINCT grupo, materia FROM profesores WHERE profesor='".$_SESSION['profi']."'");
		        		if (mysqli_num_rows($result)):
		        		echo '<div class="form-group">
		        				<label for="cmp_unidad_asignatura">Unidad y asignatura</label>
		        				
		        				<select class="form-control" id="cmp_unidad_asignatura" name="cmp_unidad_asignatura[]" size="5" multiple>';
		        			while ($row = mysqli_fetch_array($result)):
		        					echo '<option value="'.$row['grupo'].' => '.$row['materia'].'"';
		        					if (in_array($row['grupo'], $exp_unidades)) echo ' selected';
		        					echo '>'.$row['grupo'].' ('.$row['materia'].')'.'</option>';
		        			endwhile;
		        			echo'</select>
		        			</div>';
		        		endif;
		        		echo '</div>
		        						        				        		
		        	</fieldset>
		        </form>
		        
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		        <button type="submit" class="btn btn-danger" form="formEditarEvento" formaction="post/eliminarEvento.php">Eliminar</button>
		        <button type="submit" class="btn btn-primary" form="formEditarEvento">Modificar</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->';	
	}
	mysqli_free_result($result_eventos1);
}
mysqli_free_result($result_calendarios1);

// CALENDARIOS PUBLICOS
$result_calendarios1 = mysqli_query($db_con, "SELECT id, color FROM calendario_categorias WHERE espublico=1");
while ($calendario1 = mysqli_fetch_assoc($result_calendarios1)) {
	
	$result_eventos1 = mysqli_query($db_con, "SELECT * FROM calendario WHERE categoria='".$calendario1['id']."' AND YEAR(fechaini)=$anio AND MONTH(fechaini)=$mes");
	
	while ($eventos1 = mysqli_fetch_assoc($result_eventos1)) {
		if (stristr($_SESSION['cargo'],'1')) {
			echo '<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
			      </div>
			      <div class="modal-body">
			        
			        <form id="formEditarEvento" method="post" action="post/editarEvento.php">
			        	<fieldset>
			        		
			        		<input type="hidden" name="cmp_evento_id" value="'.$eventos1['id'].'">
			        		
			        		<div class="form-group">
			        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
			        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" value="'.$eventos1['nombre'].'" autofocus>
			        		</div>
			        		
			        		
			        		<div class="row">
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker1">
			        					<label for="cmp_fecha_ini">Fecha inicio</label>
			        					<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="'.$eventos1['fechaini'].'" data-date-format="DD/MM/YYYY">
			            					<span class="input-group-addon"><span class="fa fa-calendar">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker2">
			            				<label for="cmp_hora_ini">Hora inicio</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_hora_ini" name="cmp_hora_ini" value="'.$eventos1['horaini'].'" data-date-format="HH:mm">
			            					<span class="input-group-addon"><span class="fa fa-clock-o">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker3">
			            				<label for="cmp_fecha_fin">Fecha fin</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="'.$eventos1['fechafin'].'" data-date-format="DD/MM/YYYY">
			            					<span class="input-group-addon"><span class="fa fa-calendar">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker4">
			            				<label for="cmp_hora_fin">Hora fin</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_hora_fin" name="cmp_hora_fin" value="'.$eventos1['horafin'].'" data-date-format="HH:mm">
			            					<span class="input-group-addon"><span class="fa fa-clock-o">
			            				</div>
			            			</div>
			        			</div>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_descripcion">Descripción</label>
			        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion">'.$eventos1['descripcion'].'</textarea>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_lugar">Lugar</label>
			        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar" value="'.$eventos1['lugar'].'">
			        		</div>
			        						        				        		
			        	</fieldset>
			        </form>
			        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-danger" form="formEditarEvento" formaction="post/eliminarEvento.php">Eliminar</button>
			        <button type="submit" class="btn btn-primary" form="formEditarEvento">Modificar</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';
		}
		else {
			echo '<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
			      </div>
			      <div class="modal-body">
	        		
	        		<div class="row">
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	        					<label for="cmp_fecha_ini">Fecha inicio</label>
	            				<p class="form-control-static">'.$eventos1['fechaini'].'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="cmp_hora_ini">Hora inicio</label>
	            				<p class="form-control-static">'.$eventos1['horaini'].'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="cmp_fecha_fin">Fecha fin</label>
	            				<p class="form-control-static">'.$eventos1['fechafin'].'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="cmp_hora_fin">Hora fin</label>
	            				<p class="form-control-static">'.$eventos1['horafin'].'</p>
	            			</div>
	        			</div>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_descripcion">Descripción</label>
	        			<p class="form-control-static">'.$eventos1['descripcion'].'</p>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_lugar">Lugar</label>
	        			<p class="form-control-static">'.$eventos1['lugar'].'</p>
	        		</div>
			        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';	
		}
	}
	mysqli_free_result($result_eventos1);
}
mysqli_free_result($result_calendarios1);
?>