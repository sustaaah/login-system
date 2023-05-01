<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
</head>

<body>
	<h1>Registration Form</h1>
	<form id="registrationForm">
		<label for="firstName">First Name:</label>
		<input type="text" id="inputName" name="firstName" required><br>

		<label for="lastName">Last Name:</label>
		<input type="text" id="inputSurname" name="lastName" required><br>

		<label for="email">Email:</label>
		<input type="email" id="inputEmail" name="email" required><br>

		<label for="username">Username:</label>
		<input type="text" id="inputUsername" name="username" required><br>

		<label for="password">Password:</label>
		<input type="password" id="inputPassword" name="password" required><br>

		<label for="confirmPassword">Confirm Password:</label>
		<input type="password" id="inputConfirmPassword" name="confirmPassword" required><br>
		<div class="h-captcha" data-sitekey="e47a8603-8cf8-4d65-bae2-e8f831885212"></div>

		<input type="submit" value="Submit">
	</form>
	<script src="script/register.js"></script>
	<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</body>
</html>