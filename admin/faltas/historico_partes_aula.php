<?php
require('../../bootstrap.php');
include("../../menu.php");
include("../../faltas/menu.php");
?>	

<?php
	$carpeta = '../../varios/faltas';
    if(is_dir($carpeta)){
        if($dir = opendir($carpeta)){
            while(($archivo = readdir($dir)) !== false){
                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess' && substr($archivo, 0,1)!=="."){
                	$archivos[$archivo] = $archivo;
                }
            }
            closedir($dir);
        }
    }
ksort ($archivos); 
?>

<div class="container">		

	<div class="page-header">
		<h2 style="display:inline">Faltas de Asistencia <small> Archivos digitalizados de los Partes de Aula</small></h2>
	</div>

	<div class="row">
		<div class="well col-md-4">
			<ul class="list-group">
				<?php 
				foreach ($archivos as $archivo) {  
					echo '<li class="list-group-item"><i class="far fa-file-pdf-o"> </i><a target="_blank" href="'.$carpeta.'/'.$archivo.'"> '.$archivo.'</a></li>';
					}  
				?>
			</ul>
		</div>
	</div>
</div>	

<?php
include("../../pie.php");
?>