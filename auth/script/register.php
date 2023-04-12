<?php
// TODO insert error code
require('config.php');
require('mailer.php');
// TODO insert db variables from config.php

try {
    $conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $username, $password);
    // Set the attribute to report errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Failed to connect to the database: " . $e->getMessage());
}

// User input data
$name = $_POST['nome']; // User's first name
$surname = $_POST['cognome']; // User's last name
$email = $_POST['email']; // Email of the account to register
$username = $_POST['username']; // Username of the account to register
$password = $_POST['password']; // Password of the account to register

// Sanitize and validate user input
// TODO validate name with regex
$name = filter_var($name, FILTER_SANITIZE_STRING);
if (empty($name)) {
    die("Error: First name is missing.");
}

// TODO validate surname with regex
$surname = filter_var($surname, FILTER_SANITIZE_STRING);
if (empty($surname)) {
    die("Error: Last name is missing.");
}

$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email.");
}

$username = filter_var($username, FILTER_SANITIZE_STRING);
if (empty($username)) {
    die("Error: Username is missing.");
}

$regexPassword  = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#\$!€()\-])(?=.*[^\s])[\S]{8,32}$/";
if (!preg_match($regexPassword, $password)){
	die("Error: Insert a valid password");
}

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
	// TODO insert all the records
	$query = "INSERT INTO users (uniq_id, name, surname, email, username, password, tfa_active, account_active, flagged_to, confirm_code, last_login, last_password_change, login_attempt) VALUES (:userUniqId, :name, :surname, :email, :username, :password, :tfaActive, :accountActive,:flaggedTo, :confirmCode, :lastLogin, :lastPasswordChange, :loginAttempt)";
    $stmt = $conn->prepare($query);

	// prepare variables
	$userUniqId = sha1($username.uniqid());
	$confirmCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
	$lastPasswordChange = "null";
	$lastLogin = time();
	$
	$loginAttempt = 0;
	$tfaActice = 0;
	// Hash the password before inserting it into the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	// bind params
	$stmt->bindParam(":userUniqId", $userUniqId);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":surname", $surname);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $hashedPassword);
	$stmt->bindParam(":tfaActive", $);
	$stmt->bindParam(":accountActive", $);
	$stmt->bindParam(":flaggedTo", $);
	$stmt->bindParam(":confirmCode", $);
	$stmt->bindParam(":lastLogin", $);
	$stmt->bindParam(":lastPasswordChange", );
	$stmt->bindParam(":loginAttempt", "0");
    $stmt->execute();
	// 
	// TODO finish the script
	// 
	
	//////////////////////////////////////////////////////////////
	
	// TODO create a mail template for email confirm code
	// TODO sent code
	// TODO save code in db
	// TODO set the account as 'flagged' or 'blocked' as soon as the email is confirmed
	// TODO redirect to verify.html page

	// TODO create an array that contains info to insert into the message

	
	mailer();




	//////////////////////////////////////////////////////////////
	
	
    echo "Account registered successfully.";
}

?>