<?php
// Includi le librerie di PHPMailer
require 'lib/PHPMailer/Exception.php';
require 'lib/PHPMailer/PHPMailer.php';
require 'lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailer($template, $to, $firstName, $secondName, $data1, $data2, $data3)
{
	require 'config.php';

	// Dichiarazione delle variabili per il messaggio
	$nome = $firstName;
	$cognome = $secondName;
	$email = $to;

	// TODO create switcha

	switch ($template) {
		case "verifyMail":
			break;

		default:
			print('error');
			break;
	}

	// Leggi il contenuto del file messaggio.html
	$messaggio = file_get_contents('messaggio.html');

	// Sostituisci le variabili nel messaggio
	$messaggio = str_replace("[NOME]", $firstName, $messaggio);
	$messaggio = str_replace("[COGNOME]", $secondName, $messaggio);
	$messaggio = str_replace("[EMAIL]", $to, $messaggio);

	// Crea una nuova istanza di PHPMailer
	$mail = new PHPMailer(true);

	try {
		// Configura le impostazioni di invio email
		$mail->isSMTP();
		$mail->Host = $req_smtp_hostname; // Indirizzo del server SMTP di Aruba
		$mail->SMTPAuth = true;
		$mail->Username = $req_smtp_username; // La tua email
		$mail->Password = $req_smtp_password; // La tua password
		$mail->SMTPSecure = 'tls';
		$mail->Port = $req_smtp_port;

		// Configura i dettagli del mittente e del destinatario
		$mail->setFrom($req_smtp_from_mail, $req_smtp_from_name);
		$mail->addAddress('destinatario@example.com', 'Nome del destinatario'); // FIXME

		// Configura l'oggetto e il corpo del messaggio
		$mail->Subject = 'Oggetto dell\'email'; // FIXME
		$mail->Body = $messaggio; // FIXME
		$mail->isHTML(true); // Imposta il formato del messaggio come HTML

		// Invia l'email
		$mail->send();
		echo 'Email inviata correttamente!'; // FIXME
	}
	catch (Exception $e) {
		echo 'Errore durante l\'invio dell\'email: ' . $mail->ErrorInfo; // FIXME
	// TODO implement error code
	}
}




?>