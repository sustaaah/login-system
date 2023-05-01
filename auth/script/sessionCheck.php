<?php
function checkLogin()
{
	require('config.php');
	session_start();

	if (isset($_SESSION['sessionUniqId']) && isset($_SESSION['userUniqId'])) {
		$sessionUniqId = $_SESSION['sessionUniqId'];
		$userUniqId = $_SESSION['userUnuqId'];

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Query con prepared statement e clausola WHERE
			$stmt = $conn->prepare("SELECT * FROM users WHERE sessionUniqId = :sessionUniqId");
			$stmt->bindParam(':sessionUniqId', $sessionUniqId);

			// Esecuzione della query con il valore del parametro
			$stmt->execute();

			// Elaborazione del risultato
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				// check last action

				// check if the session is expired

				// check if session is still valid

				
				
				
				
				
				echo "ID: " . $row["id"] . " - Nome: " . $row["nome"] . " - Cognome: " . $row["cognome"] . "<br>";
			
			
			
			} else {
				echo "Nessun risultato.";
			}
		}
		catch (PDOException $e) {
			echo "Errore di connessione al database: " . $e->getMessage();
		}

		
		
	} else {
		session_destroy();
		
	}
	
	// Chiusura della connessione
	$conn = null;
	// todo check if last action is allowed
	// todo check if login is still valid
	// todo check if userAgent and ip are valid

}

function generateCsrfToken()
{
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken()
{
	return $_SESSION['csrf_token'];
}

function checkCsrfToken($csrf_token)
{
	if ($csrf_token == $_SESSION['csrf_token']) {
		return true;
	} elseif ($csrf_token != $_SESSION['csrf_token']) {
		return false;
	} else {
		return false;
	}
}
?>