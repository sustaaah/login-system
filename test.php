<?php
function log_error($error_msg) {
    // Imposta il nome del file di log degli errori
    $file_name = "error_log.txt";

    // Apri il file di log degli errori e scrivi il messaggio di errore
    $file = fopen($file_name, "a");
    fwrite($file, date("d-m-Y H:i:s") . " - " . $error_msg . "\n");
    fclose($file);
}

// Utilizzo della funzione
$error_msg = "Errore: questa è una prova di registrazione degli errori.";
log_error($error_msg);
