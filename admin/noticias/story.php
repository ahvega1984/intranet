<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);



?>
<?
include("../../menu.php");
include("menu.php");
?>
<div class="page-header">
  <h2>Noticias del Centro <small> Noticias en la base de datos</small></h2>
</div>

<div class="container-fluid">
<div class="row">
<div class="col-sm-10 col-sm-offset-1">
<? 
$id = $_GET['id'];
$connection = mysql_connect($db_host, $db_user, $db_pass) or die ("Imposible conectar!");
mysql_select_db($db) or die ("Imposible seleccionar base de datos!");

$query = "SELECT slug, content, contact, timestamp from noticias where id = '$id'";
$result = mysql_query($query) or die ("Error in query: $query. " . mysql_error());
$row = mysql_fetch_object($result);

if ($row)
{?>
<p class="lead text-warning" align="center">
<?          
            echo $row->slug;
?>
</p>
<br />
<div class="well">
<blockquote>
<?             	
			echo $row->content;
?>
 </blockquote>
 </div>
 <p>
            Publicada: <? echo fecha_actual($row->timestamp); ?><br />
            Autor: <? echo $row->contact; ?><br /></p>  
            <div align="center"><a href="../../index.php" class="btn btn-success">Volver a la p�gina principal</a></div>
<br /> 
  <?
}
else
{
?>
<div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>ATENCI�N:</h4>Esa noticia no se encuentra en la base de datos
          </div>
<?
}
mysql_close($connection);
?>
</div>
</div>
</div>
<? include("../../pie.php");?>
</body>
</html>
