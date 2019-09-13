<?php
require('../../bootstrap.php');

if (isset($_GET['month'])) {
	$month = $_GET['month'];
}
if (isset($_GET['year'])) {
	$year = $_GET['year'];
	}
if (isset($_GET['today'])) {
	$today = $_GET['today'];
	}
if (isset($_GET['servicio'])) {
	$servicio = $_GET['servicio'];
}
for ($i=1;$i<=7;$i++)
{
//echo $_POST['day_event'.$i];
if (isset($_POST['day_event'.$i])) { $day_event{$i} = $_POST['day_event'.$i]; }
elseif (isset($_GET['day_event'.$i])) { $day_event{$i} = $_GET['day_event'.$i]; }
else{$day_event{$i}="";}
}
if (isset($_GET['month'])) { $month = intval($_GET['month']); }
if (isset($_POST['month'])) { $month = intval($_POST['month']); }

if (isset($_GET['year'])) { $year = intval ($_GET['year']); }
if (isset($_POST['year'])) { $year = intval ($_POST['year']); }

if ($year < 1990) { $year = 1990; } if ($year > 2035) { $year = 2035; }

if (isset($_GET['today'])) { $today = intval ($_GET['today']); }
if (isset($_POST['today'])) { $today = intval ($_POST['today']); }


$month = (isset($month)) ? $month : date("n",time());
$year = (isset($year)) ? $year : date("Y",time());
$today = (isset($today))? $today : date("j", time());

$sql_date = "$year-$month-$today";
$semana = date( mktime(0, 0, 0, $month, $today, $year));
$hoy = getdate($semana);
$numero_dia = $hoy['wday'];

$eventQuery = "SELECT id FROM reservas WHERE eventdate = '$sql_date' and servicio = '$servicio'";
$eventExec = mysqli_query($db_con, $eventQuery);
$event_found = "";
while($row = mysqli_fetch_array($eventExec)) {
  //$echo = $row["id"];
  $event_found = 1;
}

$day_event1 = trim($_POST['day_event1']);
$day_event2 = trim($_POST['day_event2']);
$day_event3 = trim($_POST['day_event3']);
$day_event4 = trim($_POST['day_event4']);
$day_event5 = trim($_POST['day_event5']);
$day_event6 = trim($_POST['day_event6']);
$day_event7 = trim($_POST['day_event7']);

if (isset($_POST['day_event1_obs']) && ! empty(trim($_POST['day_event1_obs']))) $day_event1 .= "||" .$_POST['day_event1_obs'];
if (isset($_POST['day_event2_obs']) && ! empty(trim($_POST['day_event2_obs']))) $day_event2 .= "||" .$_POST['day_event2_obs'];
if (isset($_POST['day_event3_obs']) && ! empty(trim($_POST['day_event3_obs']))) $day_event3 .= "||" .$_POST['day_event3_obs'];
if (isset($_POST['day_event4_obs']) && ! empty(trim($_POST['day_event4_obs']))) $day_event4 .= "||" .$_POST['day_event4_obs'];
if (isset($_POST['day_event5_obs']) && ! empty(trim($_POST['day_event5_obs']))) $day_event5 .= "||" .$_POST['day_event5_obs'];
if (isset($_POST['day_event6_obs']) && ! empty(trim($_POST['day_event6_obs']))) $day_event6 .= "||" .$_POST['day_event6_obs'];
if (isset($_POST['day_event7_obs']) && ! empty(trim($_POST['day_event7_obs']))) $day_event7 .= "||" .$_POST['day_event7_obs'];

$day_event_safe1 = addslashes($day_event1);
$day_event_safe2 = addslashes($day_event2);
$day_event_safe3 = addslashes($day_event3);
$day_event_safe4 = addslashes($day_event4);
$day_event_safe5 = addslashes($day_event5);
$day_event_safe6 = addslashes($day_event6);
$day_event_safe7 = addslashes($day_event7);
if ($event_found == 1) {
  //UPDATE
    $postQuery = "UPDATE `reservas` SET event1 = '".$day_event_safe1."', event2 = '".$day_event_safe2."', event3 = '".$day_event_safe3."',
    event4 = '".$day_event_safe4."', event5 = '".$day_event_safe5."', event6 = '".$day_event_safe6."', event7 = '".$day_event_safe7."' WHERE eventdate = '$sql_date' and servicio = '$servicio';";
    $postExec = mysqli_query($db_con, $postQuery) or die("Could not Post UPDATE Event to database!");
    mysqli_query($db_con, "DELETE FROM `reservas` WHERE event1 = '' and event2 = ''  and event3 = ''  and event4 = ''  and event5 = ''  and event6 = ''  and event7 = '' and servicio = '$servicio'");
mysqli_close($conn);
	header("Location: index.php?servicio=$servicio&year=$year&month=$month&today=$today&mens=actualizar");

} else {
  //INSERT
    $postQuery = "INSERT INTO `reservas` (eventdate,dia,event1,event2,event3,event4,event5,event6,event7,html,servicio) VALUES ('$sql_date','$numero_dia','".$day_event_safe1."','".$day_event_safe2."','".$day_event_safe3."','".$day_event_safe4."','".$day_event_safe5."','".$day_event_safe6."','".$day_event_safe7."','$show_html', '$servicio')";

    $postExec = mysqli_query($db_con, $postQuery) or die('Error: '.mysqli_error($db_con));
    mysqli_query($db_con, "DELETE FROM `reservas` WHERE event1 = '' and event2 = ''  and event3 = ''  and event4 = ''  and event5 = ''  and event6 = ''  and event7 = '' and servicio = '$servicio'");
mysqli_close($conn);
    header("Location: index.php?servicio=$servicio&year=$year&month=$month&today=$today&mens=insertar");

}
?>
