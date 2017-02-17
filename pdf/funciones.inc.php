<?

//////////
// Comprueba si es fecha en formato dd/mm/aaaa o dd-mm-aaaa
// false si no true si si lo es
//////////
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
#echo "$regs[3].$regs[2].$regs[1]";
			} else {
	return false;
			}
    }
#checkdate(mes,dia,año); 
}


////////////
// DAR LA VUELTA A LA FECHA
///////////
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

?>
