<?php
require('../bootstrap.php');

if (isset($_GET['recurso'])) {
	$nombre_rec = $_GET['recurso'];
}

include("../menu.php");
include("menu.php");
?>

<div class="container">

	<div class="page-header">
	  <h2>Sistema de Reservas <small> <?php echo $nombre_rec; ?></small></h2>
	</div>

<?php

if (isset($_GET['month'])) { $month = $_GET['month']; $month = preg_replace ("/[[:space:]]/", "", $month); $month = preg_replace ("/[[:punct:]]/", "", $month); $month = preg_replace ("/[[:alpha:]]/", "", $month); }
if (isset($_GET['year'])) { $year = $_GET['year']; $year = preg_replace ("/[[:space:]]/", "", $year); $year = preg_replace ("/[[:punct:]]/", "", $year); $year = preg_replace ("/[[:alpha:]]/", "", $year); if ($year < 1990) { $year = 1990; } if ($year > 2035) { $year = 2035; } }
if (isset($_GET['today'])) { $today = $_GET['today']; $today = preg_replace ("/[[:space:]]/", "", $today); $today = preg_replace ("/[[:punct:]]/", "", $today); $today = preg_replace ("/[[:alpha:]]/", "", $today); }


$month = (isset($month)) ? $month : date("n",time());
$year = (isset($year)) ? $year : date("Y",time());
$today = (isset($today))? $today : date("j", time());
$daylong = date("l",mktime(1,1,1,$month,$today,$year));
$monthlong = date("F",mktime(1,1,1,$month,$today,$year));
$dayone = date("w",mktime(1,1,1,$month,1,$year))-1;
$numdays = date("t",mktime(1,1,1,$month,1,$year));
$alldays = array('L','M','X','J','V','S','D');
$next_year = $year + 1;
$last_year = $year - 1;
    if ($daylong == "Sunday")
	{$daylong = "Domingo";}
    elseif ($daylong == "Monday")
	{$daylong = "Lunes";}
    elseif ($daylong == "Tuesday")
	{$daylong = "Martes";}
    elseif ($daylong == "Wednesday")
	{$daylong = "Miércoles";}
    elseif ($daylong == "Thursday")
	{$daylong = "Jueves";}
    elseif ($daylong == "Friday")
	{$daylong = "Viernes";}
    elseif ($daylong == "Saturday")
	{$daylong = "Sábado";}


    if ($monthlong == "January")
	{$monthlong = "Enero";}
    elseif ($monthlong == "February")
	{$monthlong = "Febrero";}
    elseif ($monthlong == "March")
	{$monthlong = "Marzo";}
    elseif ($monthlong == "April")
	{$monthlong = "Abril";}
    elseif ($monthlong == "May")
	{$monthlong = "Mayo";}
    elseif ($monthlong == "June")
	{$monthlong = "Junio";}
    elseif ($monthlong == "July")
	{$monthlong = "Julio";}
    if ($monthlong == "August")
	{$monthlong = "Agosto";}
    elseif ($monthlong == "September")
	{$monthlong = "Septiembre";}
    elseif ($monthlong == "October")
	{$monthlong = "Octubre";}
    elseif ($monthlong == "November")
	{$monthlong = "Noviembre";}
    elseif ($monthlong == "December")
	{$monthlong = "Diciembre";}
if ($today > $numdays) { $today--; }

$primero = 0;
$rc = mysqli_query($db_con, "select reservas_tipos.id, tipo, elemento, id_tipo, reservas_elementos.observaciones from reservas_tipos, reservas_elementos where reservas_tipos.id = reservas_elementos.id_tipo and tipo = '$recurso'");
	while ($srv = mysqli_fetch_array($rc)) {
		$ci+=1;
		$servicio = $srv[2];
		$lugar = $srv[4];

if ($ci == 1 or $ci == 4 or $ci == 7 or $ci == 10 or $ci == 13 or $ci == 16){
	echo ($primero) ? '</div> <hr>' : '';
	echo '<div class="row">';
	$primero = 1;
}

?>
<div class="col-sm-4">
	<a name="<?php echo $servicio; ?>"></a>
	<h3 class="text-center"><?php echo $servicio;?></h3>
	<h4><small><?php echo $lugar; ?></small></h4>

	<table class="table table-bordered table-centered">
		<thead>
			<tr>
				<th colspan="7"><h4><?php echo $monthlong; ?></h4></th>
			</tr>
			<tr>
				<?php foreach ($alldays as $value): ?>
				<th><?php echo $value; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
<?php
//Días vacíos
if ($dayone < 0) $dayone = 6;
for ($i = 0; $i < $dayone; $i++) {
  echo "<td>&nbsp;</td>";
}


//Días
for ($zz = 1; $zz <= $numdays; $zz++) {
  if ($i >= 7) {  print("</tr><tr>"); $i=0; }
  // Mirar a ver si hay alguna ctividad en el días
  $result_found = 0;
  if ($zz == $today) {
    echo "<td class=\"calendar-today\">$zz</td>";
    $result_found = 1;
  }

  if ($result_found != 1) {
		//Buscar actividad para el día y marcarla
		$sql_currentday = "$year-$month-$zz";
    	$eventQuery = "SELECT event1, event2, event3, event4, event5, event6, event7, event8, event9, event10, event11, event12, event13, event14 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio = '$servicio'";
 		$eventExec = mysqli_query($db_con, $eventQuery );
		if (mysqli_num_rows($eventExec)>0) {
			while ( $row = mysqli_fetch_array ( $eventExec ) ) {
echo "<td class=\"calendar-orange\">$zz</td>";
$result_found = 1;
		}
		}
		else{
		$sql_currentday = "$year-$month-$zz";
		$fest = mysqli_query($db_con, "select distinct fecha, nombre from $db.festivos WHERE fecha = '$sql_currentday'");
		if (mysqli_num_rows($fest)>0) {
		$festiv=mysqli_fetch_array($fest);
			        echo "<td class=\"calendar-red\">$zz</a></td>\n";
				$result_found = 1;
				}
		}

	}

  if ($result_found != 1) {
    echo "<td>$zz</td>";
  }

  $i++; $result_found = 0;
}

$create_emptys = 7 - (($dayone + $numdays) % 7);
if ($create_emptys == 7) { $create_emptys = 0; }

if ($create_emptys != 0) {
  echo "<td colspan=\"$create_emptys\">&nbsp;</td>";
}

echo "</tr>";
echo "</table>";
?>
	<div class="well">
		<h4 class="text-info">Próximos días</h4>
<?php
for ($i = $today; $i <= ($today + 6); $i++) {
  $current_day = $i;
  $current_year = $year;
  $current_month = $month;
  if ($i > $numdays) {
    $current_day = ($i - $numdays);
    $current_month = $month + 1;
    if ($current_month > 12) {
      $current_month = 1; $current_year = $year + 1;
    }
  }
  $dayname = date("l",mktime(1,1,1,$current_month,$current_day,$current_year));
    if ($dayname == "Sunday")
	{$dayname = "Domingo";}
    elseif ($dayname == "Monday")
	{$dayname = "Lunes";}
    elseif ($dayname == "Tuesday")
	{$dayname = "Martes";}
    elseif ($dayname == "Wednesday")
	{$dayname = "Miércoles";}
    elseif ($dayname == "Thursday")
	{$dayname = "Jueves";}
    elseif ($dayname == "Friday")
	{$dayname = "Viernes";}
    elseif ($dayname == "Saturday")
	{$dayname = "Sábado";}

    $sql_currentday = "$current_year-$current_month-$current_day";
    $eventQuery = "SELECT event1, event2, event3, event4, event5, event6, event7, event8, event9, event10, event11, event12, event13, event14 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio = '$servicio'";
    $eventExec = mysqli_query($db_con, $eventQuery);
    while($row = mysqli_fetch_array($eventExec)) {
   if (mysqli_num_rows($eventExec) == 1) {
        // $this_days_title = stripslashes($row["title"]);
   $event_event1 = stripslashes($row["event1"]);
	 $event_event1 = stripslashes($row["event1"]);
   if (stristr($event_event1, '||') == true) {
     $exp_event_event1 = explode("||", $event_event1);
     $event_event1_profesor = $exp_event_event1[0];
     $event_event1_observacion = $exp_event_event1[1];
   }
   else {
     $event_event1_profesor = $event_event1;
   }
   $event_event2 = stripslashes($row["event2"]);
   if (stristr($event_event2, '||') == true) {
     $exp_event_event2 = explode('||', $event_event2);
     $event_event2_profesor = $exp_event_event2[0];
     $event_event2_observacion = $exp_event_event2[1];
   }
   else {
     $event_event2_profesor = $event_event2;
   }
   $event_event3 = stripslashes($row["event3"]);
   if (stristr($event_event3, '||') == true) {
     $exp_event_event3 = explode('||', $event_event3);
     $event_event3_profesor = $exp_event_event3[0];
     $event_event3_observacion = $exp_event_event3[1];
   }
   else {
     $event_event3_profesor = $event_event3;
   }
   $event_event4 = stripslashes($row["event4"]);
   if (stristr($event_event4, '||') == true) {
     $exp_event_event4 = explode('||', $event_event4);
     $event_event4_profesor = $exp_event_event4[0];
     $event_event4_observacion = $exp_event_event4[1];
   }
   else {
     $event_event4_profesor = $event_event4;
   }
   $event_event5 = stripslashes($row["event5"]);
   if (stristr($event_event5, '||') == true) {
     $exp_event_event5 = explode('||', $event_event5);
     $event_event5_profesor = $exp_event_event5[0];
     $event_event5_observacion = $exp_event_event5[1];
   }
   else {
     $event_event5_profesor = $event_event5;
   }
   $event_event6 = stripslashes($row["event6"]);
   if (stristr($event_event6, '||') == true) {
     $exp_event_event6 = explode('||', $event_event6);
     $event_event6_profesor = $exp_event_event6[0];
     $event_event6_observacion = $exp_event_event6[1];
   }
   else {
     $event_event6_profesor = $event_event6;
   }
   $event_event7 = stripslashes($row["event7"]);
   if (stristr($event_event7, '||') == true) {
     $exp_event_event7 = explode('||', $event_event7);
     $event_event7_profesor = $exp_event_event7[0];
     $event_event7_observacion = $exp_event_event7[1];
   }
   else {
     $event_event7_profesor = $event_event7;
   }
	 $event_event8 = stripslashes($row["event8"]);
   if (stristr($event_event8, '||') == true) {
     $exp_event_event8 = explode('||', $event_event8);
     $event_event8_profesor = $exp_event_event8[0];
     $event_event8_observacion = $exp_event_event8[1];
   }
   else {
     $event_event8_profesor = $event_event8;
   }
	 $event_event9 = stripslashes($row["event9"]);
   if (stristr($event_event9, '||') == true) {
     $exp_event_event9 = explode('||', $event_event9);
     $event_event9_profesor = $exp_event_event9[0];
     $event_event9_observacion = $exp_event_event9[1];
   }
   else {
     $event_event9_profesor = $event_event9;
   }
	 $event_event10 = stripslashes($row["event10"]);
   if (stristr($event_event10, '||') == true) {
     $exp_event_event10 = explode('||', $event_event10);
     $event_event10_profesor = $exp_event_event10[0];
     $event_event10_observacion = $exp_event_event10[1];
   }
   else {
     $event_event10_profesor = $event_event10;
   }
	 $event_event11 = stripslashes($row["event11"]);
   if (stristr($event_event11, '||') == true) {
     $exp_event_event11 = explode('||', $event_event11);
     $event_event11_profesor = $exp_event_event11[0];
     $event_event11_observacion = $exp_event_event11[1];
   }
   else {
     $event_event11_profesor = $event_event11;
   }
	 $event_event12 = stripslashes($row["event12"]);
   if (stristr($event_event12, '||') == true) {
     $exp_event_event12 = explode('||', $event_event12);
     $event_event12_profesor = $exp_event_event12[0];
     $event_event12_observacion = $exp_event_event12[1];
   }
   else {
     $event_event12_profesor = $event_event12;
   }
	 $event_event13 = stripslashes($row["event13"]);
   if (stristr($event_event13, '||') == true) {
     $exp_event_event13 = explode('||', $event_event13);
     $event_event13_profesor = $exp_event_event13[0];
     $event_event13_observacion = $exp_event_event13[1];
   }
   else {
     $event_event13_profesor = $event_event13;
   }
	 $event_event14 = stripslashes($row["event14"]);
   if (stristr($event_event14, '||') == true) {
     $exp_event_event14 = explode('||', $event_event14);
     $event_event14_profesor = $exp_event_event14[0];
     $event_event14_observacion = $exp_event_event14[1];
   }
   else {
     $event_event14_profesor = $event_event14;
   }
      }
    }

	echo '<p><span class="far fa-calendar fa-fw"></span> '.$dayname.' - '.$current_day.'</p>';
	echo '<a href="//'.$config['dominio'].'/intranet/reservas/reservar/index.php?year='.$current_year.'&today='.$current_day.'&month='.$current_month.'&servicio='.$servicio.'">';

  //Nombre del día
 if (mysqli_num_rows($eventExec) == 1)
 {
 	 if ($event_event1_profesor !== "") {
 	    echo "<p>1ª hora: $event_event1_profesor</p>";
			if (isset($event_event1_observacion) && ! empty($event_event1_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event1_observacion</small></div>";
			}
 	}
 	 	 if ($event_event2_profesor !== "") {
 	    echo "<p>2ª hora: $event_event2_profesor</p>";
			if (isset($event_event2_observacion) && ! empty($event_event2_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event2_observacion</small></div>";
			}
 	}
 	 	 if ($event_event3_profesor !== "") {
 	    echo "<p>3ª hora: $event_event3_profesor</p>";
			if (isset($event_event3_observacion) && ! empty($event_event3_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event3_observacion</small></div>";
			}
 	}
 	 	 if ($event_event4_profesor !== "") {
 	    echo "<p>4ª hora: $event_event4_profesor</p>";
			if (isset($event_event4_observacion) && ! empty($event_event4_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event4_observacion</small></div>";
			}
 	}
 	 	 if ($event_event5_profesor !== "") {
 	    echo "<p>5ª hora: $event_event5_profesor</p>";
			if (isset($event_event5_observacion) && ! empty($event_event5_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event5_observacion</small></div>";
			}
 	}
 	 	 if ($event_event6_profesor !== "") {
 	    echo "<p>6ª hora: $event_event6_profesor</p>";
			if (isset($event_event6_observacion) && ! empty($event_event6_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event6_observacion</small></div>";
			}
 	}
 	 	 if ($event_event7_profesor !== "") {
 	    echo "<p>7ª hora: $event_event7_profesor</p>";
			if (isset($event_event7_observacion) && ! empty($event_event7_observacion)) {
				echo "<div style=\"margin-left: 20px;\"><small>$event_event7_observacion</small></div>";
			}
 	}
		if ($event_event8_profesor !== "") {
		 echo "<p>8ª hora: $event_event8_profesor</p>";
		 if (isset($event_event8_observacion) && ! empty($event_event8_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event8_observacion</small></div>";
		 }
	}
		if ($event_event9_profesor !== "") {
		 echo "<p>9ª hora: $event_event9_profesor</p>";
		 if (isset($event_event9_observacion) && ! empty($event_even9_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event9_observacion</small></div>";
		 }
	}
		if ($event_event10_profesor !== "") {
		 echo "<p>10ª hora: $event_event10_profesor</p>";
		 if (isset($event_event10_observacion) && ! empty($event_even10_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event10_observacion</small></div>";
		 }
	}
		if ($event_event11_profesor !== "") {
		 echo "<p>11ª hora: $event_event11_profesor</p>";
		 if (isset($event_event11_observacion) && ! empty($event_even11_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event11_observacion</small></div>";
		 }
	}
		if ($event_event12_profesor !== "") {
		 echo "<p>12ª hora: $event_event12_profesor</p>";
		 if (isset($event_event12_observacion) && ! empty($event_even12_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event12_observacion</small></div>";
		 }
	}
		if ($event_event13_profesor !== "") {
		 echo "<p>13ª hora: $event_event13_profesor</p>";
		 if (isset($event_event13_observacion) && ! empty($event_even13_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event13_observacion</small></div>";
		 }
	}
		if ($event_event14_profesor !== "") {
		 echo "<p>14ª hora: $event_event14_profesor</p>";
		 if (isset($event_event14_observacion) && ! empty($event_even14_observacion)) {
			 echo "<div style=\"margin-left: 20px;\"><small>$event_event14_observacion</small></div>";
		 }
	}

 }

echo "</a></p>";

   //$this_days_title = "";
   $event_event1 = "";
   $event_event2 = "";
   $event_event3 = "";
   $event_event4 = "";
   $event_event5 = "";
   $event_event6 = "";
   $event_event7 = "";
	 $event_event8 = "";
	 $event_event9 = "";
	 $event_event10 = "";
	 $event_event11 = "";
	 $event_event12 = "";
	 $event_event13 = "";
	 $event_event14 = "";
}
echo '<br>';
echo '<a class="btn btn-primary btn-block" href="//'.$config['dominio'].'/intranet/reservas/reservar/index.php?servicio='.$servicio.'">Reservar...</a>';
echo '</div>';
echo '</div>';

}
echo '</div>';
?>

</div>

<?php include("../pie.php");?>

</body>
</html>
