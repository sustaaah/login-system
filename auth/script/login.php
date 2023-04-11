<?php
require('config.php');
session_start();

$mail = $_POST['mail'];
$password = $_POST['password'];

// databse pdo connection with prepared statement
$conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->query("SET NAMES 'utf8'");


// try catch for pdo error
try {
	$stmt = $conn->prepare("SELECT * FROM users WHERE mail =?");
	$stmt->bindParam(1, $mail);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
	$conn = null;
}

// close db pdo connection


$conn = null;


?>