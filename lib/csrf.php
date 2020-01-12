<?php if (! defined("INTRANET_DIRECTORY")) die ('No direct script access allowed');

function generateToken($length = 50) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    
}

function outputToken() {
    $token = generateToken();
    $_SESSION['csrf'] = $token;
    $output = '<input type="hidden" name="csrf" value="' . $token . '">';
    return $output;
}

function checkToken()  {

	if (isset($_POST['csrf'])) {
		if ($_POST['csrf'] == $_SESSION['csrf']) {
			return 1;
		}
		else {
			return 0;
		}
	}
	elseif (isset($_GET['csrf'])) {
		if ($_GET['csrf'] == $_SESSION['csrf']) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else {
		return 0;
	}
}
