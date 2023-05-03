<?php
function checkLogin()
{
	require('config.php');
	session_start();

	if (isset($_SESSION['sessionUniqId']) && isset($_SESSION['userUniqId'])) {
		$sessionUniqId = $_SESSION['sessionUniqId'];
		$userUniqId = $_SESSION['userUnuqId'];

		try {
			$sessionDbConnection = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
			$sessionDbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Query con prepared statement e clausola WHERE
			$smtpSessionDbConnection = $sessionDbConnection->prepare("SELECT * FROM users WHERE sessionUniqId = :sessionUniqId");
			$smtpSessionDbConnection->bindParam(':sessionUniqId', $sessionUniqId);

			// Esecuzione della query con il valore del parametro
			$smtpSessionDbConnection->execute();

			// Controllo del numero di risultati
			$row_count = $smtpSessionDbConnection->rowCount();
			if ($row_count !== 1) {
				// TODO create a errorlog.txt file to save the error
				throw new Exception("La query ha trovato più di una riga");
			}

			// Elaborazione del risultato
			$row = $smtpSessionDbConnection->fetch(PDO::FETCH_ASSOC);
			if ($row) {
				$sessionError = 0;

				if (intval($row['loginTime']) + $req_inactivity_session_time < time()) {
					// error: inactivity
					$sessionError += 1;
				}

				if (intval($row['loginTime']) + $req_session_expire < time()) {
					// error: session expired
					$sessionError += 1;

				}

				if (intval($row['isValid']) !== 1) {
					// error: invalid session
					$sessionError += 1;

				}

				$userAgent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING);
				if ($userAgent !== $row['userAgent']) {
					// error: different user agent
					$sessionError += 1;
				}

				if ($sessionError !== 0) {
					// todo destroy and close session

				}
			} else {
				echo "Nessun risultato.";
			}
		} catch (PDOException $e) {
			echo "Errore di sessionDbConnectionessione al database: " . $e->getMessage();
		} catch (Exception $e) {
			echo "Errore nella query: " . $e->getMessage();
		}

		// Chiusura della sessionDbConnectionessione
		$sessionDbConnection = null;
	} else {
		session_destroy();
	}
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