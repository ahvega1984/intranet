<?php
	require('bootstrap.php');
	$result = mysqli_query($db_con, "SELECT id, horas FROM ausencias");
	while ($row = mysqli_fetch_array($result)) {
		$hora_string = (string)$row['horas'];
		$hora_guardar = '';
		for ($i=0;$i<strlen($hora_string);$i++) {
			$hora_guardar .= $hora_string[$i].',';
		}
		$hora_guardar = trim($hora_guardar, ',');
		$actualiza = mysqli_query($db_con, "update ausencias set horas = '$hora_guardar' where id = ".$row['id']);
	}
	
	
