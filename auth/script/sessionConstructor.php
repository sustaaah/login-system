<?php
function login($uniqUserId, $username)
{

	require_once('config.php');
	ini_set('session.use_cookies', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.use_trans_id', 0);
	ini_set('session.use_strict_mode', 1);
	session_set_cookie_params([
		'lifetime' => $req_session_cookie_expire,
		'path' => $req_session_path,
		'domain' => $req_session_domain,
		'secure' => true,
		'httponly' => true,
		'samesite' => 'Lax'
	]);
	session_name($req_session_name);
	session_start();

	// Connessione al database
	try {
		$conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
		// Impostiamo il livello di errore a PDO::ERRMODE_EXCEPTION
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Query SQL con prepared statement
		$sql = "INSERT INTO session (sessionUniqId, user, userAgent, ip, userUniqId, loginTime, lastActivity, isValid) VALUES (:sessionUniqId, :user, :userAgent, :ip, :userUniqId, :loginTime, :lastActivity, :isValid)";
		$stmt = $conn->prepare($sql);

		$ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
		$sessionUniqId = sha1(bin2hex(random_bytes(32)) . $ip . $uniqUserId);
		$userAgent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING);
		$loginTime = time();
		$lastActivity = time();
		$isValid = 1;

		// Impostiamo i valori dei parametri
		$stmt->bindParam(':sessionUniqId', $sessionUniqId);
		$stmt->bindParam(':user', $username);
		$stmt->bindParam(':userAgent', $userAgent);
		$stmt->bindParam(':ip', $ip);
		$stmt->bindParam(':userUniqId', $uniqUserId);
		$stmt->bindParam(':loginTime', $loginTime);
		$stmt->bindParam(':lastActivity', $lastActivity);
		$stmt->bindParam(':isValid', $isValid);

		// Esecuzione della query
		$stmt->execute();
	} catch (PDOException $e) {
		logError($e->getMessage());
		die();
	}

	$_SESSION['userUniqId'] = $uniqIdUser;
	$_SESSION['sessionUniqId'] = $sessionUniqId;
}