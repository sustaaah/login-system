<?php
require('config.php');

$data = array(
	'secret' => $req_hcaptcha_secretKey,
	'response' => $_POST['captchaResponse']
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
	// Prendi l'input dell'username e della password dall'utente
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = $_POST['password'];

	// Verifica che l'input dell'username non sia vuoto
	if (empty($username)) {
		echo "Inserisci un nome utente valido.";
	} else {
		// Connessione al database
		$dsn = "mysql:host=$req_dbhostname;dbname=$req_dbname";
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			$pdo = new PDO($dsn, $req_dbusername, $req_dbpassword, $options);
		} catch (PDOException $e) {
			echo "Connessione al database fallita: " . $e->getMessage();
			die();
		}

		// Query per selezionare l'utente dal database
		$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->execute(array(':username' => $username));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verifica che l'utente esista e che la password sia corretta
		if ($user || password_verify($password, $user['password'])) {
			// Richiama la funzione per l'accesso
			require('sessionConstructor.php');
			login($user['uniq_id'], $username);
			print('login successful');
		} else {
			echo "Nome utente o password non validi.";
		}
	}
} else {
	// return error to user; they did not pass
	die('hcaptcha failed');
}

?>