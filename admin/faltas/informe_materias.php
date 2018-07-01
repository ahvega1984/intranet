<?php
require('../../bootstrap.php');

$PLUGIN_DATATABLES = 1;

include("../../menu.php");
include("../../faltas/menu.php");

?>
<div class="container">

<div class="page-header">
  <h2>Informe sobre Faltas de Asistencia <small>Materia</small></h2>
</div>

<div id="status-loading" class="text-center">
    <br><br><span class="lead"><span class="far fa-circle-o-notch fa-spin"></span> Cargando datos...<br><small>El proceso puede tomar algún tiempo.</small><br><br></span>
</div>



<div id="wrap" class="row" style="display: none;">
	
	<?php include("menu_informes.php"); ?>
	
	  <br>
	  
	  <div class="alert alert-info">
	  	Junto al nombre de la asignatura, entre <strong>paréntesis</strong>, el número de alumnos que cursa la asignatura en ese nivel; en <strong>negrita</strong> la media por alumno de faltas en la asignatura; en <strong>rojo</strong> las faltas no justificadas; en <strong>verde</strong> las faltas justificadas.
	  </div>
	  
	  <br>


  <div class="col-md-10 col-md-offset-1">
    
<?php 
$nm=0;
$crs = mysqli_query($db_con,"select distinct nomcurso, unidades.idcurso from unidades, cursos where unidades.idcurso=cursos.idcurso order by idunidad");
while ($curs = mysqli_fetch_array($crs)) {

$curso=$curs[0];
$idcurso=$curs[1];

?> 
  <h3 class='text-info' align='center'><?php echo $curso;?></h3>
  <table class="table table-bordered table-vcentered datatable">
  <thead><tr>
      <th></th>
      <th>Trimestre 1º</th>
      <th>Trimestre 2º</th>
      <th>Trimestre 3º</th> 
  </tr></thead>
  <tbody>
<?php

$unidades = mysqli_query($db_con, "select distinct nombre, codigo from asignaturas where curso = '$curso' and abrev not like '%\_%' order by nombre");
while ($grp = mysqli_fetch_array($unidades)) {
  
  $num_asig = "";

  $cod_asig = $grp[1];
  $nom_asig = $grp[0];

  $comb = mysqli_query($db_con,"select * from alma where combasi like '%$cod_asig:%' and curso = '$curso'");
  $num_asig = mysqli_num_rows($comb);
  
  $navidadF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadF = mysqli_num_rows($navidadF);
  $navidadJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) < (select fecha from festivos where nombre like '% Navidad' limit 1)");
  $num_navidadJ = mysqli_num_rows($navidadJ);
  $total_navidad = ($num_navidadF+$num_navidadJ)/$num_asig;

  $santaF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '%Semana Santa' limit 1)");
  $num_santaF = mysqli_num_rows($santaF);
  $santaJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '% Navidad' limit 1) and date(fecha) < (select fecha from festivos where nombre like '%Semana Santa' limit 1)");
  $num_santaJ = mysqli_num_rows($santaJ);
  $total_santa = ($num_santaF+$num_santaJ)/$num_asig;

  $veranoF = mysqli_query($db_con,"select * from FALTAS where falta='F' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '%Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoF = mysqli_num_rows($veranoF);
  $veranoJ = mysqli_query($db_con,"select * from FALTAS where falta='J' and codasi = '$cod_asig' and date(fecha) > (select fecha from festivos where nombre like '%Semana Santa' limit 1) and date(fecha) < '".$config['curso_fin']."'");
  $num_veranoJ = mysqli_num_rows($veranoJ);
  $total_verano = ($num_veranoF+$num_veranoJ)/$num_asig;
?>
<tr>
  <td><h5><?php echo $nom_asig." (<SPAN class='text-info'>".$num_asig."</SPAN>)";?></h5></td>
  <td><?php echo "<b>".substr($total_navidad,0,4)."</b> (<span class='text-danger'>".$num_navidadF."</span><b> | </b><span class='text-success'>".$num_navidadJ."</span>)";?></td>
  <td><?php echo "<b>".substr($total_santa,0,4)."</b>  (<span class='text-danger'>".$num_santaF."</span><b> | </b><span class='text-success'>".$num_santaJ."</span>)";?></td>
  <td><?php echo "<b>".substr($total_verano,0,4)."</b>  (<span class='text-danger'>".$num_veranoF."</span><b> | </b><span class='text-success'>".$num_veranoJ."</span>)";?></td>
</tr>  
<?php
}
?>

</tbody>
<table>
<br>
<?php
}
?>

</div>
</div>
</div>

<?php 
include("../../pie.php");
?>  
 <script>
  $(document).ready(function() {
    var table = $('.datatable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false,
        
        "lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
        
        "order": [[ 1, "asc" ]],
        
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
function espera() {
  document.getElementById("wrap").style.display = '';
  document.getElementById("status-loading").style.display = 'none';        
}
window.onload = espera;
</script>  
</body>
</html>
