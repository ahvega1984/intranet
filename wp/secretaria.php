<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
		<style>
			.capa1 {
				background-color: #68B04D;
			}
			.capa2 {
				background-color: #605E5E;
				color: #fff;
			}
			.capa2 a {
				color: #fff;
			}
			.capa3 {
				background-color: #3D9BE9;
			}
			.col-md-4 {
				padding: 40px;
				text-align: center;
			}
			i {
				color: #fff;
			}
			i:hover {
				color: #000;
			}
			#capa_matriculas {
				background-color: #68B04D;
			}
			#capa_becas {
				background-color: #605E5E;
			}
			#capa_tramites {
				background-color: #3D9BE9;
			}
			.capa_desplegable {
				padding: 10px;
				color: #fff;
				display: none;
				min-height: 300px;
			}
		</style>
		
		<script>
			function abrir (id) {
				$($("[id^='capa_']")).hide();				
				$('#capa_'+id).fadeIn("slow");
			}
		</script>
		
    </head>
    <body>	
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 capa1">
					<!--a class="btn" onclick="abrir('matriculas')"><i class="fa fa-user-plus fa-7x"></i></a-->
					<a class="btn" href="https://www.iesrioverde.es/matriculas/" target="_parent"><i class="fa fa-user-plus fa-7x"></i></a>
					<p></p>
					<h2>Matriculas</h2>
					<p></p>
				</div>
				<div class="col-md-4 capa2">
					<!--a class="btn" onclick="abrir('becas')"><i class="fa fa-graduation-cap fa-7x"></i></a-->
					<a class="btn" href="https://www.iesrioverde.es/becas/" target="_parent"><i class="fa fa-graduation-cap fa-7x"></i></a>
					<p></p>
					<h2>Becas</h2>
					<p></p>
				</div>
				<div class="col-md-4 capa3">
					<!--a class="btn" onclick="abrir('tramites')"><i class="fa fa-paperclip fa-7x"></i></a-->
					<a class="btn" href="https://www.iesrioverde.es/otros-tramites/" target="_parent"><i class="fa fa-paperclip fa-7x"></i></a>
					<p></p>
					<h2>Otros trámites</h2>
					<p></p>
				</div>
			</div>			
		</div>
		
		<!--div id='capa_matriculas' class="capa_desplegable">			
			<h1>Matriculas</h1>
			It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
		</div>
		
		<div id='capa_becas' class="capa_desplegable">			
			<h1>Becas</h1>
			It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
		</div>
		
		<div id='capa_tramites' class="capa_desplegable">			
			<h1>Otros trámites</h1>
			It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
		</div-->
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>
