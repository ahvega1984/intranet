<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<style type="text/css">
			.departamento {
				border-width: 2px;
				font-weight: 500;
				font-size: 0.8571em;
				line-height: 1.35em;
				margin: 5px 1px;
				border: none;
				border-radius: 0.1875rem;
				padding: 11px 22px 20px;
				cursor: pointer;
				background-color: #888;
				color: #FFFFFF;
			}
			.btn {
				margin-bottom: 5px;
				text-align: left;
				width: 100%;
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
			.modal-header {
				background: #3D9BE9;
				color: #fff;
			}
			.modal-header .close{
				color: #fff;
			}
			.modal-body {
				background-color: #ecf0f1;				
			}
			.btn-primary {
				background-color: #3D9BE9;
			}
			
		</style>		
		<script type="text/javascript">
			function maximize(type, num) {
				//$("#"+type+"_"+num).show();
				$("#"+type+"_"+num).fadeIn("slow");
				$("#minimize_"+type+"_"+num).show();
				$("#maximize_"+type+"_"+num).hide();
			}
			function minimize(type, num) {
				//$("#"+type+"_"+num).hide();
				$("#"+type+"_"+num).fadeOut("slow");
				$("#minimize_"+type+"_"+num).hide();
				$("#maximize_"+type+"_"+num).show();
			}
		</script>		
	</head>
	<body>
	<?php	
		include 'lib.php';
		$datos = getDepartamentos();
		?>
		<br>
		<div class="row">
			<?php
			$i = 0;
			foreach ($datos as $dato) {				
				$i++;
				?>
				<!-- Button trigger modal -->
				<div class="col-sm-12 col-md-6 col-lg-4">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?= $i ?>">
						<span class="fas <?= $dato['icon'] ?> fa-fw fa-lg"></span> <?= $dato['name'] ?>
					</button>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="exampleModal<?= $i ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel"> <span class="fas <?= $dato['icon'] ?> fa-fw fa-lg"></span> <?= $dato['name'] ?> </h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<h6> 
									<span class="fas fa-users fa-fw"></span> Miembros 
									<i id="minimize_miembros_<?=$i?>" onclick="minimize('miembros',<?=$i?>)" style="cursor: pointer; display: none;float: right;" class="fas fa-window-minimize"></i> 
									<i id="maximize_miembros_<?=$i?>" onclick="maximize('miembros',<?=$i?>)" style="cursor: pointer; float: right;"  class="fas fa-window-maximize"></i> 
								</h6>
								<ul id="miembros_<?=$i?>" style="display: none">
									<?php foreach ($dato['miembros'] as $miembro) { ?> 
										<li> 
											<?php if (isset($miembro['cargo'])) { ?> <b> <?php } ?> 
											<span class="far fa-user"></span> <?= $miembro['nombre'] ?>	
											<?php if (isset($miembro['cargo'])) { echo "(".$miembro['cargo'].")";?> </b> <?php } ?> 
										
											<?php if (isset($miembro['correo']) && $miembro['correo']!='') { ?>
											<br>
											<span class='fa fa-envelope'></span> <a href='mailto:<?=$miembro['correo']?>'><?=$miembro['correo']?></a>
											<?php } ?>
										</li>
										
									<?php } ?> 
								</ul>
								
								<?php if (count($dato['ficheros']) > 0) { ?>
								<hr>
								<h6>
									<span class="far fa-folder"></span> &nbsp; Programaciones 
									<i id="minimize_programaciones_<?=$i?>" onclick="minimize('programaciones',<?=$i?>)" style="cursor: pointer; display: none;float: right;" class="fas fa-window-minimize"></i> 
									<i id="maximize_programaciones_<?=$i?>" onclick="maximize('programaciones',<?=$i?>)" style="cursor: pointer; float: right;"  class="fas fa-window-maximize"></i> 
								</h6>
								<ul id="programaciones_<?=$i?>" style="display: none">
									<?php 
										foreach ($dato['ficheros'] as $fichero) { 	
											echo "<li><span class='far fa-file'></span> <a href='{$fichero['wwwroot']}' target='_blank'>{$fichero['name']}</a> </li>";												
										}
									?> 
								</ul>
								<?php } ?>
							</div>

						</div>
					</div>
				</div>
			<?php } ?>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>
