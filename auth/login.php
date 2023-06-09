<?php
session_start();

// todo check if is a real session variable
if(isset($_SESSION['uniqid'])){
	
}
$error = false;
if (isset($_GET['sessionError'])){
	switch ($_GET['sessionError']){
		case 'expired':
			$error = "The current session has expired";
			break;
	
		case 'inactivity':
			$error = "The current session was terminated due to inactivity.";
			break;
	
		default:
			$error = false;
			break;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<h1>Log in to your account</h1>
	<?php
if($error !== false){

	print('<div>' . $error . '</div>');
}
?>
	<form onsubmit="return login()">
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required>
		<br><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>
		<div class="h-captcha" data-sitekey="e47a8603-8cf8-4d65-bae2-e8f831885212"></div>
		<br><br>
		<input type="submit" value="Sing in">
	</form>
	<script src="script/login.js"></script>
	<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</body>
</html>