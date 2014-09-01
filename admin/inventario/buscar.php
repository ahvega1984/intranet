<?
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


?>

<?php
include("../../menu.php");
include("menu.php");
$PLUGIN_DATATABLES = 1;
?>
<?
if (empty($departamento) and stristr($_SESSION['cargo'],'4') == TRUE){
	$departamento=$_SESSION['dpt'];
	$departament=$departamento;
}
else{
	$departament="Direcci�n";
}
$profe=$_SESSION['profi'];
if (empty($buscar)) {
?>
<div class="container">
<div class="page-header">
<h2>Material del Centro <small> Buscar en el Inventario</small></h2>
</div>
<div class="row">
<div class="col-sm-6 col-sm-offset-3">
<div class="well" align="center">
            <form method="post" action="buscar.php">
                  <input type="text" name="expresion" id="exp" value="<? echo $expresion;?>" class="form-control" />
                  <br /><button type="submit"  name="buscar" value="Buscar datos" class="btn btn-primary"><i class="fa fa-search "> </i> Buscar datos </button>
            </form>
</div>
<br />
<?
		echo '<div class="well well-lg">INSTRUCCIONES.<BR><div style="text-align:left;width:inherit;">1. Puedes buscar en cualquier campo de la tabla de datos: familia, clase, lugar, descripci�n, marca, modelo, etc. <br>Si introduces varias palabras, se buscar�n los registros que contengan <em>todas</em> las palabras.<br>2. La(-s) palabra(-s) que introduzcas no tienen porque ser completas, as� que puedes escribir un trozo de palabra para aumentar los resultados de la b�squeda.<br>3. Por esa raz�n, si no escribes ning�n texto en el campo de b�squeda, se presentar�n todos los registros que has introducido, lo cual es interesante, por ejemplo, para imprimir un listado completo del material del Departamento. Los miembros del Equipo directivo ver�n, en este caso, la totalidad de los materiales registrados por todos los Departamentos y la propia Direcci�n,<br>4. Los nombres de las columnas de la tabla de resultados contienen un enlace que ordena los resultados de modo ascendente o descendente. Haciendo click sobre el nombre de una columna, podemos ordenar los resultados por familia, clase, modelo, etc.</div></div>';

		echo "</div></div></div>";
}
?>

<div class="container-fluid">
<div class="row">
<div class="col-sm-12">
<?
if ($ser) {$ser=" order by $ser";}else{$ser=" order by fecha";}
if ($orden=="desc") {$ord="asc";}else{$orden="asc";	$ord="desc";}
if ($buscar=="Buscar datos") {
$trozos = explode(" ",$expresion,5);
for($i=0;$i<5;$i++)
{
if(!(empty($trozos[$i]))){
$frase.=" and (familia like '%$trozos[$i]%' or inventario_clases.clase like '%$trozos[$i]%' or lugar like '%$trozos[$i]%' or descripcion like '%$trozos[$i]%' or marca like '%$trozos[$i]%' or modelo like '%$trozos[$i]%' or serie like '%$trozos[$i]%'  or departamento like '%$trozos[$i]%')";
}
}
if (strlen($departamento)>0) {
	$dep=" and departamento = '$departamento'";	
	}
$frase.=$dep;

$datos=mysql_query("select familia, inventario_clases.clase, lugar, descripcion, marca, modelo, serie, unidades, fecha, inventario.id, departamento from inventario, inventario_clases where inventario.clase=inventario_clases.id $frase $ser $orden");
//echo "select familia, inventario_clases.clase, lugar, descripcion, marca, modelo, serie, unidades, fecha, inventario.id from inventario, inventario_clases where inventario.clase=inventario_clases.id $frase $ser $orden";

if (mysql_num_rows($datos) > 0)
{
?>
<div class="page-header" align=center>
<h2>Material del Centro <small> Registros encontrados</small></h2>
</div>
<?
echo '<table class="table table-striped table-bordered datatable">
<thead>
<tr><th>Familia</th><th>Clase</th><th>Lugar</th><th>Descripci�n</th><th>Marca</th><th>Modelo</th><th nowrap>N� Serie</th><th>Unidad</th><th>Departamento</th><th></th><th></th></tr>
</thead><tbody>';
while($dat = mysql_fetch_row($datos))
{
$familia=$dat[0];	
$clase=$dat[1];
$lugar=$dat[2];
$descripcion=$dat[3];
$marca=$dat[4];
$modelo=$dat[5];
$serie=$dat[6];
$unidades=$dat[7];
$fecha=$dat[8];
$id=$dat[9];
$departamento=$dat[10];
?>
<tr><td><? echo $familia;?></td><td><? echo $clase;?></td><td><? echo $lugar;?></td><td><? echo $descripcion;?></td><td><? echo $marca;?></td><td><? echo $modelo;?></td><td><? echo $serie;?></td><td><? echo $unidades;?></td><td><? echo $departamento;?></td><td><a href="introducir.php?id=<? echo $id;?>&eliminar=1" data-bb='confirm-delete'><i class="fa fa-trash-o" title="Borrar" > </i> </a></td><td><a href="editar.php?id=<? echo $id;?>&departamento=<? echo $departamento;?>"><i class="fa fa-pencil" title="Modificar"> </i> </a></td></tr>
<?
}
	echo '</table>';
echo '</div>';
}
else
{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Ning�n registro del Inventario responde a tu criterio.
</div></div><br />';
}
}
?>
</div>
</div>
</div>
<?
	include("../../pie.php");
?>

	<script>
	$(document).ready(function() {
	  var table = $('.datatable').DataTable({
	  	"paging":   true,
	      "ordering": true,
	      "info":     false,
	      
	  		"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
	  		
	  		"order": [[ 1, "desc" ]],
	  		
	  		"language": {
	  		            "lengthMenu": "_MENU_",
	  		            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
	  		            "info": "P�gina _PAGE_ de _PAGES_",
	  		            "infoEmpty": "No hay resultados disponibles.",
	  		            "infoFiltered": "(filtrado de _MAX_ resultados)",
	  		            "search": "Buscar: ",
	  		            "paginate": {
	  		                  "first": "Primera",
	  		                  "next": "�ltima",
	  		                  "next": "",
	  		                  "previous": ""
	  		                }
	  		        }
	  	});
	});
	</script>
</body>
</html>