<?php
require('../../bootstrap.php');
require_once('GoogleAuthenticator.php');
$ga = new PHPGangsta_GoogleAuthenticator();

$result = mysqli_query($db_con, "SELECT `totp_secret` FROM `c_profes` WHERE `idea` = '".$_SESSION['ide']."' LIMIT 1");
$row = mysqli_fetch_array($result);
if ((isset($_POST['totp_code']) && is_numeric($_POST['totp_code']) && (strlen($_POST['totp_code']) == 6)) && $row['totp_secret'] != NULL) {
    $secret = $row['totp_secret'];
    
    $checkResult = $ga->verifyCode($secret, $_POST['totp_code'], 2);    // 2 = 2*30sec clock tolerance
    if ($checkResult) {
        $jsondata['status'] = true;
    } else {
        $jsondata['status'] = false;
    }

}
else {
    $jsondata['status'] = false;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata);
exit();