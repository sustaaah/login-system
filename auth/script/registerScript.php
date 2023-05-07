<?php
require('config.php');

$data = array(
	'secret' => $req_hcaptcha_secretKey,
	'response' => $_POST['h-captcha-response']
);
$verify = curl_init();
curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
curl_setopt($verify, CURLOPT_POST, true);
curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($verify); 
// var_dump($response);
$responseData = json_decode($response);

if ($responseData->success) {
	try {
		$conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
		// Set the attribute to report errors
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		logError("Failed to connect to the database: " . $e->getMessage());
		die();
	}

	// User input data
	$name = $_POST['firstName']; // User's first name
	$surname = $_POST['lastName']; // User's last name
	$email = $_POST['email']; // Email of the account to register
	$username = $_POST['username']; // Username of the account to register
	$password = $_POST['password']; // Password of the account to register

	$validationName = $validationSurname = $validationEmail = $validationUsername = $validationPassword = 0;

	// Sanitize and validate user input
	$regexName = "/^([A-Z][a-z]+\s)?[A-Z][a-z]+$/";
	$name = trim($name);
	if (empty($name) || !preg_match($regexName, $name)) {
		$validationName = 1;
		die("Error: First name is missing or invalid.");
	}
	$surname = filter_var($surname, FILTER_SANITIZE_STRING);
	if (empty($surname) || !preg_match($regexName, $surname)) {
		$validationSurname = 1;
		die("Error: Last name is missing.");
	}

	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$validationEmail = 1;
		die("Error: Invalid email.");
	}

	$username = filter_var($username, FILTER_SANITIZE_STRING);
	if (empty($username)) {
		$validationUsername = 1;
		die("Error: Username is missing.");
	}

	$regexPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#\$!€()\-])(?=.*[^\s])[\S]{8,32}$/";
	if (!preg_match($regexPassword, $password)) {
		$validationPassword = 1;
		die("Error: Insert a valid password");
	}

	$validationCheck = $validationName + $validationSurname + $validationEmail + $validationUsername + $validationPassword;
	if ($validationCheck != 0) {
		// TODO insert error gateaway
		if ($validationName != 1) {
			
		}
		if ($validationSurname != 1) {

		}
		if ($validationUsername != 1) {

		}
		if ($validationPassword != 1) {

		}
		if ($validationEmail != 1) {

		}
	} elseif ($validationCheck == 0) {
		// Check if the account already exists in the database
		$query = "SELECT * FROM users WHERE email = :email OR username = :username";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":username", $username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			// Account already exists
			die("Error: Account already exists.");
		} else {
			// Register the new account in the database
			$query = "INSERT INTO users (uniq_id, name, surname, email, username, password, tfa_active, account_active, flagged_to, confirm_code, last_login, last_password_change, login_attempt, registration_date) VALUES (:userUniqId, :name, :surname, :email, :username, :password, :tfaActive, :accountActive,:flaggedTo, :confirmCode, :lastLogin, :lastPasswordChange, :loginAttempt, :registration_date)";
			$stmt = $conn->prepare($query);

			// prepare variables
			$userUniqId = sha1($username . uniqid());
			$confirmCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
			$lastPasswordChange = "null";
			$timestamp = time();
			$loginAttempt = 0;
			$tfaActive = 0;
			$loginAttempt = 0;
			$flaggedTo = "null";
			$loginAttempt = 0;
			$accountActive = 0;
			// Hash the password before inserting it into the database
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			// bind params //
			$stmt->bindParam(":userUniqId", $userUniqId);
			$stmt->bindParam(":name", $name);
			$stmt->bindParam(":surname", $surname);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":username", $username);
			$stmt->bindParam(":password", $hashedPassword);
			$stmt->bindParam(":tfaActive", $tfaActive);
			$stmt->bindParam(":accountActive", $accountActive);
			$stmt->bindParam(":flaggedTo", $flaggedTo);
			$stmt->bindParam(":confirmCode", $confirmCode);
			$stmt->bindParam(":lastLogin", $timestamp);
			$stmt->bindParam(":lastPasswordChange", $timestamp);
			$stmt->bindParam(":loginAttempt", $loginAttempt);
			$stmt->bindParam(":registration_date", $timestamp);

			$stmt->execute();

			//////////////////////////////////////////////////////////////

			// TODO set the account as 'flagged' or 'blocked' as soon as the email is confirmed
			// TODO redirect to verify.html page
			//
			// array params for account confirmation and activation
			// 1: template name
			// 2: name
			// 3: surname
			// 4: email address
			// 5: confirmation code
			//
			$params = array("template" => "accountConfirmation", "name" => $name, "surname" => $surname, "email" => $email, "confirmationCode" => $confirmCode);
			require('mailer.php');
			//////////////////////////////////////////////////////////////
			$status = "success";
		}

		// all ok, setup session
		require('sessionConstructor.php');
		login($userUniqId, $username);

		// TODO redirect to verify.php page

	} else {
		logError('logical error');
		$status = 'error';
	}
} else {
	// return error to user; they did not pass hcaptcha validation
	$status = 'error';
}

echo json_encode(array("status" => $status));
die();
?>