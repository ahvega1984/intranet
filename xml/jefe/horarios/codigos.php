<?php
require('../../../bootstrap.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<?php

$result = mysqli_query($db_con, "SELECT idactividad, nomactividad FROM actividades_seneca");
while ($row = mysqli_fetch_array($result)) {
	$exp_nomactividad = explode('(', $row['nomactividad']);
	
	$exp_nomactividad = str_replace(' a ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' al ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' el ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' la ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' las ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' los ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' de ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' En ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' en ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' su ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' del ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' Del ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' con ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' que ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' y ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace('.', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(',', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace('-', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' para ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' cuando ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' caso ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' como ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' no ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' tengan ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' otros ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' determine ', ' ', $exp_nomactividad);
	$exp_nomactividad = str_replace(' correspondientes ', ' ', $exp_nomactividad);
	
	$nomactividad = mb_convert_case($exp_nomactividad[0], MB_CASE_TITLE, 'UTF-8');
	
	$abrev = "";
	for ($i = 0; $i < strlen($nomactividad); $i++) {
		if ($nomactividad[$i] == mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'UTF-8') && $nomactividad[$i] != " " && $nomactividad[$i] != ".") {
			$abrev .= mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'UTF-8');
		}
	}
	
	if (strlen($abrev) < 3) {
		$exp_nomactividad = explode(' ', $nomactividad);
		$abrev .= $exp_nomactividad[1][1].$exp_nomactividad[1][2];
		$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'UTF-8');
	}
	
	if (strlen($abrev) < 2) {
		$exp_nomactividad = explode(' ', $nomactividad);
		$abrev .= $exp_nomactividad[0][1].$exp_nomactividad[0][2];
		$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'UTF-8');
	}

	
	echo $abrev . ' --> ' . $row['nomactividad'] . '<br>';
}
?>