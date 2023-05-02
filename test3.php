<?php
require('auth/script/auth.php');

$auth = new auth();

$auth->responseDataUpdate('status', 'error');
$auth->responseDataUpdate('error', 'name');
$auth->responseDataUpdate('error', 'password');


$auth->responseDataPrint();