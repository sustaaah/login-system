<?php
// Includi le librerie di PHPMailer
require 'lib/PHPMailer/Exception.php';
require 'lib/PHPMailer/PHPMailer.php';
require 'lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailer($params)
{
	require 'config.php';

	switch ($params['template']) {
		case "accountConfirmation": // array params for account confirmation and activation 1: template name 2: name 3: surname 4: email address 5: confirmation code
			$message = file_get_contents('confirmRegistration.html');
			$message = str_replace("[NAME]", $params['name'], $message);
			$message = str_replace("[EMAIL]", $params['email'], $message);
			$message = str_replace("[CODE]", $params['confirmationCode'], $message);

			$subject = "Confirm Registration";
			break;

		default:
			print('error');
			break;
	}

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
		$mail->addAddress($params['email'], $params['name'] . $params['surname']);
		// Configura l'oggetto e il corpo del messaggio
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->isHTML(true); // Imposta il formato del messaggio come HTML

		// Invia l'email
		$mail->send();
		return true;
	}
	catch (Exception $e) {
		return false;
	}
}
?>