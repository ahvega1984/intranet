$( document ).ready(function() {
	$.post( "estadisticas_dia.php", { "id" : 'accesos' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	    	
	       $('#stats-numprof').html(data.num_profesores);
	       $('#stats-totalprof').html(data.total_profesores);
	       
	       if (data.accesos_tabla.length > 0) {
	           $('#stats-accesos-modal').html('<div class="table-responsive" style="height: 350px; overflow: scroll;"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Profesor/a</th><th>Departamento</th></tr></thead><tbody id="accesos_tabla"></tbody></table></div>');
	           
	           $.each(data.accesos_tabla, function(i, item) {
	          	 $('#accesos_tabla').append('<tr><td>' + item.profesor + '</td><td>' + item.departamento + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-accesos-modal').html('<p class="lead text-center text-muted"><span class="far fa-thumbs-o-up fa-5x"></span><br>Todos los profesores han accedido hoy</p>');
	       }
	});
	
	$.post( "estadisticas_dia.php", { "id" : 'convivencia' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-convivencia').html(data.total);
	       
	       if (data.convivencia_tabla.length > 0) {
	           $('#stats-convivencia-modal').html('<div class="table-responsive" style="height: 350px; overflow: scroll;"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Problema</th><th>Profesor</th></tr></thead><tbody id="convivencia_tabla"></tbody></table></div>');
	           
	           $.each(data.convivencia_tabla, function(i, item) {
	          	 $('#convivencia_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td>' + item.alumno + '</td><td>' + item.problema + '</td><td nowrap>' + item.profesor + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-convivencia-modal').html('<br><p class="lead text-center text-muted">No se han registrado problemas de convivencia hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas_dia.php", { "id" : 'expulsiones' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-expulsados').html(data.total_expulsados);
	       $('#stats-reingresos').html(data.total_reingresan);
	       
	       if (data.expulsados_tabla.length > 0) {
	           $('#stats-expulsados-modal').html('<div class="table-responsive"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th><th>Problema</th><th>Inicio</th><th>Fin</th></tr></thead><tbody id="expulsados_tabla"></tbody></table></div>');
	           
	           $.each(data.expulsados_tabla, function(i, item) {
	          	 $('#expulsados_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td>' + item.alumno + '</td><td>' + item.unidad + '</td><td nowrap>' + item.problema + '</td><td>' + item.inicio + '</td><td>' + item.fin + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-expulsados-modal').html('<br><p class="lead text-center text-muted">No hay alumnos expulsados actualmente</p><br>');
	       }
	       
	       if (data.reincorporaciones_tabla.length > 0) {
	           $('#stats-reincorporaciones-modal').html('<div class="table-responsive"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th><th>Problema</th><th>Inicio</th><th>Fin</th></tr></thead><tbody id="reincorporaciones_tabla"></tbody></table></div>');
	           
	           $.each(data.reincorporaciones_tabla, function(i, item) {
	          	 $('#reincorporaciones_tabla').append('<tr onclick="window.location.href=\'admin/fechorias/detfechorias.php?id=' + item.idfechoria + '&amp;claveal=' + item.claveal + '\'" style="cursor: pointer; font-size: 0.9em;"><td>' + item.alumno + '</td><td>' + item.unidad + '</td><td nowrap>' + item.problema + '</td><td>' + item.inicio + '</td><td>' + item.fin + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-reincorporaciones-modal').html('<br><p class="lead text-center text-muted">No hay alumnos que se reincorporen hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas_dia.php", { "id" : 'visitas' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-visitas').html(data.total);
	       
	       if (data.visitas_tabla.length > 0) {
	           $('#stats-visitas-modal').html('<div class="table-responsive"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Alumno/a</th><th>Unidad</th><th>Tutor</th></tr></thead><tbody id="visitas_tabla"></tbody></table></div>');
	           
	           $.each(data.visitas_tabla, function(i, item) {
	          	 $('#visitas_tabla').append('<tr onclick="window.location.href=\'admin/infotutoria/infocompleto.php?id=' + item.idvisita + '\'" style="cursor: pointer; font-size: 0.9em;"><td>' + item.alumno + '</td><td>' + item.unidad + '</td><td nowrap>' + item.tutor + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-visitas-modal').html('<br><p class="lead text-center text-muted">No hay visitas previstas para hoy</p><br>');
	       }
	});
	
	$.post( "estadisticas_dia.php", { "id" : 'mensajes' }, null, "json" )
	    .done(function( data, textStatus, jqXHR ) {
	       $('#stats-mensajes').html(data.total);
	       
	       if (data.mensajes_tabla.length > 0) {
	           $('#stats-mensajes-modal').html('<div class="table-responsive" style="height: 350px; overflow: scroll;"><table class="table table-condensed table-hover table-striped"><thead><tr><th>Profesor/a</th><th class="text-center">Sin leer</th></tr></thead><tbody id="mensajes_tabla"></tbody></table></div>');
	           
	           $.each(data.mensajes_tabla, function(i, item) {
	          	 $('#mensajes_tabla').append('<tr><td>' + item.profesor + '</td><td class="text-center"><a href="index.php?resetea_mensaje=1&idea_mensaje=' + item.idea + '" class="pull-right"><span class="far fa-refresh fa-fw" data-bs="tooltip" title="Marcar todos los mensajes como leídos"></span></a>' + item.numero + '</td></tr>');
	           });
	       }
	       else {
	           $('#stats-mensajes-modal').html('<p class="lead text-center text-muted"><span class="far fa-thumbs-o-up fa-5x"></span><br>Sin mensajes no le&iacute;dos</p>');
	       }
	});
});