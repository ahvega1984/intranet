<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
?>
      <div class="page-header">
  <h2>Programa de Ayudas al Estudio <small> Importación de datos</small></h2>
</div>
<br />
<?php
if(isset($_POST['enviar']))
{
// Nivel de los Libros
if(substr($_FILES['archivo']['name'],0,1) == '1') {$nivel = '1º de E.S.O.';}
if(substr($_FILES['archivo']['name'],0,1) == '2') {$nivel = '2º de E.S.O.';}
if(substr($_FILES['archivo']['name'],0,1) == '3') {$nivel = '3º de E.S.O.';}
if(substr($_FILES['archivo']['name'],0,1) == '4') {$nivel = '4º de E.S.O.';}
$nombre_nivel = $_FILES['archivo']['name'];
 // Creamos Base de datos y enlazamos con ella.
 $base0 = "delete from textos_gratis where nivel = '$nivel'";
 mysqli_query($db_con, $base0);
// Importamos los datos del fichero CSV (todos_alumnos.csv) en la tabña alma.
$handle = fopen ($_FILES['archivo']['tmp_name'] , "r" ) or die("<br><blockquote>No se ha podido abrir el fichero.<br> Asegúrate de que su formato es correcto.</blockquote>");
$linea = 1;
while (($data1 = fgetcsv($handle, 1000, "|")) !== FALSE) 
{
if ($linea > 8) {
	$datos1 = "INSERT INTO textos_gratis (materia, isbn, ean, editorial, titulo, ano, caducado, importe, utilizado, nivel) VALUES (\"". trim(utf8_encode($data1[0])) . "\",\"". trim(utf8_encode($data1[1])) . "\",\"". trim(utf8_encode($data1[2])) . "\",\"". trim(utf8_encode($data1[3])) . "\",\"". trim(utf8_encode($data1[4])) . "\",\"". trim(utf8_encode($data1[5])) . "\",\"". trim(utf8_encode($data1[6])) . "\",\"". trim(utf8_encode($data1[7])) . "\",\"". trim(utf8_encode($data1[8])) . "\",\"". $nivel . "\")";
	// echo $datos1."<br>";
	mysqli_query($db_con, $datos1);
}
$linea++;
}
fclose($handle);
echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Tabla de Libros de Texto Gratuitos: los datos de '.$nombre_nivel.' han sido introducidos correctamente.
</div></div><br />';
}
?>
<div align="center">
<input type="button" name="Volver atrás" onclick="history.back(1)" class="btn btn-primary" value="Volver atrás"/>
</div>
</div>
</body>
</html>

