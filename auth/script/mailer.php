<?php
require_once('lib/PHPMailer/Exception.php');
require_once('lib/PHPMailer/PHPMailer.php');
require_once('lib/PHPMailer/SMTP.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('config.php');

if (isset($params['template'])) {
	switch ($params['template']) {
		case "accountConfirmation":
			$message = file_get_contents('template/confirmRegistration.html');
			$message = str_replace("[NAME]", $params['name'], $message);
			$message = str_replace("[EMAIL]", $params['email'], $message);
			$message = str_replace("[CODE]", $params['confirmationCode'], $message);

			$subject = "Confirm Registration";
			break;

		default:
			logError('Invalid email template');
			die();
			
	}

	$mail = new PHPMailer(true);

	try {
		$mail->isSMTP();
		$mail->Host = $req_smtp_hostname;
		$mail->SMTPAuth = true;
		$mail->Username = $req_smtp_username;
		$mail->Password = $req_smtp_password;
		$mail->SMTPSecure = $req_smtp_secure;
		$mail->Port = $req_smtp_port;

		$mail->setFrom($req_smtp_from_mail, $req_smtp_from_name);
		$mail->addAddress($params['email'], $params['name'] . ' ' . $params['surname']);
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->isHTML(true);

		if (!$mail->send()) {
			logError('Unable to send email: ' . $mail->ErrorInfo);
			die();
		}
		return true;
	} catch (Exception $e) {
		logError('Unable to send email: ' . $e->getMessage());
		die();
	}
} else {
	logError('Email template not set');
	die();
}