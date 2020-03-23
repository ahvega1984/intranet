<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('0', '1'));

$get_dir = limpiarInput($_GET['directorio'], 'alphanumeric');

$dirs = array('exporta0', 'exporta1', 'exporta2', 'exportaO', 'exportaE');
if (! isset($_GET['directorio']) || (isset($_GET['directorio']) && ! in_array($get_dir, $dirs))) {
  exit('No direct script access allowed');
}


include("../../menu.php");
?>
<br />
<div align="center">
<div class="page-header">
  <h2>Administración <small> Importación de calificaciones por Evaluación</small></h2>
</div>
<br />
<div class="well well-large">
<?php
// Eliminamos de la tabla los alumnos que se han dado de baja
mysqli_query($db_con,"delete from notas where claveal not in (select claveal1 from alma)");

// Importamos datos en la tabla.
$directorio = "../".$get_dir;

//echo $directorio."<br>";
if ($directorio=="../exporta0") {
	mysqli_query($db_con, "TRUNCATE TABLE notas");
}

// Recorremos directorio donde se encuentran los ficheros y aplicamos la plantilla.
if ($handle = opendir($directorio)) {
  while (false !== ($file = readdir($handle))) {
    if ($file != "." && $file != ".." && $file != "index.php") {

      $doc = new DOMDocument('1.0', 'utf-8');

      $doc->load($directorio.'/'.$file);

      $claves = $doc->getElementsByTagName( "ALUMNO" );

      foreach ($claves as $clave) {
        $clave2 = $clave->getElementsByTagName( "X_MATRICULA" );
        $clave3 = $clave2->item(0)->nodeValue;

        //Incorporamos alumnos que se han matriculado durante la última evaluación
        $vacio = 0;
        $hay = mysqli_query($db_con,"select * from notas where claveal='$clave3'");
        if (! mysqli_num_rows($hay)) {
          $vacio = 1;
        }

        $materias = $clave->getElementsByTagName( "MATERIA_ALUMNO" );
        if ($directorio=="../exporta0") {
          $cod = "INSERT INTO notas (claveal,notas0) VALUES ('$clave3', '";
        }
        if ($directorio=="../exporta1") {
          if ($vacio == 1) {
            $cod = "INSERT INTO notas (claveal,notas1) VALUES ('$clave3', '";
          }
          else{
            $cod = "update notas set notas1 = '";
          }
        }
        if ($directorio=="../exporta2") {
          if ($vacio == 1) {
            $cod = "INSERT INTO notas (claveal,notas2) VALUES ('$clave3', '";
          }
          else{
            $cod = "update notas set notas2 = '";
          }
        }
        if ($directorio=="../exportaO") {

          if ($vacio == 1) {
            $cod = "INSERT INTO notas (claveal,notas3) VALUES ('$clave3', '";
          }
          else{
            $cod = "update notas set notas3 = '";
          }
        }
        if ($directorio=="../exportaE") {
          if ($vacio == 1) {
            $cod = "INSERT INTO notas (claveal,notas4) VALUES ('$clave3', '";
          }
          else{
            $cod = "update notas set notas4 = '";
          }
        }

        foreach ($materias as $materia) {
          $codigos = $materia->getElementsByTagName( "X_MATERIAOMG" );
          $codigo = $codigos->item(0)->nodeValue;
          $notas = $materia->getElementsByTagName( "X_CALIFICA" );
          $nota = $notas->item(0)->nodeValue;
          $codigo.=":";
          $nota.=";";
          $cod.=$codigo.$nota;
        }

        if ($directorio=="../exporta0" or $vacio==1) {
          $cod.="')";
        }
        else{
          $cod.="' where claveal = '$clave3'";
        }

        mysqli_query($db_con, $cod);
        //echo $cod."<br>";
      }
    } 
  }

  closedir($handle);
  unlink($directorio.'/'.$file);   

  if ($directorio=="../exporta0") {
    mysqli_query($db_con, "insert into notas (claveal) select claveal1 from alma where claveal1 not in (select claveal from notas)");
  }

  echo '
  <div class="alert alert-success alert-block fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    Las Notas de Evaluación se han importado correctamente en la base de datos.
  </div>';
}
else {
  echo '
  <div class="alert alert-danger alert-block fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5>ATENCIÓN:</h5>
    Parece que no hay archivos en el directorio correspondiente.<br> O bien no has enviado el archivo correcto descargado de Séneca o bien el archivo está corrompido.
  </div>';
}
?>
<div align="center">
<input type="button" value="Volver atrás" name="boton" onclick="history.back(2)" class="btn btn-inverse" />
</div>
</div>
</div>
<?php include("../../pie.php");?>
</body>
</html>
