<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<style type="text/css">
			
			.btn {
				margin: 5px;
				text-align: left;
				width: 150px;
			}
			li {
				list-style:none; 
				font-size:14px;
				margin-bottom: 10px;
			}
			a {
				font-size: 12px;
			}
			.modal-lg, .modal-xl {
				max-width: 800px;
			}
			
			body {
				overflow-x: hidden;
			}
			.fa-envelope {
				margin-left: 25px;
			}
			
			.modal-dialog {
				max-width: 800px;
			}
			
			.modal-header {
				background: #00a65a;
				color: #fff;
			}
			.modal-header .close{
				color: #fff;
			}
			.modal-body {
				background-color: #ecf0f1;
			}
			
			.btn-success {
				background-color: #00a65a;
			}
			.row {
				margin-left: 20px;
				margin-top:20px;
			}
			
			.button {				
				border: none;
				color: white;
				padding: 15px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				font-weight: bold;
			  }
			
		</style>
		
		<script>
			
			function abrir (id) {				
				$($("[id^='etapa_']")).hide();				
				$('#etapa_'+id).fadeIn("slow");
			}
			
			function maximize(type, num) {
				$("#"+type+"_"+num).fadeIn("slow");
				$("#minimize_"+type+"_"+num).show();
				$("#maximize_"+type+"_"+num).hide();
			}
			function minimize(type, num) {
				$("#"+type+"_"+num).fadeOut("slow");
				$("#minimize_"+type+"_"+num).hide();
				$("#maximize_"+type+"_"+num).show();
			}
			
		</script>
		
	</head>
	<body>
	<?php	
		include 'lib.php';
		$datos = getTutorias();
		//printArray($datos);
		$i = 0;
		$j = 0;
		$buttons_etapas = '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="#"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			  <ul class="navbar-nav mr-auto">';
		$buttons_grupos = "";
		foreach ($datos as $key=>$grupos) { 
			$j++;
			$buttons_etapas .= '<li class="nav-item"><a class="nav-link button" style="cursor: pointer; color:#fff" href="#etapa_'.$j.'" onclick="abrir('.$j.')">'.$key.'</a></li>';
			
			$buttons_grupos .= '<div class="row" id="etapa_'.$j.'" style="display: none;">';
			foreach ($grupos as $dato) {				
				$i++;
				$buttons_grupos .= '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal'.$i.'">
										<span class="fas fa-mouse-pointer fa-fw fa-lg"></span>'.$dato['grupo'].'
									</button>									
									<div class="modal fade" id="exampleModal'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<br>
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel"> ';
				foreach ($dato['cursos'] as $curso) {
					$buttons_grupos .= '<div class "title"> <i class="fas fa-book"></i> '.$curso.'</div>';
				}										
				$buttons_grupos .= '</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<h6> <i class="fas fa-user"></i> Tutor/a: </h6>
									<ul>
										<li> <i class="far fa-user"></i> '.$dato['tutor'].'</li>';
				if ($dato['tutor_correo'] != '') {
					$buttons_grupos .= '<li><span class="fa fa-envelope"></span> <a href="mailto:'.$dato['tutor_correo'].'">'.$dato['tutor_correo'].'</a></li>';
				}
				
				if ($dato['horario'] != '') {
					$buttons_grupos .= '<li> Horario de atención a familias: <b>'.$dato['horario'].'</b> (Solicitar cita previa)</li>';
				}
				
				$buttons_grupos .= '</ul>
									<hr>
									<h6> 
										<span class="fas fa-users fa-fw"></span> Equipo Educativo 
										<i id="minimize_miembros_'.$i.'" onclick="minimize(\'miembros\','.$i.')" style="cursor: pointer; display: none;float: right;" class="fas fa-window-minimize"></i> 
										<i id="maximize_miembros_'.$i.'" onclick="maximize(\'miembros\','.$i.')" style="cursor: pointer; float: right;"  class="fas fa-window-maximize"></i> 
									</h6>
									<ul id="miembros_'.$i.'" style="display: none">';
				foreach ($dato['equipo'] as $equipo) {
					$buttons_grupos .= '<li> <i class="far fa-user"></i> <b>'.$equipo['asignatura'].'</b>:'.$equipo['profesor'].'</li>';
					if ( $equipo['correo'] != '' ) {
						$buttons_grupos .= '<li><span class="fa fa-envelope"></span> <a href="mailto:'.$equipo['correo'].'">'.$equipo['correo'].'</a></li>';
					} 
				}
				$buttons_grupos .= '</ul><hr>'
						. '<h6><i class="fas fa-table"></i> <a style="font-size: 16px; color: #212529" href="horario.php?curso='.$dato['grupo'].'">Horario</a></h6>';
				if (count($dato['ficheros'])>0) {
					$buttons_grupos .= '<hr><h6><span class="far fa-folder"></span> Recursos: </h6> <ul>';
										
					foreach ($dato['ficheros'] as $fichero) { 	
						$buttons_grupos .= "<li><span class='far fa-file'></span> <a href='{$fichero['wwwroot']}' target='_blank'>{$fichero['name']}</a> </li>";
					}																					
					$buttons_grupos .= "</ul>";
				} 
				$buttons_grupos .= "</div></div></div></div>";
			} 
			$buttons_grupos .= "</div>";
		} 
		
		$buttons_etapas .= '</ul></div></nav>';
		
		echo $buttons_etapas;
		echo $buttons_grupos;
	?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>
