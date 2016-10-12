<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../../menu.php");
?>
<br />
<div class="container">
<div class="row">

<div class="page-header">
  <h2>Administraci&oacute;n <small> Depuraci&oacute;n y limpieza de las tablas de Horarios</small></h2>
</div>
<br />
<div class="text-center" id="t_larga_barra">
	<span class="lead"><span class="fa fa-circle-o-notch fa-spin"></span> Cargando...</span>
</div>
<div id='t_larga' style='display:none' >

<div class="well well-large" style="width:700px;margin:auto;text-align:left">
<?php
// Colocar codigos y nombre de asignaturas de Horw de acuerdo con Seneca (tabla Profesores)

$sql = mysqli_query($db_con, "select id, prof, a_grupo, a_asig, asig, c_asig from horw where a_grupo not like '' and c_asig not in (select idactividad from actividades_seneca)");
while($row = mysqli_fetch_array($sql))
{
$curso = substr($row[2],0,1);
$id = $row[0];
$prof = $row[1];
$a_grupo = substr($row[2],0,4);
$a_asig = $row[3];
$asig = $row[4];
$c_asig =  $row[5];
$abrev_asig = substr($row[4],0,1);
$cur=mysqli_query($db_con, "select distinct curso from alma where unidad= '$a_grupo'");
$cur0=mysqli_fetch_array($cur);
$curs2=$cur0[0];
$asig0 = mysqli_query($db_con, "select distinct materia, abrev, codigo, curso from profesores, asignaturas, horw where profesor = prof and profesores.nivel = asignaturas.curso and materia = nombre and grupo = '$a_grupo' and id= '$id' and abrev not like '%\_%' and curso like '%$curs2%' and c_asig not in (select idactividad from actividades_seneca)");
$codigo="";
while($asigna = mysqli_fetch_array($asig0))
{
  $materia= $asigna[0];
  $abrev= $asigna[1];
  $codigo= $asigna[2];
  $curs= $asigna[3];

  if(mysqli_num_rows($asig0) == 1)
  {
    $num+=1;
    //  echo "Unidad única.<br>";
    mysqli_query($db_con, "insert into horw_var select * from horw where id='$id'");
    mysqli_query($db_con, "update horw_var set clase='Actualizado' where id='$id'");
    mysqli_query($db_con, "update horw set a_asig = '$abrev', c_asig = '$codigo', asig = '$materia' where id= '$id'");
    // echo "$id => $prof => $materia => $asig => $abrev => $codigo => $c_asig => $a_asig => $a_grupo => $curs<br>";
  }

  elseif ($codigo==$c_asig) {
    $num+=1;
    //  echo "Codigos iguales<br>";
    mysqli_query($db_con, "insert into horw_var select * from horw where id='$id'");
    mysqli_query($db_con, "update horw_var set clase='Codigo +' where id='$id'");
    mysqli_query($db_con, "update horw set a_asig = '$abrev', asig = '$materia' where id= '$id'");
  }

  else
  {
    $materia2 = mb_strtoupper($materia);
    $asig2 = mb_strtoupper($asig);
    similar_text($asig2, $materia2, $percent2);
    if($percent2>"75")
    {
      $num+=1;
      mysqli_query($db_con, "insert into horw_var select * from horw where id='$id'");
      mysqli_query($db_con, "update horw_var set clase='$percent2' where id='$id'");
      mysqli_query($db_con, "update horw set a_asig = '$abrev', c_asig = '$codigo', asig = '$materia' where id= '$id'");
      $codigo="";
    }
    else
    {
      if (!(empty($a_grupo))) {
        mysqli_query($db_con, "insert into horw_var select * from horw where id='$id'");
        mysqli_query($db_con, "update horw_var set clase='Sin corresp' where id='$id'");
      }
    }
  }
}
}
mysqli_query($db_con, "OPTIMIZE TABLE `horw`");

// Tabla donde recoger los datos eliminados del horario por si es necesario recuperalos
mysqli_query($db_con,"create table if not exists horw_backup select * from horw where hora='99'");

// Limpiamos Tabla de Horarios de grupos que no da el profesor
echo "<p class='lead text-important text-danger' style='text-align:left'>Profesores y Asignaturas de<strong> Horw </strong>que no aparecen en S&eacute;neca.</p>";

$hor0 = "select id, prof, a_grupo, asig from horw where a_grupo in (select nomunidad from unidades) and asig not like 'OPTATIVA EXENTOS'";
$hor1 = mysqli_query($db_con, $hor0);
echo "<ul>";
while($hor = mysqli_fetch_array($hor1))
{
$id = $hor[0];
$profesor = $hor[1];
$grupo = $hor[2];
$materia = $hor[3];

$prof0 = "select * from profesores where profesor = '$profesor' and grupo = '$grupo'";
$prof1 = mysqli_query($db_con, $prof0);
if(mysqli_num_rows($prof1) < 1)
{
echo "<li>Borrado: $profesor => $materia  => $grupo</li>";
mysqli_query($db_con,"insert into horw_backup select * from horw where id='$id'");
mysqli_query($db_con, "delete from horw where id = '$id'");
}
}

echo "</ul>";
mysqli_query($db_con, "OPTIMIZE TABLE `horw`");  

// creamos Horw para las Faltas
$base0 = "DROP TABLE horw_faltas";
mysqli_query($db_con, $base0);
mysqli_query($db_con, "create table horw_faltas select * from horw where a_grupo not like '' and c_asig not in (select distinct idactividad from actividades_seneca where idactividad not like '2' and idactividad not like '21')");
//Elimina las horas no lectivas
mysqli_query($db_con, $nolectiva);
mysqli_query($db_con, "ALTER TABLE ".$db."horw_faltas ADD INDEX (`prof`)");
mysqli_query($db_con, "ALTER TABLE ".$db."horw_faltas ADD index (`c_asig`)");
mysqli_query($db_con, "OPTIMIZE TABLE  `horw_faltas`");
//Profes que estan en horw y no en profesores
echo '<br /><div class="alert alert-warning alert-block fade in">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      Profesores en Horw que no aparecen en la tabla Profesores
creados desde S&eacute;neca (probablemente por no tener horario lectivo):<br><ul>';
$pro0 = "select distinct prof from horw where prof not in (select distinct profesor from profesores)";
$pro1 = mysqli_query($db_con, $pro0);
while($pro = mysqli_fetch_array($pro1))
{
if(!(empty($pro[0])))
echo "<li>$pro[0]</li>";
}
echo "</ul></div>";
echo '<br /><div align="center"><div class="alert alert-success alert-block fade in"><br />
      <button type="button" class="close" data-dismiss="alert">&times;</button>
Tabla <strong>Profesores</strong>: los datos se han introducido correctamente en la Base de datos. Es necesario que actualizes las tablas de Departamentos, una vez actualizados los Profesores.<br>Vuelve a la p&aacute;gina de Administraci&oacute;n y actualiza los Departamentos inmediatamente.
</div></div>';

?>
<div align="center">
  <a  href="../index.php" class="btn btn-primary" />Volver a Administración</a>
</div>
</div>
</div>
</div>
</div>

 <?php include("../../pie.php");?>
  <script>
function espera( ) {
        document.getElementById("t_larga").style.display = '';
        document.getElementById("t_larga_barra").style.display = 'none';        
}
window.onload = espera;
</script>  
</body>
</html>
