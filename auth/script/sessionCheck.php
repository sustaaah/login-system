<?php
function checkLogin() {
	// todo check if last action is allowed
	// todo check if login is still valid
	// todo check if userAgent and ip are valid
	
}

function generateCsrfToken() {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken() {
	return $_SESSION['csrf_token'];
}

function checkCsrfToken($csrf_token){
	if($csrf_token == $_SESSION['csrf_token']){
		return true;
	}
	elseif ($csrf_token != $_SESSION['csrf_token']){
		return false;
	}
	else {
		return false;
	}
}
?>