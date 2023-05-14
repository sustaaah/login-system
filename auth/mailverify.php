<?php
require_once('script/config.php');

session_start();
if (!isset($_SESSION['userUniqId'])){
	// TODO finish redirecting
	header("Location: https://" . $req_domain . $req_path_to_login . "auth/login.php?e=" . $sessionError);
	die();
}

//
// TODO create a script to check if the user logged in need to check mail
//

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verify</title>
</head>
<body>
	
</body>
</html>