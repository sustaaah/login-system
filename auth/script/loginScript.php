<?php
function login($uniqIdUser){
	require('config.php');
	session_start();
	session_name($req_session_name);

	// Connessione al database
	try {
		$conn = new PDO("mysql:host=$req_dbhostname;dbname=$req_dbname", $req_dbusername, $req_dbpassword);
		// Impostiamo il livello di errore a PDO::ERRMODE_EXCEPTION
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Query SQL con prepared statement
		$sql = "INSERT INTO utenti (nome, cognome) VALUES (:nome, :cognome)";
		$stmt = $conn->prepare($sql);

		// Impostiamo i valori dei parametri
		$nome = "Mario";
		$cognome = "Rossi";
		$stmt->bindParam(':nome', $nome);
		$stmt->bindParam(':cognome', $cognome);

		// Esecuzione della query
		$stmt->execute();

		echo "Nuovo utente inserito con successo";
	} catch (PDOException $e) {
		echo "Errore durante l'inserimento dell'utente: " . $e->getMessage();
	}
}