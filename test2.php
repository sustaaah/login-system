<?php
$multidimensionalArray = array('status' => 'error', 'error' => array('name', 'password'));

$newError = 'new error message';

array_push($multidimensionalArray['error'], $newError);

// stampa l'array modificato
print(json_encode($multidimensionalArray));
print_r($multidimensionalArray);