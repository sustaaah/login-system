<?php
function login($uniqIdUser){
	require('config.php');
	session_set_cookie_params($expire, '/', $domain, true, true); // TODO insert variable
	session_name($req_session_name);
	session_start();

	// Connessione al database
	try {
		$conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
		// Impostiamo il livello di errore a PDO::ERRMODE_EXCEPTION
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Query SQL con prepared statement
		$sql = "INSERT INTO session (sessionUniqId, user, userAgent, ip, userUniqId, loginTime, expireTime, lastActivity, isValid) VALUES (:sessionUniqId, :user, :userAgent, :ip, :userUniqId, :loginTime, :expireTime, :lastActivity, :isValid)";
		$stmt = $conn->prepare($sql);

		// Impostiamo i valori dei parametri
		$stmt->bindParam(':sessionUniqId', $);
		$stmt->bindParam(':user', $);
		$stmt->bindParam(':userAgent', $);
		$stmt->bindParam(':ip', $);
		$stmt->bindParam(':userUniqId', $);
		$stmt->bindParam(':loginTime', $);
		$stmt->bindParam(':expireTime', $);
		$stmt->bindParam(':lastActivity', $);
		$stmt->bindParam(':isValid', $);
		
		// Esecuzione della query
		$stmt->execute();

		$_SESSION['userUniqId'] = $uniqIdUser;
	} catch (PDOException $e) {
		echo "Errore durante l'inserimento dell'utente: " . $e->getMessage();
	}
}