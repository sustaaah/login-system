<?php
function checkLogin()
{
	require('config.php');
	session_start();

	if (isset($_SESSION['sessionUniqId']) && isset($_SESSION['userUniqId'])) {
		$sessionUniqId = $_SESSION['sessionUniqId'];
		$userUniqId = $_SESSION['userUnuqId'];

		try {
			$sessionDbuserDbConnectionection = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
			$sessionDbuserDbConnectionection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Query con prepared statement e clausola WHERE
			$smtpSessionDbuserDbConnectionection = $sessionDbuserDbConnectionection->prepare("SELECT * FROM users WHERE sessionUniqId = :sessionUniqId");
			$smtpSessionDbuserDbConnectionection->bindParam(':sessionUniqId', $sessionUniqId);

			// Esecuzione della query con il valore del parametro
			$smtpSessionDbuserDbConnectionection->execute();

			// Controllo del numero di risultati
			$resultSessionDbuserDbConnectionection_count = $smtpSessionDbuserDbConnectionection->rowCount();
			if ($resultSessionDbuserDbConnectionection_count !== 1) {
				// TODO create a errorlog.txt file to save the error
				throw new Exception("La query ha trovato più di una riga");
			}

			// Elaborazione del risultato
			$resultSessionDbuserDbConnectionection = $smtpSessionDbuserDbConnectionection->fetch(PDO::FETCH_ASSOC);
			if ($resultSessionDbuserDbConnectionection) {
				$sessionError = 0;
				$validSession = false;

				if (intval($resultSessionDbuserDbConnectionection['loginTime']) + $req_inactivity_session_time < time()) {
					// error: inactivity
					$sessionError += 1;
				}

				if (intval($resultSessionDbuserDbConnectionection['loginTime']) + $req_session_expire < time()) {
					// error: session expired
					$sessionError += 1;

				}

				if (intval($resultSessionDbuserDbConnectionection['isValid']) !== 1) {
					// error: invalid session
					$sessionError += 1;

				}

				$userAgent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRING);
				if ($userAgent !== $resultSessionDbuserDbConnectionection['userAgent']) {
					// error: different user agent
					$sessionError += 1;
				}

				if ($sessionError == 0) {
					// session valid, check user
					try {
						$userDbConnection = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
						$userDbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						// Query con prepared statement e clausola WHERE
						$stmtUserDbConnection = $userDbConnection->prepare("SELECT * FROM USERS WHERE uniq_id = :uniq_id");
						$stmtUserDbConnection->bindParam(':uniq_id', $resultSessionDbuserDbConnectionection['userUniqId']);

						// Esecuzione della query con il valore del parametro id
						$id = 1;
						$stmtUserDbConnection->execute();

						// Elaborazione del risultato
						$rowUserDbConnection = $stmtUserDbConnection->fetch(PDO::FETCH_ASSOC);
						if ($rowUserDbConnection) {

							// TODO CHECK IF ACCOUNT IS FLAGGED, BLOCKED

						} else {
							echo "Nessun risultato.";
						}
					} catch (PDOException $e) {
						echo "Errore di userDbConnectionessione al database: " . $e->getMessage();
					}

					// close connection
					$userDbConnection = null;

					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////x











				}

				if ($validSession === false) {
					// todo destroy and close session

				}

			} else {
				echo "Nessun risultato.";
			}
		} catch (PDOException $e) {
			echo "Errore di sessionDbuserDbConnectionectionessione al database: " . $e->getMessage();
		} catch (Exception $e) {
			echo "Errore nella query: " . $e->getMessage();
		}

		// Chiusura della sessionDbuserDbConnectionectionessione
		$sessionDbuserDbConnectionection = null;
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