<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

$elimina = "select distinct alma_seg.claveal, alma_seg.apellidos,
alma_seg.nombre, alma_seg.unidad from alma_seg, alma where 
alma_seg.claveal NOT IN (select distinct claveal from alma)";

$elimina1 = mysqli_query($db_con, $elimina);
if(mysqli_num_rows($elimina1) > 0)
{
	echo "<div align='left'><div class='alert alert-warning alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            Los siguientes alumnos han sido 
eliminados de la base de datos. <br>Comprueba los registros 
creados:</div></div>";
	while($elimina2 = mysqli_fetch_array($elimina1))
	{
		echo "<li>".$elimina2[2] . " " . $elimina2[1] . " -- " . $elimina2[3] . "</li>";
		$SQL16 = "DELETE FROM usuarioalumno where claveal = '$elimina2[0]'";
		$result16 = mysqli_query($db_con, $SQL16);
	}
}
echo "<br />";

$SQL1 = "select distinct alma.claveal, alma.apellidos,
alma.nombre, alma.unidad from alma where 
alma.claveal NOT IN (select distinct claveal from alma_seg)";
$result1 = mysqli_query($db_con, $SQL1);
$total = mysqli_num_rows($result1);
if ($total !== 0)
{
	echo "<div align='left'><div class='alert alert-success alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            Los nuevos alumnos han sido añadidos a 
la base de datos. <br>Comprueba en la lista de abajo los registros 
creados:</div></div>";

$nc_al="";

	while  ($row1= mysqli_fetch_array($result1))
	{

		echo "<li>".$row1[2] . " " . $row1[1] . " -- " . $row1[3] . " -- " . $numero ."</li>";

			// Usuario TIC
			$apellidos = $row1[1] ;
			$apellido = explode(" ",$row1[1] );
			$alternativo = strtolower(substr($row1[3],0,2));
			$nombreorig = $row1[2]  . " " . $row1[1] ;
			$nombre = $row1[2] ;
			$claveal = $row1[0] ;
			if (substr($nombre,0,1) == "Á") {$nombre =
			str_replace("Á","A",$nombre);}
			if (substr($nombre,0,1) == "É") {$nombre =
			str_replace("É","E",$nombre);}
			if (substr($nombre,0,1) == "Í") {$nombre =
			str_replace("Í","I",$nombre);}
			if (substr($nombre,0,1) == "Ó") {$nombre =
			str_replace("Ó","O",$nombre);}
			if (substr($nombre,0,1) == "Ú") {$nombre =
			str_replace("Ú","U",$nombre);}

			$apellido[0] = str_replace("Á","A",$apellido[0]);
			$apellido[0] = str_replace("É","E",$apellido[0]);
			$apellido[0] = str_replace("Í","I",$apellido[0]);
			$apellido[0] = str_replace("Ó","O",$apellido[0]);
			$apellido[0] = str_replace("Ú","U",$apellido[0]);
			$apellido[0] = str_replace("á","a",$apellido[0]);
			$apellido[0] = str_replace("é","e",$apellido[0]);
			$apellido[0] = str_replace("í","i",$apellido[0]);
			$apellido[0] = str_replace("ó","o",$apellido[0]);
			$apellido[0] = str_replace("ú","u",$apellido[0]);

			$userpass =
"a".strtolower(substr($nombre,0,1)).strtolower($apellido[0]);
			$userpass = str_replace("ª","",$userpass);
			$userpass = str_replace("ñ","n",$userpass);
			$userpass = str_replace("-","_",$userpass);
			$userpass = str_replace("'","",$userpass);
			$userpass = str_replace("º","",$userpass);
			$userpass = str_replace("ö","o",$userpass);

			$usuario  = $userpass;
			$passw = $userpass . preg_replace('/([ ])/e', 'rand(0,9)', '   ');
			$unidad = $row1[3];

			$repetidos = mysqli_query($db_con, "select usuario from usuarioalumno where
usuario like '$usuario%'");
			$n_a=0;
			while($num = mysqli_fetch_array($repetidos))
			{
				$n_a+=1;
			}
			$n_a+=1;
			$usuario = $usuario.$n_a;
			mysqli_query($db_con, "insert into usuarioalumno set nombre = \"".$nombreorig. "\",
usuario = \"".$usuario. "\", pass = '$passw', perfil = 'a', unidad = '$unidad', claveal 
= '$claveal'");
			echo "<li>TIC: ".$nombreorig . " " . $usuario . " -- " . $unidad . "  " .$claveal. "</li>";
		
	}
	echo "<br />";
}
else
{
	echo "<div align='left'><div class='alert alert-warning alert-block fade in'>
            <button type='button'' class='close' data-dismiss='alert'>&times;</button>
            No se ha encontrado ningun registro 
nuevo para añadir en la base de datos.<br>Si crees que hay un problema, ponte en 
contacto con quien sepa arreglarlo</div></div><br />";	
}

$cambio0 = mysqli_query($db_con, "select claveal, unidad, apellidos, nombre from
alma");
while($cambio = mysqli_fetch_array($cambio0)){
	$f_cambio0 = mysqli_query($db_con, "select unidad from usuarioalumno where claveal =
'$cambio[0]'");
	$f_cambio = mysqli_fetch_array($f_cambio0);
	if($cambio[1] == $f_cambio[0]){}
	else{
		mysqli_query($db_con, "update usuarioalumno set unidad = '$cambio[1]' where claveal = '$cambio[0]'");
	}	
}

//mysqli_query($db_con, "drop table alma");
