<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

$GLOBALS['db_con'] = $db_con;

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
        
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    
    return $_SERVER['REMOTE_ADDR'];
}

function isPrivateIP($ip){
    return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

function getBrowser($u_agent) {
    if (empty($u_agent)) {
        $u_agent = 'Agente de usuario no detectado';
    }
    $bname = 'Navegador desconocido';
    $bversion= "";
    $ub = "Navegador desconocido";
    $platform = 'Dispositivo desconocido';
    $pname = "";
    $pversion= "";

    $u_agent = str_replace('; es-es', '', $u_agent);
    $u_agent = str_replace('; en-us', '', $u_agent);
    $u_agent = str_replace('; en-uk', '', $u_agent);
    
    // First get the platform?
    if (preg_match('/android/i', $u_agent)) {
        $platform_name = 'Android';
        $pname = 'Android';
    } elseif (preg_match('/ubuntu/i', $u_agent)) {
        $platform = 'Ubuntu / Guadalinex';
        $pname = 'Ubuntu';
    } elseif (preg_match('/Linux Mint/i', $u_agent)) {
        $platform = 'Linux Mint';
        $pname = 'Linux Mint';
    } elseif (preg_match('/x11; linux/i', $u_agent)) {
        $platform = 'GNU/Linux';
        $pname = 'GNU/Linux';
    } elseif (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
        $pname = 'Linux';
    } elseif (preg_match('/iPhone/i', $u_agent)) {
        $platform = 'iPhone iOS';
        $pname = 'iPhone OS';
    } elseif (preg_match('/iPad/i', $u_agent)) {
        $platform = 'iPad iOS';
        $pname = 'iPad; CPU OS';
    } elseif (preg_match('/mac os x 10_13|mac os x 10_12/i', $u_agent)) {
        $platform = 'macOS';
        $pname = 'Mac OS X';
    } elseif (preg_match('/mac os x 10_11|mac os x 10_10|mac os x 10_9/i', $u_agent)) {
        $platform = 'OS X';
        $pname = 'Mac OS X';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac OS X';
        $pname = 'Mac OS X';
    } elseif (preg_match('/windows nt 10/i', $u_agent)) {
        $platform = 'Windows 10';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.3/i', $u_agent)) {
        $platform = 'Windows 8.1';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.2/i', $u_agent)) {
        $platform = 'Windows 8';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.1/i', $u_agent)) {
        $platform = 'Windows 7';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 6.0/i', $u_agent)) {
        $platform = 'Windows Vista';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 5.1/i', $u_agent)) {
        $platform = 'Windows XP';
        $pname = 'Windows';
    } elseif (preg_match('/windows nt 5.0/i', $u_agent)) {
        $platform = 'Windows 2000';
        $pname = 'Windows';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
        $pname = 'Windows';
    }

    if ($pname != "" && $pname != 'Windows') {
        // finally get the correct version number
        $known = array($pname, $ub, 'other');
        if ($pname == 'Android') {
            $pattern = '#(?<platform>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|0-9_|a-zA-Z.|a-zA-Z_]*;[0-9]*([a-zA-Z]*[-| ])*[a-zA-Z]*[-| ]*[0-9]*[-]*[a-zA-Z]*[-]*[0-9]*)#';
        }
        else {
            $pattern = '#(?<platform>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|0-9_|a-zA-Z.|a-zA-Z_]*)#';
        }
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['platform']);
        if ($i > 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $pversion= str_replace('_', '.', $matches['version'][0]);
            } else {
                $pversion= str_replace('_', '.', $matches['version'][1]);
            }
        } elseif ($i == 1) {
            $pversion= str_replace('_', '.', $matches['version'][0]);
        }
        // check if we have a number
        if ($pversion==null || $pversion=="") {
            $pversion="";
        }
        elseif ($pname == 'Android') {
            $pversion = str_replace(' es-es; ', '', $pversion);
            $exp_pversion = explode(';', $pversion);
            $platform = ltrim(trim($exp_pversion[1]).' - Android', ' - ');
            $pversion = trim($exp_pversion[0]);
        }
    }
    

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
		$ub = "MSIE";
	} elseif(preg_match('/Edge/i',$u_agent)) {
        $bname = 'Microsoft Edge';
        $ub = "Edge";
    } elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Safari';
        $ub = "Safari";
    } elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
		$ub = "Opera";
	} elseif(preg_match('/Vivaldi/i',$u_agent)) {
        $bname = 'Vivaldi';
        $ub = "Vivaldi";
    } elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i > 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $bversion= $matches['version'][0];
        } else {
            $bversion= $matches['version'][1];
        }
    } elseif ($i == 1) {
        $bversion= $matches['version'][0];
    }
    // check if we have a number
    if ($bversion==null || $bversion=="") {$bversion="";}
    return array(
    'userAgent'         => $u_agent,
    'browser_name'      => $bname,
    'browser_version'   => $bversion,
    'platform_name'     => $pname,
    'platform'          => $platform,
    'platform_version'  => $pversion,
    'pattern'           => $pattern
    );
}

function registraPagina($db_link, $pagina)
{
	$pagina = str_ireplace("/intranet/","",$pagina);
	mysqli_query($db_link, "INSERT INTO reg_paginas (id_reg,pagina) VALUES ('".mysqli_real_escape_string($db_link, $_SESSION['id_pag'])."','".mysqli_real_escape_string($db_link, $pagina)."')");
}

function acl_permiso($cargo_usuario, $cargo_requerido) {
	
	$nopermitido = 0;
	$permitido = 0;
	
	if(empty($cargo_usuario)) {
		$nopermitido = 1;
	}
	else {
		if(is_array($cargo_requerido)) {
			for($i = 0; $i < strlen($cargo_usuario); $i++) {
				// Si alguno de los permisos coincide, prevalecerá el valor del flag 'permitido'.
				if(! in_array($cargo_usuario[$i], $cargo_requerido)) {
					$nopermitido = 1;
				}
				else {
					$permitido = 1;
				}
			}
		}
		else {
			// Convertimos a string si se trata de cualquier otro tipo de dato
			$cargo_requerido = (string) $cargo_requerido;
			
			if (stristr($cargo_usuario, $cargo_requerido) == FALSE) {
				$nopermitido = 1;
			}
		}	
	}
	
	// Si se activó el flag 'permitido' se permite el acceso a la página
	if ($permitido) {
		$nopermitido = 0;
	}
	
	return $nopermitido ? 0 : 1;
}


function acl_acceso($cargo_usuario, $cargo_requerido) {
	$tienePermiso = acl_permiso($cargo_usuario, $cargo_requerido);
	
	if (! $tienePermiso) {
		$db_con = $GLOBALS['db_con'];
		
		include(INTRANET_DIRECTORY . '/config.php');
		include(INTRANET_DIRECTORY . '/menu.php');
		echo "\t\t<div class=\"container\" style=\"margin-top: 80px; margin-bottom: 120px;\">\n";
		echo "\t\t\t<div class=\"row\">\n";
		echo "\t\t\t\t<div class=\"col-sm-offset-2 col-sm-8\">\n";
		echo "\t\t\t\t\t<div class=\"well text-center\">\n";
		echo "\t\t\t\t\t\t<span class=\"fa fa-hand-paper-o fa-5x\"></span>\n";
		echo "\t\t\t\t\t\t<h2 class=\"text-center\">¡Acceso prohibido!</h2>";
		echo "\t\t\t\t\t\t<hr>";
		echo "\t\t\t\t\t\t<p class=\"lead text-center\">No tiene privilegios para acceder a esta página.<br>Si cree que se trata de algún error, póngase en contacto con algún miembro del equipo directivo de su centro.</p>";
		echo "\t\t\t\t\t\t<hr>";
		echo "\t\t\t\t\t\t<a href=\"javascript:history.go(-1)\" class=\"btn btn-primary\">Volver atrás</a>";
		echo "\t\t\t\t\t</div>\n";
		echo "\t\t\t\t</div>\n";
		echo "\t\t\t</div>\n";
		echo "\t\t</div>\n";
		include(INTRANET_DIRECTORY . '/pie.php');
		echo "\t</body>\n";
		echo "</html>\n";
		exit();
	}
}

function redondeo($n){

	$entero10 = explode(".",$n);
	if (strlen($entero10[1]) > 2) {
		//redondeo o truncamiento según los casos

		if (substr($entero10[1],2,1) > 5){$n = $entero10[0].".". substr($entero10[1],0,2)+0.01;}
		else {$n = $entero10[0].".". substr($entero10[1],0,2);}
		echo $n;
	}
		
	else {echo $n;}
}

function media_ponderada($n){

	$entero10 = explode(".",$n);
	if (strlen($entero10[1]) > 2) {
		//redondeo o truncamiento según los casos

		if (substr($entero10[1],2,1) > 5){$n = $entero10[0].".". substr($entero10[1],0,2)+0.01;}
		else {$n = $entero10[0].".". substr($entero10[1],0,2);}
		return $n;
	}
		
	else {return $n;}
}

function tipo($db_con)
{
	$tipo = "select distinct tipo from listafechorias";
	$tipo1 = mysqli_query($db_con, $tipo);
	while($tipo2 = mysqli_fetch_array($tipo1))
	{
		echo "<OPTION>$tipo2[0]</OPTION>";
	}
}

function medida2($db_con, $tipofechoria)
{
	$tipo = "select distinct medidas2 from listafechorias where fechoria = '$tipofechoria'";
	$tipo1 = mysqli_query($db_con, $tipo);
	while($tipo2 = mysqli_fetch_array($tipo1))
	{
		$texto = trim($tipo2[0]);
		echo "$texto";
	}
}

function fechoria($db_con, $clase)
{
	$tipofechoria0 = "select fechoria from listafechorias where tipo = '$clase' order by fechoria";
	$tipofechoria1 = mysqli_query($db_con, $tipofechoria0);
	while($tipofechoria2 = mysqli_fetch_array($tipofechoria1))
	{
		echo "<option>$tipofechoria2[0]</option>";
	}
}

function unidad($db_con)
{
	// include("opt/e-smith/config.php");

	$tipo = "select distinct unidad from alma, unidades where nomunidad=unidad order by unidad ASC";
	$tipo1 = mysqli_query($db_con, $tipo);
	while($tipo2 = mysqli_fetch_array($tipo1))
	{
		echo "<option>".$tipo2[0]."</option>";
	}
}

function variables()
{
	foreach($_POST as $key => $val)
	{
		echo "$key --> $val<br>";
	}
}

// Comprueba si es fecha en formato dd/mm/aaaa o dd-mm-aaaa
// false si no true si si lo es
function es_fecha($fec)
{
	if (empty($fec))
	return false;
	else
	{
		# Tanto si es con / o con - la convertimos a -
		$fec = strtr($fec,"/","-");
		# la cortamos en trozos
		if (ereg("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})", $fec, $fec_ok)) {
			return checkdate($fec_ok[2],$fec_ok[1],$fec_ok[3]);
		} else {
			return false;
		}
	}
}

// DAR LA VUELTA A LA FECHA
function cambia_fecha($fec)
{
	if (empty($fec))
	return "";
	else
	{
		# Tanto si es con / o con - la convertimos a -
		$fec = strtr($fec,"/","-");
		# la cortamos en trozos
		$fec_ok=explode("-",$fec);
		# la devolvemos en el orden contrario
		return ($fec_ok[2]."-".$fec_ok[1]."-".$fec_ok[0]);
	}
}

/////////////
//Devuelve el numero de dia de la semana de la fecha
//////////////

function dia_dma($a)
{
if (es_fecha($a)){
					$a = strtr($a,"/","-");
					$a_ok=explode("-",$a);				
					$fecha = getdate(mktime(0,0,0,$a_ok[1],$a_ok[0],$a_ok[2]));
					if ($fecha['wday']!=0){return $fecha['wday'];}else{return 7;}
					}else{
					return '';
					}
}

function dia_amd($a)
{
$a=cambia_fecha($a);
return dia_dma($a);
}

function cambia_fecha_dia_mes($fec)
{
	if (empty($fec))
	return "";
	else
	{
		# Tanto si es con / o con - la convertimos a -
		$fec = strtr($fec,"/","-");
		# la cortamos en trozos
		$fec_ok=explode("-",$fec);
		# la devolvemos en el orden contrario
		return ($fec_ok[2]."-".$fec_ok[1]);
	}
}


function elmes($m){
	$mes["01"] = "enero";
	$mes["02"] = "febrero";
	$mes["03"] = "marzo";
	$mes["04"] = "abril";
	$mes["05"] = "mayo";
	$mes["06"] = "junio";
	$mes["07"] = "julio";
	$mes["08"] = "agosto";
	$mes["09"] = "septiembre";
	$mes["10"] = "octubre";
	$mes["11"] = "noviembre";
	$mes["12"] = "diciembre";
	return $mes[$m];
}

function formatea_fecha($fec){
	$fec = strtr($fec,"/","-");
	$fec_ok=explode("-",$fec);
	return ($fec_ok[2]." de ".elmes($fec_ok[1])." de ".$fec_ok[0]);
}

function formatDate($val)
{
	$arr = explode("-", $val);
	return date("d M Y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));

}

function fecha_actual($valor_fecha){
	$mes = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
	8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
	$dia = array("domingo", "lunes","martes","miércoles","jueves","viernes","sábado");
	$diames = date("j");
	$nmes = date("n");
	$ndia = date("w");
	$nano = date("Y");
	echo $diames." de ".$mes[$nmes].", ".$nano;
}

function fecha_actual3($valor_fecha){

	$arr0 = explode(" ", $valor_fecha);
	$arr = explode("-", $arr0[0]);
	$mes0 = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
	8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
	$dia0 = array("domingo", "lunes","martes","miércoles","jueves","viernes","sábado");
	$diames0 = date("j",mktime($arr[1],$arr[2],$arr[0]));
	$nmes0 = $arr[1];
	if(substr($nmes0,0,1) == "0"){$nmes0 = substr($nmes0,1,1);}
	$ndia0 = date("w",mktime($arr[1],$arr[2],$arr[0]));
	$nano0 = $arr[0];
	echo "$diames0 de ".$mes0[$nmes0];
}

function fecha_actual2($valor_fecha){
	$arr0 = explode(" ", $valor_fecha);
	$arr = explode("-", $arr0[0]);
	$mes0 = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
	8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
	$dia0 = array("domingo", "lunes","martes","miércoles","jueves","viernes","sábado");
	$diames0 = date("j",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
	$nmes0 = $arr[1];
	if(substr($nmes0,0,1) == "0"){$nmes0 = substr($nmes0,1,1);}
	$ndia0 = date("w",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
	$nano0 = $arr[0];
	return "$diames0 de ".$mes0[$nmes0].", $nano0";
}

function fecha_sin($valor_fecha){
	$arr0 = explode(" ", $valor_fecha);
	$arr = explode("-", $arr0[0]);
	$mes0 = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
	8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
	$diames0 = date("j",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
	$nmes0 = $arr[1];
	if(substr($nmes0,0,1) == "0"){$nmes0 = substr($nmes0,1,1);}
	$ndia0 = date("w",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
	$nano0 = $arr[0];
	echo "$diames0 de ".$mes0[$nmes0].", $nano0";
}

// Eliminar nombre de profesor con mayúsculas completo
function eliminar_mayusculas(&$n_profeso) {
	$n_profeso = mb_convert_case($n_profeso, MB_CASE_TITLE, "UTF-8");
}


function nomprofesor($nombre) {
	return mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
}

function obtener_nombre_profesor_por_idea($idea) {
	$result = mysqli_query($GLOBALS['db_con'], "SELECT nombre FROM departamentos WHERE idea = '".$idea."' LIMIT 1");
	$row = mysqli_fetch_array($result);
	return $row['nombre'];
}


function obtener_idea_por_nombre_profesor($nombre) {
	$result = mysqli_query($GLOBALS['db_con'], "SELECT idea FROM departamentos WHERE nombre = '".$nombre."' LIMIT 1");
	$row = mysqli_fetch_array($result);
	return $row['idea'];
}

function size_convert($size)
{
    $unit=array('B','KB','MB','GB','TB','PB');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function iniciales($str) {
    $ret = '';
    $str = str_ireplace(" de ", " ", $str);
    $str = str_ireplace(" del ", " ", $str);
    $str = str_ireplace(" de la ", " ", $str);
    $str = str_ireplace(" la ", " ", $str);
    $str = str_ireplace(" el ", " ", $str);
    $str = str_ireplace(" y ", " ", $str);
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}

function obtener_foto_alumno($claveal) {
	$directorio_fotos = INTRANET_DIRECTORY."/xml/fotos/";
	$ruta_foto_alumno = "";

	foreach (glob($directorio_fotos . $claveal . "*") as $foto) {
		$ruta_foto_alumno = $foto;
	}

	if (file_exists($ruta_foto_alumno)) {
		$exp_ruta_foto_alumno = array_reverse(array_map('strrev', explode('.', strrev($ruta_foto_alumno), 2)));
		$nombre = str_replace($directorio_fotos, '', $exp_ruta_foto_alumno[0]);
		$extension = $exp_ruta_foto_alumno[1];

		return $nombre.'.'.$extension;
	}
	else {
		return false;
	}
}

unset($GLOBALS['db_con']);
