<?php
require("auth/script/lib/JWT/JWT.php");
require("auth/script/lib/JWT/Key.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function encrypt ($payload){
	$key = 'example_key';
	$jwt = JWT::encode($payload, $key, 'HS256');
	return $jwt;
}

function decrypt($jwt){
	$key = 'example_key';

	$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
	$decoded = (array) $decoded;
	print_r($decoded['username']);
}



$payload = [
	'username' => 'jonny'
];
$jwt = encrypt($payload);

decrypt($jwt);


?>