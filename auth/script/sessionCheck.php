<?php
function checkLogin()
{
	require('config.php');
	session_start();

	if (isset($_SESSION['sessionUniqId']) && isset($_SESSION['userUniqId'])) {
		$sessionUniqId = $_SESSION['sessionUniqId'];
		$userUniqId = $_SESSION['userUniqId'];
		$validSeesion = false;
		$needRedirect = false;

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
						
						$stmtUserDbConnection->execute();

						// Elaborazione del risultato
						$rowUserDbConnection = $stmtUserDbConnection->fetch(PDO::FETCH_ASSOC);
						if ($rowUserDbConnection) {
							if ($rowUserDbConnection['active'] == 1){
								// account active
								if ($rowUserDbConnection['flaggedTo'] == 'null' || $rowUserDbConnection['flaggedTo'] < time()) {
									// account not flagged
									// SESSION OK
									$validSeesion = true;
								}
							}
							else{
								// account deactivated
								$sessionError = "deactivated"
								$validSeesion = false;
							}

						} else {
							// can't find and account linked to the session, destroy session
							$sessionError = "noLinkedAccount";
							$validSeesion = false;
						}
					} catch (PDOException $e) {
						// TODO INSERT ERROR IN LOG FILE
						// echo "Errore di userDbConnectionessione al database: " . $e->getMessage();
						$validSeesion = false;
					}

					// close connection
					$userDbConnection = null;
				}
			} else {
				// error TODO SPECIFY ERROR IN THE COMMENT
				$validSeesion = false;
			}
		} catch (PDOException $e) {
			// TODO INSERT ERROR IN LOG FILE
			// echo "Errore di sessionDbuserDbConnectionectionessione al database: " . $e->getMessage();
			$validSeesion = false;
		} catch (Exception $e) {
			// TODO INSERT ERROR IN LOG FILE
			// echo "Errore nella query: " . $e->getMessage();
			$validSeesion = false;
		}

		// Chiusura della sessionDbuserDbConnectionectionessione
		$sessionDbuserDbConnectionection = null;

		if ($validSession === true){
			// valid session
			return true;
		}
		else{
			// session invalid: destroy and close session
			// TODO redirect to login page with error details
			
			$needRedirect = true;
			
		}
	} else {
		// error: session variables non set, destroy session
		session_unset();
		session_destroy();

		$needRedirect = true;
	}

	if ($needRedirect === true){
		// TODO check variables with config.php
		header("Location: https://" . $req_domain . $req_path . "auth/login.php?e=" . $sessionError);
		die();
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